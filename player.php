<?php
$pre = 'skip_fbapi';
include_once 'include/config.php';
include_once 'include/functions.php';
?>

<?php 
// Due to the database transition, this converts all types into one ID for older versions of the player.
if(!isset($_GET['id'])) {
	if(isset($_GET['link']))
		$id = $_GET['link'];
	elseif (isset($_GET['upload']))
		$id = $_GET['upload'];
	elseif (isset($_GET['from_feed']))
		$id = $_GET['from_feed'];
	elseif (isset($_GET['xmlData']))
		$id = $_GET['xmlData'];
}
?>

<?php

if (isset($_GET['xmlData']) || isset($_GET['from_feed'])) {

	$decodedInput = base64_decode($id);
	list($id, $time) = split("-", $decodedInput, 2);

	$db_data = $db->Raw("SELECT `xid`,`user`,`type`,`link`,`title`,`artist`,`playtime` FROM `userdb_uploads` WHERE `xid`='$id' OR `id`='$id';");
	$id = $db_data[0]['xid'];
	$type = $db_data[0]['type'];
	
	if($type == 'link') {
		$link = htmlspecialchars_decode($db_data[0]['link'], ENT_QUOTES);
		$duration = 0;
		$urlParse = parse_url($link);
		if (str_replace('www.','',$urlParse['host']) == 'youtube.com') {
			$provider = 'youtube';
		} else {
			if (in_array(strtolower(substr($link, strrpos($link, '.') + 1)), array('m4a','mp4','aac','flv')))
				$provider = 'video';
			else
				$provider = 'sound';
		}
	} elseif ($type == 'upload') {
		$uploadData = $db->Raw("SELECT `user`,`server`,`drive` FROM `userdb_uploads` WHERE `id`='$id' OR `xid`='$id';");
		$server = $uploadData[0]['server'];
		$serverData = $db->Raw("SELECT `stream_url`,`stream_secret` FROM `servers` WHERE `name`='$server'");
		
		$userFolder = array_sum(str_split($uploadData[0]['user']));
		
		$f = "/" . $uploadData[0]['drive'] . "/" . $userFolder . "/" . basename($db_data[0]['link']) . "";
	
		$t_hex = sprintf("%08x", $time);
		$m = md5($serverData[0]['stream_secret'].$f.$t_hex);
	
		$link = "" . $serverData[0]['stream_url'] . "/stream/" . $m . "/" . $t_hex . "" . $f . "";
		
		if($db_data[0]['playtime'] == '') $duration = 0; else $duration = $db_data[0]['playtime'];
		
		if (in_array(strtolower(substr($link, strrpos($link, '.') + 1)), array('m4a','mp4','aac','flv')))
			$provider = 'video';
		else
			$provider = 'sound';
	}
	
	$db->Raw("UPDATE `userdb_uploads` SET `count`=`count`+1 WHERE `id`='$id'"); // Updating individual song count.

	// placing it into our activities database...
	if(isset($_POST['fb_sig_user']))
		$db->Raw("INSERT INTO `userdb_actions` (`xid`,`who`,`action`) VALUES ('$id','$_POST[fb_sig_user]','play')");
	else
		$db->Raw("INSERT INTO `userdb_actions` (`xid`,`who`,`action`) VALUES ('$id','outside','play')");
	
?>

	<playlist version="1" xmlns="http://xspf.org/ns/0/">
		<tracklist>
			<track>
				<location><?php echo $link; ?></location>
				<provider><?php echo $provider; ?></location>
			</track>
		</tracklist>
	</playlist>

<?php } else { // else statement from xmlData ?>
	
	<?php 
	// lets check if it exists in our db first...
	$db_data = $db->Raw("SELECT `xid` FROM `userdb_uploads` WHERE `xid`='$id' OR `id`='$id';");
		
	if (is_null($db_data[0])) {
		error('Data could not be located!','Check the input ID or URL and try again. If the problem still persists, please contact the developer.');
		die();
	}
	?>

	<?php $xmlData = base64_encode('' . $id . '-' . time() . ''); ?>

	<?php if($_GET['caller'] == 'editor') { ?>	
		<center>
		<embed
			src="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf" 
			flashvars="file=<?php echo $config['fb']['appcallbackurl']; ?>player.php?xmlData=<?php echo $xmlData; ?>&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/skewd.zip&autostart=1"
			height="28"
			width="500"
		/>
		</center>
	<?php } else if(isset($_GET['from_friends']) || isset($_GET['from_tab'])) { ?>
		<fb:swf 
			swfsrc="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf" 
			flashvars="file=<?php echo $config['fb']['appcallbackurl']; ?>player.php?xmlData=<?php echo $xmlData; ?>&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/skewd.zip&autostart=1"
			height="28" 
			width="<?php if(isset($from_friends)) echo "420"; elseif(isset($from_tab)) echo "760"; ?>" 
		/>
	<?php } else { ?>
		<fb:wide>
			<fb:swf 
				swfsrc="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf" 
				flashvars="file=<?php echo $config['fb']['appcallbackurl']; ?>player.php?xmlData=<?php echo $xmlData; ?>&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/skewd.zip&autostart=1"
				height="28" 
				width="380" 
			/>
		</fb:wide>
		<fb:narrow>
			<fb:swf 
				swfsrc="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf" 
				flashvars="file=<?php echo $config['fb']['appcallbackurl']; ?>player.php?xmlData=<?php echo $xmlData; ?>&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/skewd-slim.zip&autostart=1"
				height="28" 
				width="184" 
				/>
		</fb:narrow>
	<?php } ?>

<?php } ?>