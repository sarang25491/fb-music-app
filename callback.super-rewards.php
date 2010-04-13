<?php
$pre = 'skip_fbapi';
include 'include/config.php';

// $secret = '4994a15d0562dc31ae2901c7f61a747a';

if ($_GET['uid'] == 0) die();
if ($_GET['total'] == 0) die();

// if ($sig == md5($id . ':' . $new . ':' . $uid . ':' . $secret) {
	$check_ifexists = $db->Raw("SELECT COUNT(*) FROM `userdb_users` WHERE `user`='$uid'");
	if ($check_ifexists[0]['COUNT(*)'] == 0) 
		$db->Raw("INSERT INTO `userdb_users` (`user`,`credit`,`override`) VALUES ('$uid','$total','0')");
	else 
		$db->Raw("UPDATE `userdb_users` SET `credit`='$total' WHERE `user`='$uid'");
	// if ($uid == '1340490250') echo '1';
// } else {
//	echo '0';
// }

echo '1';
?>
