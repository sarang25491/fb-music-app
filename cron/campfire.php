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
	fwrite($file,'<?php success(\'Support Chat Room Open: <a href="http://burst.campfirenow.com/' . $room_data['room']['active-token-value']['value'] . '">http://burst.campfirenow.com/' . $room_data['room']['active-token-value']['value'] . '</a>\',\'Looks like there is someone available to help! Feel free to drop by if you need any help!\'); ?>');
} else {
	fwrite($file,'<?php error(\'Support Chat Room Closed\',\'Sorry about this, but no one is around to help out at the moment. Please try the other methods below.\'); ?>');
}

fclose($file);

?>