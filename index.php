<html>
<head>
	<title>cache-a-day</title>
</head>
<body>
<?php

$user = $_GET["user"];
$user = preg_replace("/[^a-z\d ]/i", '', $user);

if ($user == null) {
?>
<form action="" method="get">
<input type="text" name="user" placeholder="User" />
<input type="submit" />
</form>
<?php
} else {

$timezoneToronto = new DateTimeZone("America/Toronto");

$gpxFile = "gpx/" . $user . ".gpx";

if (!file_exists($gpxFile)) {
	die("User not found");
}

$gpx = simplexml_load_file($gpxFile);

echo "<h1>cache-a-day</h1>";

$now = new DateTime('now');
$now->setTimezone($timezoneToronto);

$tomorrow = new DateTime('+1 day');
$tomorrow->setTimezone($timezoneToronto);

$day2 = new DateTime('+2 day');
$day2->setTimezone($timezoneToronto);

$day3 = new DateTime('+3 day');
$day3->setTimezone($timezoneToronto);

echo "<strong>" . $now->format("F j") . "</strong>";
echo "<br/><br/>";

function cachesFoundOnDate($gpx, $date) {
	$foundString = "";
	foreach($gpx->wpt as $cache) {
		$logs = $cache->children('groundspeak', true)->cache->children('groundspeak', true)->logs;
		if ($logs) {
			foreach($logs as $log) {
				$logDateData = $log->children('groundspeak', true)->log->children('groundspeak', true)->date;
				$logDate = DateTime::createFromFormat(DateTime::ATOM, $logDateData);
				$logDate->setTimezone($timezoneToronto);
				$logDateString = $logDate->format("m-d");
				$dateString = $date->format("m-d");
				if ($logDateString == $dateString) {
					$foundString = $foundString . ($found ? "," : "") . " <a href=\"" . $cache->url . "\">" . $cache->name . "</a>";
					$found = true;
				}
			}
		}
	}
	return $foundString;
}

$foundString = cachesFoundOnDate($gpx, $now);
$found = strlen($foundString) > 0;

$imgFound = "<img src=\"img/found.png\">";
$imgNotFound = "<img src=\"img/notfound.png\">";

echo "Today ";
if ($found) {
	echo $imgFound;
	echo " (You've already found" . $foundString . ".)";
} else {
	echo $imgNotFound;
	echo " Go find a cache!";
}

echo "<br/><br/>";

echo "Tomorrow " . (strlen(cachesFoundOnDate($gpx, $tomorrow)) > 0 ? $imgFound : $imgNotFound);
echo "<br/>";
echo $day2->format("l") . " " . (strlen(cachesFoundOnDate($gpx, $day2)) > 0 ? $imgFound : $imgNotFound);
echo "<br/>";
echo $day3->format("l") . " " . (strlen(cachesFoundOnDate($gpx, $day3)) > 0 ? $imgFound : $imgNotFound);

}

?>
</body>
</html>
