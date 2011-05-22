<?php include_once 'include/facebook/facebook.php'; ?>
<?php $pre = 'skip_login'; include 'include/config.php'; ?>
<?php include 'include/aws/sdk.class.php'; ?>

<?php
if (isset($_GET['updateList'])) {
	$origPlaylist = $db->Raw("SELECT `xid` FROM `userdb_uploads` WHERE `user`='$_GET[uid]'");
   
	if (count($_POST['playlist']) == 0)
		$newList = array();
	else
		$newList = $_POST['playlist'];
	
	foreach ($origPlaylist as $origSong) {
		if (!in_array($origSong['xid'], $newList)) {
			$id = $origSong['xid'];
         $deleteData = $db->Raw("SELECT `type`,`link`,`server`,`drive` FROM `userdb_uploads` WHERE `id`='$id' LIMIT 1");
			$server = $deleteData[0]['server'];
			if ($deleteData[0]['type'] == 'upload') { 
				if ($server == 's3')
            {
               $s3 = new AmazonS3();
               $s3->delete_object('fb-music', $deleteData[0]['link']);
            }
            else
            {               
               $serverData = $db->Raw("SELECT `internal_uri` FROM `servers` WHERE `name`='$server'");
				   $userFolder = array_sum(str_split($_GET['id']));
				
				   if(file_exists('' . $serverData[0]['internal_uri'] . 'users/' . $deleteData[0]['drive'] . '/' . $userFolder . '/' . baseName($deleteData[0]['link']) . ''))
					   unlink('' . $serverData[0]['internal_uri'] . 'users/' . $deleteData[0]['drive'] . '/' . $userFolder . '/' . baseName($deleteData[0]['link']) . '');
			   }
         }

//         $db->Raw("INSERT INTO `delete_queue` (`xid`,`file`) VALUES ('$id','$deleteData[0][link]')");
			$db->Raw("DELETE FROM `userdb_uploads` WHERE `id`='$id'"); 
		}
	}
	
	// Update the playlist order as long as there are still songs that exist in the playlist.
	if (count($_POST['playlist']) !== 0) {	
		foreach ($_POST['playlist'] as $key => $song) {
			$db->Raw("UPDATE `userdb_uploads` SET `order`='$key' WHERE `xid`='$song'");
		}
	}
	
	echo "<b>Playlist successfully saved!<b>";
}

if (isset($_GET['grabSongData'])) {
	$songData = $db->Raw("SELECT `title`,`artist` FROM `userdb_uploads` WHERE `xid`='$_GET[id]'");
	echo '<b>Now Playing</b>: ' . htmlspecialchars_decode(utf8_decode($songData[0]['title']), ENT_QUOTES) . ' by ' . htmlspecialchars_decode(utf8_decode($songData[0]['artist']), ENT_QUOTES) . '';
}
?>
