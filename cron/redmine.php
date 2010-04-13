<?php
include "../include/class.myatomparser.php";

$file = fopen('../statics/redmine.txt','w');
# where is the feed located?
$url = "https://redmine.burst-dev.com/projects/music/activity.atom?key=32JyPNGS0OoFxERO3mVokjL1b1m7OwqSj0s8Im47";

# create object to hold data and display output
$atom_parser = new myAtomParser($url);

$output = $atom_parser->getRawOutput();	# returns string containing HTML

fwrite($file,'<b>Development Changeset Timeline</b><br />');

foreach ($output['FEED']['ENTRY'] as $entry) {
	list($date,$time) = explode('T', $entry['UPDATED']);
	list($year, $month, $day) = explode('-', $date);
	list($time, $offset) = explode('-', $time);
	list($hour, $minute, $second) = explode(':', $time);

	fwrite($file,'[<fb:time t="' . mktime($hour,$minute,$second,$month,$day,$year,-5) . '" />] ' . $entry['TITLE'] . '<br />');
}

fclose($file);
?>