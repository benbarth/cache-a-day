<?php

$timezoneToronto = new DateTimeZone("America/Toronto");

$gpxFile = "gpx/mrbarth.gpx";

$gpx = simplexml_load_file($gpxFile);

echo "<h1>cache-a-day</h1>";

$now = new DateTime('NOW');
$now->setTimezone($timezoneToronto);
$nowShortString = $now->format("m-d");

echo "Today is " . $nowShortString . ".<br/><br/>";

$numberOfLogs = 0;
$found = false;
$foundString = "";
foreach($gpx->wpt as $cache) {
	$logs = $cache->children('groundspeak', true)->cache->children('groundspeak', true)->logs;
	if ($logs) {
		foreach($logs as $log) {
			$numberOfLogs += 1;
			$logDateData = $log->children('groundspeak', true)->log->children('groundspeak', true)->date;
			$logDate = DateTime::createFromFormat(DateTime::ATOM, $logDateData);
			$logDate->setTimezone($timezoneToronto);
			$logDateString = $logDate->format("m-d");
			if ($logDateString == $nowShortString) {
				$found = true;
				$foundString = $foundString . " <a href=\"" . $cache->url . "\">" . $cache->name . "</a>";
			}
		}
	}
}

if (!$found) {
	echo "<img src=\"https://geocaching.com/images/logtypes/3.png\"> ";
	echo('Go find one!');
} else {
	echo "<img src=\"https://geocaching.com/images/logtypes/2.png\"> ";
	echo "You've already found" . $foundString;
}


echo "<br/><br/>";
echo "Total: " . $numberOfLogs;

?>
