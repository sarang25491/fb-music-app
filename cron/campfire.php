<?php

$ch = curl_init('http://burst.campfirenow.com/room/283137.xml');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERPWD, 'd14b8548045c46d5c8f4742f9452feb1d0a0a357:s65oonx');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($ch);
curl_close($ch);

include '../include/class.xml2array.php';
$room_data = xml2array($result);

print_r($room_data);

$file = fopen('../statics/campfire.txt','w');
if ($room_data['room']['open-to-guests']['value'] == 'true') {
	fwrite($file,'<?php success(\'Support Chat Room Open: <a href="http://burst.campfirenow.com/' . $room_data['room']['active-token-value']['value'] . '">http://burst.campfirenow.com/' . $room_data['room']['active-token-value']['value'] . '</a>\',\'Looks like there is someone available to help! Feel free to drop by, leave a message on the <a href="http://www.facebook.com/board.php?uid=2436915755">boards</a>, or message the developer <a href="http://www.facebook.com/?compose=1&id=1340490250&sk=messages">directly</a>.\'); ?>');
} else {
	fwrite($file,'<?php error(\'Support Chat Room Closed\',\'Sorry about that, but no one is around... Please leave a message on the <a href="http://www.facebook.com/board.php?uid=2436915755">boards</a> (recommended), or message the developer <a href="http://www.facebook.com/?compose=1&id=1340490250&sk=messages">directly</a>.\'); ?>');
}

fclose($file);

?>