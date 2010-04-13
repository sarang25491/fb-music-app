<?php
$pre = 'skip_login';
include '../include/facebook/facebook.php';
include '../include/config.php';

// Checks the users of the upload database if they are an application user.
// It will remove the user if they are not.
if($argv[1] == 'fb') {
	$users = $db->Raw("SELECT COUNT(*) AS `Rows`, `user` FROM `userdb_uploads` WHERE `type`='upload' GROUP BY `user` ORDER BY `user`");
	foreach($users as $user)
	{
		$userid = $user['user'];
		$isappuser = $facebook->api_client->users_isAppUser($userid);
		if ($isappuser == 0) {
			$isappuser = $facebook->api_client->pages_isAppAdded($userid);
		}
		echo '' . $userid . ': ' . $isappuser . '';
		if ($isappuser == 0) {
			echo ' - removing';

			$db->Raw("DELETE FROM `userdb_uploads` WHERE `user`='$userid'");
			$db->Raw("DELETE FROM `userdb_links` WHERE `user`='$userid'");
		}
		echo "\n";
	}

	// Will clear out the cached images on Facebook's side.
	$facebook->api_client->fbml_refreshImgSrc("" . $config['fb']['appcallbackurl'] . "images/spinner.gif");
} elseif ($argv[1] == 'server') {
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

	// Compares the files to the database to make sure that a database entry for the file exists.
	// If there is no database entry, the file will be deleted.
	$dir = dirList('' . $config['server']['internal_url'] . 'users/');
	foreach ($dir as $currDir) {
		$subDir = dirList('' . $config['server']['internal_url'] . 'users/' . $currDir . '');
		foreach ($subDir as $currSubDir) {
			$files = dirList('' . $config['server']['internal_url'] . 'users/' . $currDir . '/' . $currSubDir . '/');
			foreach ($files as $file) {
				echo $file;
				$link_to_check = '' . $config['server']['streaming'] . '/stream/' . $currDir . '/' . $currSubDir . '/' . $file . '';
				$existor = $db->Raw("SELECT COUNT(*) FROM `userdb_uploads` WHERE `link`='$link_to_check'");

				if ($existor[0]['COUNT(*)'] == 0) {
					echo ' - removing';
					unlink('' . $config['server']['internal_url'] . 'users/' . $currDir . '/' . $currSubDir . '/' . $file . '');
				}
				echo "\n";
			}
		}
	}

	// If there is no file matching the database entry, the database entry will be deleted.
	$database_entries = $db->Raw("SELECT `user`,`drive`,`link` FROM `userdb_uploads` WHERE `type`='upload'");
	foreach ($database_entries as $database_entry) {
		echo $database_entry['link'];
		
		$userFolder = array_sum(str_split($database_entry['user']));
		if(!file_exists('' . $config['server']['internal_url'] . 'users/' . $database_entry['drive'] . '/' . $userFolder . '/' . basename($database_entry['link']) . '')) {
			echo ' - removing';
			$db->Raw("DELETE FROM `userdb_uploads` WHERE `link`='$database_entry[link]'");
		}
		
		echo "\n";
	}

} else if ($argv[1] == 'accounts') {
	$ex_day = date('Y-m-d', strtotime('-90 days'));
	$ex_acc = $db->Raw("SELECT `user` FROM `userdb_users` WHERE date(`time`) < '$ex_day' AND `pro`='0' AND `credit`='0' AND `override`='0'");
	
	foreach ($ex_acc as $curr_user) {
		echo $curr_user[user];
		$if_page = $db->Raw("SELECT `owner` FROM `pages` WHERE `fb_page_id`='$curr_user[user]'");
		if (count($if_page) == 1) {
			$if_owner = $db->Raw("SELECT COUNT(*) FROM `userdb_users` WHERE `user`='$if_page[0][owner]' AND `pro`='0' AND `credit`='0' AND `override`='0'");
		}
		
		if (count($if_page) == 0 OR $if_owner[0]['COUNT'] == 0) {
			echo " - removing";
			$del_queue = $db->Raw("SELECT `link` FROM `userdb_uploads` WHERE `user`='$curr_user[user]'");
			foreach ($del_queue as $del_curr) {
				$f = explode("/",$del_curr['link']); // array 5 selects current drive
				unlink('' . $config['server']['internal_url'] . 'users/' . $f[5] . '/' . basename($del_curr['link']). '');
			}
		}
		
		echo "\n";	
	}
}
?>
