<?php
$pre = 'skip_fbapi';
include 'include/config.php';

$secret_key = '4994a15d0562dc31ae2901c7f61a747a';

if ($_GET['uid'] == 0) die();
if ($_GET['total'] == 0) die();

if ($_GET['sig'] == md5($_GET['id'] . ':' . $_GET['new'] . ':' . $_GET['uid'] . ':' . $secret_key) {
	// sjlu: 12-19-2010 - new implementation of third_party_id is needed
   if (isset($_GET['custom_fb3phashmode']))
   {
      $db->Raw("UPDATE `userdb_users` SET `credit_new`='$_GET[total]' WHERE `third_party_id`='$_GET[uid]'");
   }
   else
   {
      $check_ifexists = $db->Raw("SELECT COUNT(*) FROM `userdb_users` WHERE `user`='$_GET[uid]'");
	   if ($check_ifexists[0]['COUNT(*)'] == 0) 
		   $db->Raw("INSERT INTO `userdb_users` (`user`,`credit`,`override`) VALUES ('$_GET[uid]','$_GET[total]','0')");
	   else 
		   $db->Raw("UPDATE `userdb_users` SET `credit`='$_GET[total]' WHERE `user`='$_GET[uid]'");
   }
   echo '1';
} else {
   echo '0';
}

?>
