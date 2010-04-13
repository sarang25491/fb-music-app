<?php
$pre = 'skip_login';
include '../include/facebook/facebook.php';
include '../include/config.php';

function dirList ($directory) 
{
	$results = array();
	$handler = opendir($directory);
	while ($file = readdir($handler)) {
		if ($file != '.' && $file != '..')
			$results[] = $file;
	}
	closedir($handler);
	return $results;
}

$dir = dirList('' . $config['server']['internal_url'] . 'users/');
$dirUsed = 0;
$smallDir = 100;
foreach ($dir as $currDir) {
	if(is_numeric($currDir)) {
		$disk_total = disk_total_space("" . $config['server']['internal_url'] . "users/" . $currDir . "/");
		$disk_free = disk_free_space("" . $config['server']['internal_url'] . "users/" . $currDir . "/");

		$currDisk = round(100-(($disk_free/$disk_total)*100), 1);
		if ($currDisk < $smallDir) {
			$dirUsed = $currDir;
			$smallDir = $currDisk;
		}
	}
}

echo $dirUsed;
$db->Raw("UPDATE `system` SET `data`='$dirUsed[0]' WHERE `var`='drive'");
?>