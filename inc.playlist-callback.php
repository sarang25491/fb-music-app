<?php include_once 'include/facebook/facebook.php'; ?>
<?php $pre = 'skip_login'; include 'include/config.php'; ?>

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
			if ($deleteData[0]['type'] == 'upload') { 
				$server = $deleteData[0]['server'];
				$serverData = $db->Raw("SELECT `internal_uri` FROM `servers` WHERE `name`='$server'");
				$userFolder = array_sum(str_split($_GET['id']));
				
				if(file_exists('' . $serverData[0]['internal_uri'] . 'users/' . $deleteData[0]['drive'] . '/' . $userFolder . '/' . baseName($deleteData[0]['link']) . ''))
					unlink('' . $serverData[0]['internal_uri'] . 'users/' . $deleteData[0]['drive'] . '/' . $userFolder . '/' . baseName($deleteData[0]['link']) . '');
			}

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

if (isset($_GET['grabApiKey'])) {
	$md5 = md5($_GET['id']);
	echo '<b>API HASH KEY</b>: ' . $_GET['id'] . '-' . $md5 . '';
}

if (isset($_GET['grabInfo'])) {
	$songData = $db->Raw("SELECT `type`,`count` FROM `userdb_uploads` WHERE `xid`='$_GET[id]'");
	// types: link, upload
	if ($songData[0]['type'] == 'link')
		echo 'Song was <b>given</b>';
	else if ($songData[0]['type'] == 'upload')
		echo 'Song was <b>uploaded</b>';
		
	echo ' and was played <b>' . $songData[0]['count'] . '</b> times.';
}

if (isset($_GET['grabPlayerUrl'])) {
	$encodedId = base64_encode("" . $_GET['id'] . "-" . time() . "");
	echo '<a style="font-size: 10pt;" target="_parent" href="' . $config['fb']['appcallbackurl'] . 'playlist.php?id=' . $encodedId . '">' . $config['fb']['appcallbackurl'] . 'playlist.php?id=' . $encodedId . '</a>'; 
}
?>