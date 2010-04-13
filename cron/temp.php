<?php
$pre = 'skip_fbapi';
include '../include/config.php';
?>

<?php
/* 
PART 1: CLEAR EXPIRED DATABASE ENTRIES
This will first select all values inside the database
that are not within the 15 minute time frame
| Current Time | -- Files within 15 minute frame -- | Current Time minus 15 | -- EXPIRED FILES -- |
Any file in the Expired Files frame will be deleted 
from both the file system and the database
*/

$minutes_to_subtract = 60;
$expiration_time = date('Y-m-d H:i:s', strtotime('-'.$minutes_to_subtract.' min'));
/* Set CRON JOB to every 5 minutes to remove temporary files that have expired over 15 minutes */
$data_to_delete = $db->Raw("SELECT `user`,`location` FROM `userdb_temporary` WHERE `time` < '$expiration_time'");

foreach($data_to_delete as $delete_queue) {
	unlink($delete_queue['location']);
	$db->Raw("DELETE FROM `userdb_temporary` WHERE `user`='$delete_queue[user]'");
}

/*
PART 2: CHECK FILE SYSTEM EXPIRATION
This will list all files in directory
Check if the file exists in the database
If it doesn't exist, remove the file
*/

// Directory listing function.
function dirList ($directory) 
{

    // create an array to hold directory list
    $results = array();

    // create a handler for the directory
    $handler = opendir($directory);

    // keep going until all files in directory have been read
    while ($file = readdir($handler)) {

        // if $file isn't this directory or its parent, 
        // add it to the results array
        if ($file != '.' && $file != '..')
            $results[] = $file;
    }

    // tidy up: close the handler
    closedir($handler);

    // done!
    return $results;

}

$files = dirList('' . $config['server']['internal_url'] . 'users/temp/');
foreach ($files as $file) {
	$link_to_check = '' . $config['fb']['internal_url'] . 'users/temp/' . $file . '';
	$existor = $db->Raw("SELECT COUNT(*) FROM `userdb_temporary` WHERE `location`='$link_to_check'");

	if ($existor[0]['COUNT(*)'] == 0)
		unlink('' . $config['server']['internal_url'] . 'users/temp/' . $file . '');
}
?>