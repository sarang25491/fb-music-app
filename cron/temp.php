<?php
$pre = 'skip_fbapi';
include '../include/config.php';
?>

<?php exec('find ' . $config['server']['internal_url'] . 'users/temp/ -mtime +0.42 -exec rm {} \;'); // will delete anything older than an hour ?>

<?php
$currentTempDb = $db->Raw("SELECT `location` FROM `userdb_temporary`");
foreach ($currentTempDb as $file) {
	if (!file_exists($file['location']))
		$db->Raw("DELETE FROM `userdb_temporary` WHERE `location`='$file[location]'");
}
?>