<html>

<head>
	<title>Upload GPX</title>
</head>

<body>
	<form action="" method="post" enctype="multipart/form-data">
	<input type="file" id="file" name="file" />
	<button type="submit" name="submit">Upload</button>
	</form>
</body>

</html>

<?php

if (isset($_POST['submit'])) {
	$srcFile = $_FILES["file"]["tmp_name"];
	$destFolder = "gpx/";
	$destFile = $destFolder . "mrbarth.gpx";
	if (move_uploaded_file($srcFile, $destFile)) {
		print('OK');
	} else {
		print('NO');
	}
}

?>
