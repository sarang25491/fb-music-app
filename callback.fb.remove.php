<?php
$pre = 'skip_fbapi'; // do not load facebook config values
include 'include/config.php';

$user = $_POST['fb_sig_user']; // user value recieved from facebook
$uploads = $db->Raw("SELECT `link` FROM `userdb_uploads` WHERE `user`='$user' AND `type`='upload'");

$deleteData = $db->Raw("SELECT `type`,`link`,`server`,`drive` FROM `userdb_uploads` WHERE `user`='$user' AND `type`='upload'");
foreach ($deleteData as $deleteQueue) {
	if ($deleteQueue['type'] == 'upload') { 
	$serverData = $db->Raw("SELECT `internal_uri` FROM `servers` WHERE `name`='$deleteQueue[server]'");
	$userFolder = array_sum(str_split($user));

	if(file_exists('' . $serverData[0]['internal_uri'] . 'users/' . $deleteData['drive'] . '/' . $userFolder . '/' . baseName($deleteData['link']) . ''))
		unlink('' . $serverData[0]['internal_uri'] . 'users/' . $deleteData['drive'] . '/' . $userFolder . '/' . baseName($deleteData['link']) . '');
	}
}

// deleting all entries from database
$db->Raw("DELETE FROM `userdb_uploads` WHERE `user`='$user'");
?>