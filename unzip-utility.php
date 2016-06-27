<?php

if ( !empty( $_POST ) ) {
	
	$errors = array();
	$messages = array();
	
	ini_set('max_execution_time', 300);
	
	if ( empty( $_POST[ 'zip-filename' ] )
	OR '' == $_POST[ 'zip-filename' ] ) {
		$errors[] = 'No zip file specified.';
	} else {
		$zip_filename = $_POST[ 'zip-filename' ];
	}
	
	$zip_archive = new ZipArchive();
	
	if ( empty( $errors ) AND TRUE !== $zip_archive->open( $zip_filename ) ) {
		
		$errors[] = 'Sorry, PHP ZipArchive could not open the zip file.';
		
	}
	
	if ( empty( $errors ) ) {
		
		$zip_archive->extractTo( '.' );
	
	}
	
	if ( 0 == count( $errors ) ) {
		$messages[] = 'Congratulations! Your zip file has successfully been extracted.';
	}
	
	$zip_archive->close();
	
}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Unzip Utility</title>
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

	<h1>Unzip Utility</h1>

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
	
	<p>
		Instructions?
	</p>

	<div class="options">
		<form method="post" action="<?php echo $_SERVER[ 'PHP_SELF' ]; ?>">
			<label for="zip-filename">File To Unzip:</label>
			<input type="text" name="zip-filename" id="zip-filename" />
			<script type="text/javascript">document.getElementById( 'zip-filename' ).select();</script>
			<div>
				<button type="submit">Submit</button>
			</div>
		</form>
	</div>

</body>
</html>