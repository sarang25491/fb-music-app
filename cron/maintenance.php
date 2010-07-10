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
	
	ini_set("memory_limit", "1024M");
	
	$fsArray = array();
	$dir = scandir('' . $config['server']['internal_url'] . 'users/');

	unset($dir[0]);
	unset($dir[1]);
	array_pop($dir);
	
	echo "Generating file server array... ";
	
	foreach ($dir as $drive)
	{
		$userFolders = scandir('' . $config['server']['internal_url'] . 'users/' . $drive . '');
		unset($userFolders[0]);
		unset($userFolders[1]);
		foreach ($userFolders as $userFolder)
		{
			$files = scandir('' . $config['server']['internal_url'] . 'users/' . $drive . '/' . $userFolder . '');
			unset($files[0]);
			unset($files[1]);
			foreach ($files as $file)
			{
				array_push($fsArray, '' . $drive . '/' . $userFolder . '/' . $file . '');
			}
		}
	}
	
	echo "" . count($fsArray) . " elements!\n";
	
	$dbArray = array();
	
	echo "Generating database array... ";
	$dbQuery = $db->Raw("SELECT `link` FROM `userdb_uploads` WHERE `type`='upload'");
	foreach ($dbQuery as $link)
	{
		$split = split ("/", $link['link']);
		array_push($dbArray, '' . $split[4] . '/' . $split[5] . '/' . $split[6] . '');
	}
	
	echo "" . count($dbArray) . " elements!\n";
	
	function my_array_diff($a, $b) {
	    $map = $out = array();
	    foreach($a as $val) $map[$val] = 1;
	    foreach($b as $val) if(isset($map[$val])) $map[$val] = 0;
	    foreach($map as $val => $ok) if($ok) $out[] = $val;
	    return $out;
	}
	
	echo "Comparing the two arrays for files that do not have an entry in the database... ";
	$filesToDelete = my_array_diff($fsArray, $dbArray);
	
	echo "" . count($filesToDelete) . " files found!\n";
	
	echo "We are now deleting these files... ";
	
	foreach ($filesToDelete as $delete)
	{
		unlink('' . $config['server']['internal_url'] . 'users/' . $delete . '');
	}
	
	echo "done!\n";

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
