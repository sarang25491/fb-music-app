<?php include_once 'include/facebook/facebook.php'; ?>
<?php $pre = 'skip_login'; include 'include/config.php'; ?>

<?php
if (isset($_GET['updateList'])) {
	$origPlaylist = $db->Raw("SELECT `xid` FROM `userdb_uploads` WHERE `user`='$_GET[id]'");
	foreach ($origPlaylist as $origSong) {
		if (!in_array($origSong['xid'], $_POST['playlist'])) {
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
		
	foreach ($_POST['playlist'] as $key => $song) {
		$db->Raw("UPDATE `userdb_uploads` SET `order`='$key' WHERE `xid`='$song'");
	}
	
	$user = $_GET['id'];
	include 'fb.profile.php';
	echo "<b>Playlist saved! -- Updated to Profile!<b>";
}

if (isset($_GET['grabSongData'])) {
	$songData = $db->Raw("SELECT `title`,`artist` FROM `userdb_uploads` WHERE `xid`='$_GET[id]'");
	echo '<b>Now Playing</b>: ' . $songData[0]['title'] . ' by ' . $songData[0]['artist'] . '';
}

if (isset($_GET['grabApiKey'])) {
	$md5 = md5($_GET['id']);
	echo '<b>API HASH KEY</b>: ' . $_GET['id'] . '-' . $md5 . '';
}

if (isset($_GET['grabInfo'])) {
	$songData = $db->Raw("SELECT `type`,`count` FROM `userdb_uploads` WHERE `xid`='$_GET[id]'");
	echo '<b>Type</b>: ' . $songData[0]['type'] . ' | <b>Plays</b>: ' . $songData[0]['count'] . '';
}
?>