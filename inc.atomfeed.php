<?php
include "include/class.myatomparser.php";

# where is the feed located?
$url = "https://redmine.burst-dev.com/projects/music/activity.atom?key=32JyPNGS0OoFxERO3mVokjL1b1m7OwqSj0s8Im47";

# create object to hold data and display output
$atom_parser = new myAtomParser($url);

$output = $atom_parser->getRawOutput();	# returns string containing HTML

echo '<div style="padding: 10px; margin-left: 10px; margin-right: 10px; border: 1px solid #d4dae8;">';

foreach ($output['FEED']['ENTRY'] as $entry) {
	list($date,$time) = explode('T', $entry['UPDATED']);
	list($year, $month, $day) = explode('-', $date);
	list($time, $offset) = explode('-', $time);
	list($hour, $minute, $second) = explode(':', $time);

	echo '[' . $month . '.' . $day . '.' . $year . '-' . $hour . ':' . $minute . ':' . $second . ' EST] ' . $entry['TITLE'] . '';
	echo '<br/>';
}

echo '</div>';
?>