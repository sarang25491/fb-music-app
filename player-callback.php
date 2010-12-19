<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();

$pre = 'skip_fbapi';
include 'include/config.php';

$xid = $_GET['xid'];

if (isset($_GET['load']))
{
   $dbData = $db->Raw("SELECT `user`,`link`,`type` FROM `userdb_uploads` WHERE `xid`='$xid'");

   $type = $dbData[0]['type'];
   if ($type == 'upload')
   {
	   $linkSplit = split("/", $dbData[0]['link']);
	   $drive = $linkSplit[4];
	   $userFolder = $linkSplit[5];
	   $fileName = $linkSplit[6];
	   $t_hex = sprintf("%08x", time());
   }

   $db->logActivity($dbData[0]['user'], 'logStart', $xid);

   if ($type == 'upload') {
	   $link_type = 1;
	   echo "linkType=" . $link_type . "&t_hex=" . $t_hex . "&drive=" . $drive . "&userFolder=" . $userFolder . "&filename=" . $fileName . "";
   } else {
   	$link_type = 2;
	   echo "linkType=" . $link_type . "&link=" . $dbData[0]['link'] . "";
   }
}
else if (isset($_GET['logFullPlay']))
{
   $dbData = $db->Raw("SELECT `user` FROM `userdb_uploads` WHERE `xid`='$xid'");
   $db->logActivity($dbData[0]['user'], 'logFullPlay', $xid);
}

?>
