<?php
$pre = 'skip_fbapi';
include '../include/config.php';

$xid = $_GET['xid'];
$dbData = $db->Raw("SELECT `link`,`type` FROM `userdb_uploads` WHERE `xid`='$xid'");

$type = $dbData[0]['type'];
if ($type == 'upload')
{
	$linkSplit = split("/", $dbData[0]['link']);
	$drive = $linkSplit[4];
	$userFolder = $linkSplit[5];
	$fileName = $linkSplit[6];
	$t_hex = sprintf("%08x", time());
}

if ($type == 'upload') {
	$link_type = 1;
	echo "linkType=" . $link_type . "&t_hex=" . $t_hex . "&drive=" . $drive . "&userFolder=" . $userFolder . "&filename=" . $fileName . "";
} else {
	$link_type = 2;
	echo "linkType=" . $link_type . "&link=" . $dbData[0]['link'] . "";
}

?>