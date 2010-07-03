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
	<div style="padding-left: 10px; font-size: 14px; font-weight: bold;">Recent Activity & Announcements (beta)</div>
	<center>
	<table border="0" cellpadding="2" cellspacing="0" width="100%">
		<tr>
			<td style="border-bottom: 1px solid #899cc1;">
			</td>
		</tr>
		
		<?php foreach ($announcements as $ann) { ?>
			<tr>
				<td>
				
				<div style="margin-left: 15px; margin-right: 15px; background-color: #eceff5;">
					<b>[<fb:time t="<?php echo strtotime($ann['time']); ?>" />] <?php echo $ann['message']; ?></b>
				</div>
				
				</td>
			
			
			</tr>
		<?php } ?>
		
		
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
				
				<div style="padding-left: 15px;">
					<b><?php echo $title; ?></b> by <b><?php echo $artist; ?></b> was played around <fb:time t="<?php echo $action['unix_timestamp(`time`)']; ?>" />.
				</div>
										
				</td>
			</tr>
		<?php } ?>
		
		<?php 
		
		$twitter = $db->Raw("SELECT * FROM `twitter`");
		
		?>
	
	</table>
	</center>
	
	<fb:dialog id="unknownUser">
	<fb:dialog-title>Help</fb:dialog-title>
	<fb:dialog-content>
		<div style="font-size: 16pt;">We cannot track what everyone does sometimes, which is why it displays as "someone". This most likely occurs when you have songs that are played outside of Facebook, within the editor, or on Wall posts.</div>
	</fb:dialog-content>
	<fb:dialog-button type="button" value="Close" close_dialog=1 />
	</fb:dialog>
<?php } ?>
