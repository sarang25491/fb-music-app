<?php
$pageList = $db->Raw("SELECT `fb_page_id` from `pages` WHERE `owner`='$_POST[fb_sig_user]'");

if (count($pageList) > 0) {
	foreach($pageList as $page) $pageString = "" . $pageString . "'" . $page['fb_page_id'] . "',";
	$pageString = substr_replace($pageString,"",-1);
	
	$xidList = $db->Raw("SELECT `xid` FROM `userdb_uploads` WHERE `user`='$_POST[fb_sig_user]' OR `user` IN (" . $pageString . ")");
} else {
	$xidList = $db->Raw("SELECT `xid` FROM `userdb_uploads` WHERE `user`='$_POST[fb_sig_user]'");
}

if (count($xidList) > 0) {
	foreach($xidList as $xid) $xidString = "" . $xidString . "'" . $xid['xid'] . "',";
	$xidString = substr_replace($xidString,"",-1);
	
	$activity = $db->Raw("SELECT unix_timestamp(`time`),`xid`,`who`,`action` FROM `userdb_actions` WHERE `xid` IN (" . $xidString . ") OR `who`='$user' ORDER BY `time` DESC LIMIT 15");
	$announcements = $db->Raw("SELECT `time`, `message` FROM `twitter` ORDER BY `id` DESC LIMIT 3");
	
	// print_r($activity);
	?>
	
	<fb:dialog id="ann" width="500">
		<fb:dialog-title>Announcements</fb:dialog-title>
		<fb:dialog-content>
			<?php include_once('./statics/twitter.txt'); ?>
		</fb:dialog-content>
		<fb:dialog-button type="button" value="Close" close_dialog=1 />
	</fb:dialog>
	
	<div style="padding-left: 10px; font-size: 14px; font-weight: bold; border-bottom: 1px solid #899cc1;">Recent Activity & Announcements (beta)</div>
	<div style="padding: 10px; margin: 10px; border: 1px solid #e2c822; background-color: #fff9d7; font-weight: bold; font-size: 12pt;">
		<?php echo $announcements[0]['message']; ?>
	</div>
	<div align="right" style="margin-top: -10px; margin-right: 10px; margin-bottom: 10px;">
		Message posted at <fb:time t="<?php echo strtotime($announcements[0]['time']); ?>" /> - <a clicktoshowdialog="ann">View More Recent Anouncements >></a>
	</div>
	
	
	<center>
	<div style="background-color: #f7f7f7; border: 1px solid #cccccc; padding: 10px; margin-left: 10px; margin-right: 10px;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<?php function action2txt($dbAction) {
		if ($dbAction == 'play')
			return 'played';
		} ?>
		<?php foreach($activity as $action) { ?>
			<tr>
				<td>
				
				<?php 
				$xidInfo = $db->Raw("SELECT `title`,`artist` FROM `userdb_uploads` WHERE `xid`='$action[xid]'"); 
				
				$title = htmlspecialchars_decode(utf8_decode($xidInfo[0]['title']), ENT_QUOTES);
				$artist = htmlspecialchars_decode(utf8_decode($xidInfo[0]['artist']), ENT_QUOTES);
				?>
				
				<b><?php echo $title; ?></b> by <b><?php echo $artist; ?></b> was played around <fb:time t="<?php echo $action['unix_timestamp(`time`)']; ?>" />.
										
				</td>
			</tr>
		<?php } ?>
		
		<?php 
		
		$twitter = $db->Raw("SELECT * FROM `twitter`");
		
		?>
	
	</table>
	</div>
	</center>
	
	<fb:dialog id="unknownUser">
	<fb:dialog-title>Help</fb:dialog-title>
	<fb:dialog-content>
		<div style="font-size: 16pt;">We cannot track what everyone does sometimes, which is why it displays as "someone". This most likely occurs when you have songs that are played outside of Facebook, within the editor, or on Wall posts.</div>
	</fb:dialog-content>
	<fb:dialog-button type="button" value="Close" close_dialog=1 />
	</fb:dialog>
<?php } ?>
