<?php
$pre = 'skip_fbapi';
include '../include/config.php';
?>

<?php
// $top10_count = $db->Raw("SELECT `user` FROM `userdb_stats` ORDER BY `todays_count` DESC LIMIT 5");
// $top10_activity = $db->Raw("SELECT `user` FROM `userdb_stats` ORDER BY `todays_activity` DESC LIMIT 5");
// $awardies = array_merge($top10_count, $top10_activity);
// foreach($awardies as $awardie) {
//	$db->Raw("INSERT INTO `userdb_transactions` (`user`,`credit`) VALUES ('$awardie','1')");
// }
$db->Raw("UPDATE `userdb_stats` SET `todays_count`='0',`todays_activity`='0'");
$db->Raw("DELETE FROM `userdb_links` WHERE `link`=''");
?>