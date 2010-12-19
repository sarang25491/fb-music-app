<?php
$pre = 'skip_fbapi';
include_once 'include/config.php';
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
}
?>

<?php if($_GET['caller'] == 'editor') { ?>	
	<center>
		<embed
			src="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf"
			flashvars="plugins=ltas,<?php echo $config['fb']['appcallbackurl']; ?>flash/plugins/burstplugin.swf&burstplugin.xid=<?php echo $id; ?>&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/skewd.zip&bufferlength=1"
			height="28"
			width="500"
		/>
	</center>
<?php } else if (isset($_GET['from_friends']) || isset($_GET['from_tab'])) { ?>
	<fb:swf 
		swfsrc="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf"
		flashvars="file=http://music.burst-dev.com/testing/flash/test.mp3&plugins=<?php echo $config['fb']['appcallbackurl']; ?>flash/plugins/burstplugin.swf&burstplugin.xid=<?php echo $id; ?>&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/skewd.zip&bufferlength=1"
		height="28" 
		width="<?php if(isset($from_friends)) echo "420"; elseif(isset($from_tab)) echo "760"; ?>" 
	/>
<?php } else { ?>
	<fb:wide>
		<fb:swf 
			swfsrc="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf"
			flashvars="plugins=<?php echo $config['fb']['appcallbackurl']; ?>flash/plugins/burstplugin.swf&burstplugin.xid=<?php echo $id; ?>&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/skewd.zip&bufferlength=1"
			height="28" 
			width="380" 
		/>
	</fb:wide>
	<fb:narrow>
		<fb:swf 
			swfsrc="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf"
			flashvars="plugins=<?php echo $config['fb']['appcallbackurl']; ?>flash/plugins/burstplugin.swf&burstplugin.xid=<?php echo $id; ?>&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/skewd-slim.zip&bufferlength=1"
			height="28" 
			width="184" 
			/>
	</fb:narrow>
<?php } ?>

<?php
$db->Raw("UPDATE `userdb_uploads` SET `count`=`count`+1 WHERE `id`='$id'"); // Updating individual song count.
?>
