<?php

$pre = 'skip_login';
include_once 'include/facebook/facebook.php';
include_once 'include/config.php';

$db_data = $db->Raw("SELECT `user`,`type`,`link`,`dl` FROM `userdb_uploads` WHERE `id`='$_GET[id]';");

if ($db_data[0]['dl'] == 0)
	die('404 - Not Found');

if ($db_data[0]['type'] == 'upload') {

	$uploadData = $db->Raw("SELECT `user`,`server`,`drive` FROM `userdb_uploads` WHERE `id`='$id'");
	$server = $uploadData[0]['server'];
	$serverData = $db->Raw("SELECT `stream_url`,`stream_secret` FROM `servers` WHERE `name`='$server'");
	
	$userFolder = array_sum(str_split($uploadData[0]['user']));
	
	$f = "/" . $uploadData[0]['drive'] . "/" . $userFolder . "/" . basename($db_data[0]['link']) . "";

	$t_hex = sprintf("%08x", time());
	$m = md5($serverData[0]['stream_secret'].$f.$t_hex);

	$link = "" . $serverData[0]['stream_url'] . "/stream/" . $m . "/" . $t_hex . "" . $f . "";
	
} else {
	
	$link = $db_data[0]['link'];
	
}

$facebook->redirect($link);

?>