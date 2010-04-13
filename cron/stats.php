<?php
$pre = 'skip_fbapi';
include '../include/config.php';
?>

<?php
$queue = $db->Raw("SELECT * FROM `userdb_plays` WHERE `flag`='1'");
$db->Raw("UPDATE `userdb_plays` SET `flag`='0' WHERE `flag`='1'");
foreach($queue as $queue) {
	$user = $queue['player'];
	if ($user !== '0' AND $user !== NULL) { 
		$if_exists = $db->Raw("SELECT COUNT(*) FROM `userdb_stats` WHERE `user`='$user'");
		if ($if_exists[0]['COUNT(*)'] == '0') {
			$db->Raw("INSERT INTO `userdb_stats` (`user`,`todays_count`,`all_time_count`) VALUES ('$user','1','1');"); 
		} else {
			$db->Raw("UPDATE `userdb_stats` SET `todays_count`=`todays_count`+1,`all_time_count`=`all_time_count`+1 WHERE `user`='$user'");
		}
	}
	
	$user = $queue['owner'];
	if ($user !== '0' AND $user !== NULL) { 
		$if_exists = $db->Raw("SELECT COUNT(*) FROM `userdb_stats` WHERE `user`='$user'");
		if ($if_exists[0]['COUNT(*)'] == '0') {
			$db->Raw("INSERT INTO `userdb_stats` (`user`,`todays_activity`,`all_time_activity`) VALUES ('$user','1','1');"); 
		} else {
			$db->Raw("UPDATE `userdb_stats` SET `todays_activity`=`todays_activity`+1,`all_time_activity`=`all_time_activity`+1 WHERE `user`='$user'");
		}
	}
}
?>