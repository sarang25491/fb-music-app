<?php
include_once 'include/facebook/facebook.php';
include_once 'include/config.php';
include_once 'include/functions.php';
?>

<?php 
if(isset($_GET['fb_page_id']) AND $_POST['fb_sig_is_admin'] == '1')
{
	echo '<fb:page-admin-edit-header></fb:page-admin-edit-header>'; 
	$db->Raw("UPDATE `pages` SET `owner`='$_POST[fb_sig_user]' WHERE `fb_page_id`='$_GET[fb_page_id]'");
} 
?>

<?php $is_pro = $db->Raw("SELECT `pro` FROM `userdb_users` WHERE `user`='$_POST[fb_sig_user]'"); $is_pro = $is_pro[0]['pro']; ?>
<?php if($is_pro == 0) include 'include/advertisement.php'; ?>

<div style="border: 1px solid #cccccc;">
	
<?php 
if(!isset($_GET['tab'])) {
	
	if(isset($_GET['fb_page_id']))
		$db->Raw("INSERT IGNORE INTO `pages` (`fb_page_id`,`owner`,`status`) VALUES ('$_GET[fb_page_id]','$_POST[fb_sig_user]','0')");
	
	$db->Raw("INSERT INTO `userdb_users` (`user`,`credit`,`override`,`pro`) VALUES ('$user','0','0','0') ON DUPLICATE KEY UPDATE `time`=CURRENT_TIMESTAMP");
	
	$accepted_tos = $db->Raw("SELECT COUNT(*) FROM `userdb_tos` WHERE `user`='$user'");
	
	if (isset($_GET['accept_tos']))
	{
		$db->Raw("INSERT INTO `userdb_tos` (`user`) VALUES ('$user')");
		if(isset($_GET['fb_page_id']))
		{ 
			redirect('' . $config['fb']['fburl'] . '?tab=index&fb_page_id=' . $_GET['fb_page_id'] . ''); 
		} else { 
			redirect('' . $config['fb']['fburl'] . '?tab=index'); 
		}
		die();
	} elseif ($accepted_tos[0]['COUNT(*)'] == '0') {
		explanation('Terms of Service','For legal reasons, you must agree to our terms in order to use the application.');
		include 'tos.php';
?>
		<fb:editor action="<?php echo $config['fb']['fburl']; ?>?accept_tos<?php if(isset($_GET['fb_page_id'])) { echo '&fb_page_id=' . $_GET['fb_page_id'] . ''; } ?>" labelwidth="0">
			<fb:editor-buttonset>
				<fb:editor-button value="Agree"/>
			</fb:editor-buttonset>
		</fb:editor>
<?php
		die();
	} elseif ($accepted_tos[0]['COUNT(*)'] >= 1) {
		if(isset($_GET['fb_page_id']))
		{ 
			redirect('' . $config['fb']['fburl'] . '?tab=index&fb_page_id=' . $_GET['fb_page_id'] . ''); 
		} else { 
			redirect('' . $config['fb']['fburl'] . '?tab=index'); 
		}
	}
}
?>

<div id="header" style="padding-top: 15px; padding-right: 25px; padding-left: 20px; padding-bottom: 50px;">
	<div style="float: left;"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/music.png" /></div>
	<div style="float: right;"><?php include 'inc.tips.php'; ?></div>
</div>

<fb:tabs>
	<fb:tab-item href='<?php echo $config['fb']['fburl']; ?>?tab=index<?php pages($_GET['fb_page_id']); ?>' title='My Music' <?php if($_GET['tab'] == 'index') { echo "selected='true'"; } ?> />
	<fb:tab-item href='<?php echo $config['fb']['fburl']; ?>?tab=music_friends' title="Friend's Music" <?php if($_GET['tab'] == 'music_friends') { echo "selected='true'"; } ?> />
	<fb:tab-item href='<?php echo $config['fb']['fburl']; ?>?tab=offers' title='Earn Slots' align='right' <?php if($_GET['tab'] == 'offers') { echo "selected='true'"; } ?> />
	<fb:tab-item href='<?php echo $config['fb']['fburl']; ?>?tab=help' title='Get Help' align='right' <?php if($_GET['tab'] == 'help') { echo "selected='true'"; } ?> />
</fb:tabs>

<?php
switch($_GET['tab']) {
	case "index":
		include "app.index.php";
		break;
	case "music_friends":
		include "app.friends.php";
		break;
	case "offers":
		include "inc.super-rewards.php";
		break;
	case "help":
		include "app.help.php";
		break;
	case "verify":
		include "app.verify.php";
		break;	
}
?>

</div>

<?php if($is_pro == 0) include 'include/advertisement.php'; ?>

<div style="margin-top: 10px; border-top: 1px solid #d8dfea; padding: 3px 16px; height: 14px; color: #3b5998;">
	<div style="float: left;"><a href="http://www.facebook.com/apps/application.php?id=2436915755" target="_blank">Music</a> v2.3.1 [<a href="<?php echo $config['fb']['callbackurl']; ?>">Acceptable Use & Privacy Policy</a>]</div>
	<div style="float: right;">A <a href="http://burst-dev.com/" target="_blank">Burst Development</a> Project by <a href="http://stevenlu.com">Steven J. Lu</a></div>
</div>

<fb:google-analytics uacct="UA-2250290-1" />