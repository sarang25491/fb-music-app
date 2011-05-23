<?php
$pre = 'skip_login';
include '../include/facebook/facebook.php';
include '../include/config.php';
include '../include/aws/sdk.class.php';


function my_array_diff($a, $b) {
    $map = $out = array();
    foreach($a as $val) $map[$val] = 1;
    foreach($b as $val) if(isset($map[$val])) $map[$val] = 0;
    foreach($map as $val => $ok) if($ok) $out[] = $val;
    return $out;
}

function get_xids($db)
{
   $one = date("Y-m-d", strtotime("-1 month")); 
   $two = date("Y-m-d", strtotime("-2 months"));
   $three = date("Y-m-d", strtotime("-3 months"));

   // getting songs that have been
   //    - uploaded more than a month ago
   //    - user has not visited the app for more than a month
   $old_songs_db = $db->Raw("SELECT xid FROM userdb_uploads WHERE type='upload' AND time <= '$one' AND user IN (SELECT user from userdb_users WHERE time <= '$one')");
   
   // getting all song ids that have been:
   //    - played within the recent month
   $recently_played_db = $db->Raw("SELECT DISTINCT data FROM userdb_activity WHERE time > '$one'");

   // getting songs that have been:
   //    - uploaded by a page
   //    - older than a month
   //    - owner of that page has not visited in two months
   $old_pages_songs_db = $db->Raw("SELECT xid FROM userdb_uploads WHERE type='upload' AND time <= '$one' AND user IN (SELECT fb_page_id FROM pages WHERE owner IN (SELECT user FROM userdb_users WHERE time <= '$two'))");

   // getting songs that have:
   //    - no reference to a user
   //    - no reference to a page
   $unassociated_songs_db = $db->Raw("SELECT xid FROM userdb_uploads WHERE user NOT IN (SELECT user FROM userdb_users) AND user NOT IN (SELECT fb_page_id FROM pages) AND type='upload'");

   $old_songs = $recently_played = array();
   foreach($old_songs_db as $song)
      $old_songs[] = $song['xid'];

   foreach($old_pages_songs_db as $song)
      $old_songs[] = $song['xid'];

   foreach($unassociated_songs_db as $song)
      $old_songs[] = $song['xid'];

   foreach ($recently_played_db as $play)
      $recently_played[] = $play['data'];

   return my_array_diff($old_songs, $recently_played);

//   return $db->Raw("SELECT DISTINCT data FROM userdb_activity WHERE time <= '$one' AND data IN (SELECT xid FROM userdb_uploads WHERE type='upload' AND time <= '$one' AND user NOT IN (SELECT user from userdb_users WHERE time >= '$one')) AND data NOT IN (SELECT DISTINCT data FROM userdb_activity WHERE time > '$one') LIMIT 10");
}

function delete_xid($db, $xid)
{
   $data = $db->Raw("SELECT server, link FROM userdb_uploads WHERE xid='$xid'");
   $server = $data[0]['server'];
   $link = $data[0]['link'];

   if ($server == 's3')
   {
      $s3 = new AmazonS3();
      $s3->delete_object('fb-music', $link);
   }
   else
   {
      $split = split("/", $data[0]['link']);
      $link = "/var/www/music/users/" . $split[4] . "/" . $split[5] . "/" . $split[6];
      unlink($link);
   }

   $db->Raw("DELETE FROM userdb_uploads WHERE xid='$xid'");
}

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
	$data = get_xids($db); 

   while(count($data) != 0)
   {
      foreach ($data as $xid)
         delete_xid($db, $xid);
   
      $data = get_xids($db);
   }
   //$ex_day = date('Y-m-d', strtotime('-60 days'));
	//$ex_acc = $db->Raw("DELETE FROM `userdb_uploads` WHERE `user` IN (SELECT `user` FROM `userdb_users` WHERE `time` <= '$ex_day') AND `type`='upload' AND `time` <= '$ex_day'");
	// THIS JUST DELETES DB ENTRIES, YOU NEED TO RUN MAINTENANCE TO COMPLETE THE CLEANUP OF DEAD ACCOUNTS
}

?>
