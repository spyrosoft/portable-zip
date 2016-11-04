<?php

if ( !empty( $_POST ) ) {
	
	$errors = array();
	$messages = array();
	
	if ( empty( $_POST[ 'directory-to-zip' ] )
	OR '' == $_POST[ 'directory-to-zip' ] ) {
		$errors[] = 'No directory to zip specified';
	} else {
		$directory_to_zip = $_POST[ 'directory-to-zip' ];
	}
	
	ini_set("max_execution_time", 300);
	
	if ( !empty( $_POST[ 'zip-filename' ] ) ) {
		$zip_filename = $_POST[ 'zip-filename' ];
	} else {
		$zip_filename = 'zip-up-files.zip';
	}
	
	if ( !empty( $_POST[ 'unlink-existing-zip-file' ] ) ) {
		if ( file_exists( $zip_filename ) ) {
			if ( ! is_writable( $zip_filename ) ) {
				$errors[] = 'Failed to delete the existing zip file.';
			} else {
				unlink( $_POST[ 'unlink-existing-zip-file' ] );
			}
		} else {
			$messages[] = 'There is no such zip file to delete yet!';
		}
	}
	
	$zip_archive = new ZipArchive();
	
	if ( empty( $errors ) AND TRUE !== $zip_archive->open( $zip_filename, ZIPARCHIVE::CREATE ) ) {
		
		$errors[] = 'Sorry, PHP ZipArchive could not open the new zip file for writing.';
		
	} elseif ( empty( $errors ) ) {
		
		$directory_iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $directory_to_zip )
		);
		
		foreach ( $directory_iterator as $file_to_zip ) {
			
			if ( ! $zip_archive->addFile( realpath( $file_to_zip ), $file_to_zip ) ) {
				$errors[] = 'Sorry, could not add file: ' . $key;
			}
			
		}
	
	}
	
	if ( 0 == count( $errors ) ) {
		$messages[] = 'Congratulations! Download your <a href="' . $zip_filename . '" target="_blank">new zip file</a>!';
	}
	
	$zip_archive->close();
	
}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Zip Up Files and Directories for Download</title>
	<style type="text/css">
	* { margin: 0; padding: 0; }
		body {
			padding: 40px;
			font-size: 14px;
			font-family: sans-serif;
		}
		body,
		input,
		button {
			background-color: #000000;
			color: #FFFFFF;
		}
		h1 {
			text-align: center;
			margin-bottom: 20px;
		}
		label {
			display: inline-block;
			margin: 20px 0;
		}
		input,
		button {
			padding: 2px 4px;
		}
		input,
		button {
			border: 1px solid #FFFFFF;
			display: inline-block;
		}
		input[type="text"] {
			width: 100%;
		}
		input[type="checkbox"] {
			display: inline-block;
			margin-right: 5px;
		}
		a {
			color: #0000FF;
		}
		.error {
			color: #FF0000;
		}
		.error,
		.message {
			margin-bottom: 20px;
		}
		.options div {
			margin: 20px 0;
		}
	</style>
</head>
<body>

	<h1>Zip Up Files and Directories for Download</h1>

<?php if ( ! empty( $errors ) ) : ?>
	<div class="error">
<?php foreach ( $errors as $error ) : ?>
		<p><?php echo $error; ?></p>
<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php if ( ! empty( $messages ) ) : ?>
	<div class="message">
<?php foreach ( $messages as $message ) : ?>
		<p><?php echo $message; ?></p>
<?php endforeach; ?>
	</div>
<?php endif; ?>

	<div class="options">
		<form method="post" action="<?php echo $_SERVER[ 'PHP_SELF' ]; ?>">
			<label for="zip-filename">Zip Filename:</label>
			<input type="text" name="zip-filename" id="zip-filename" />
			<script type="text/javascript">document.getElementById( 'zip-filename' ).select();</script>
			<label for="directory-to-zip">Directory to Zip:</label>
			<input type="text" name="directory-to-zip" id="directory-to-zip" />
			<input type="checkbox" name="unlink-existing-zip-file" id="unlink-existing-zip-file" />
			<label for="unlink-existing-zip-file">Delete Existing File of this Name</label>
			<div>
				<button type="submit">Submit</button>
			</div>
		</form>
	</div>

</body>
</html>
