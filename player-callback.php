<?php
if (strpos($_SERVER['HTTP_REFERER'],'http://music.burst-dev.com') === FALSE) die();

$pre = 'skip_fbapi';
include 'include/config.php';
include 'include/aws/sdk.class.php';

$xid = $_GET['xid'];

if (isset($_GET['load']))
{
   $dbData = $db->Raw("SELECT `user`,`link`,`type`,`server` FROM `userdb_uploads` WHERE `xid`='$xid'");

   $type = $dbData[0]['type'];
   $server = $dbData[0]['server'];
   if ($type == 'upload' && $server != 's3')
   {
	   $linkSplit = split("/", $dbData[0]['link']);
	   $drive = $linkSplit[4];
	   $userFolder = $linkSplit[5];
	   $fileName = $linkSplit[6];
	   $t_hex = sprintf("%08x", time());
   }

   $db->logActivity($dbData[0]['user'], 'logStart', $xid);

   $link = $dbData[0]['link'];

   if ($server == 's3')
   {
      $s3 = new AmazonS3();
      $link = $s3->get_object_url('fb-music', $link, '10 seconds');
   }

   $link = urlencode($link);

   if ($type == 'upload' && $server !='s3') {
	   $link_type = 1;
	   echo "linkType=" . $link_type . "&t_hex=" . $t_hex . "&drive=" . $drive . "&userFolder=" . $userFolder . "&filename=" . $fileName . "";
   } else {
   	$link_type = 2;
	   echo "linkType=" . $link_type . "&link=" . $link . "";
   }
}
else if (isset($_GET['logFullPlay']))
{
   $dbData = $db->Raw("SELECT `user` FROM `userdb_uploads` WHERE `xid`='$xid'");
   $db->logActivity($dbData[0]['user'], 'logFullPlay', $xid);
}

?>
