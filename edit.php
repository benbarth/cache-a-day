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
	$fileName = $_FILES["file"]["name"];
	$srcFile = $_FILES["file"]["tmp_name"];

	$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

	if ($fileExtension == "zip") {
		//Let's unzip the gpx
		$tempDir = "tmp/";
		$gpxFileName = basename($fileName, ".zip") . ".gpx";
		$zip = new ZipArchive;
		if ($zip->open($srcFile) === TRUE) {
			echo $gpxFileName;
			if ($zip->extractTo($tempDir, $gpxFileName)) {
				$srcFile = $tempDir . $gpxFileName;
			} else {
				die("Unable to extract");
			}
			$zip->close();
			echo $srcFile;
		} else {
			die("Unable to open zip");
		}
	} else if ($fileExtension == "gpx") {
		// Good
	} else {
		die("Invalid file type");
	}

	$gpx = simplexml_load_file($srcFile) or die("Unable to parse gpx");

	$user = $gpx->wpt->children("groundspeak", true)->cache->children("groundspeak", true)->logs[0]->children("groundspeak", true)->log->children("groundspeak", true)->finder;

	$destFolder = "gpx/";
	$destFile = $destFolder . $user . ".gpx";

	if (rename($srcFile, $destFile)) {
		print('OK');
		print('<script>window.location.replace("./?user=' . $user . '");</script>');
	} else {
		print('NO');
	}
}

?>
