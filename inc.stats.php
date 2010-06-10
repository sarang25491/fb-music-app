<div style="padding-left: 10px; font-size: 14px; font-weight: bold;">Recent Activity</div>
<?php 
$xidList = $db->Raw("SELECT `xid` FROM `userdb_uploads` WHERE `user`='$user'");
foreach($xidList as $xid) $xidString = "" . $xidString . "'" . $xid['xid'] . "',";
$xidString = substr_replace($xidString,"",-1);

$activity = $db->Raw("SELECT unix_timestamp(`time`),`xid`,`who`,`action` FROM `userdb_actions` WHERE `xid` IN (" . $xidString . ") OR `who`='$user' ORDER BY `time` DESC LIMIT 20");
// print_r($activity);
?>
<center>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>
		<td style="border-bottom: 1px solid #899cc1;">
		</td>
	</tr>
	
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
			
				<?php if ($action['who'] == $user) { ?>
					<b>You</b>'ve <?php echo action2txt($action['action']); ?> your own song, <b><?php echo $title; ?></b> by <b><?php echo $artist; ?></b> at <fb:time t="<?php echo $action['unix_timestamp(`time`)']; ?>" />.
				<?php } else if ($action['who'] == 'outside') { ?>
					<b>Someone</b>(<a clicktoshowdialog="unknownUser">?</a>) has <?php echo action2txt($action['action']); ?> <b><?php echo $title; ?></b> by <b><?php echo $artist; ?></b> at <fb:time t="<?php echo $action['unix_timestamp(`time`)']; ?>" />.
				<?php } else { ?>
					<b><fb:name uid="<?php echo $action['who']; ?>" /></b> <?php echo action2txt($action['action']); ?> <b><?php echo $title; ?></b> by <b><?php echo $artist; ?></b> at <fb:time t="<?php echo $action['unix_timestamp(`time`)']; ?>" />.
				<?php } ?>
			
			
			</div>
									
			</td>
		</tr>
	<?php } ?>

</table>
</center>

<fb:dialog id="unknownUser">
<fb:dialog-title>Help</fb:dialog-title>
<fb:dialog-content>
	<div style="font-size: 16pt;">We cannot track what everyone does sometimes, which is why it displays as "someone". This most likely occurs when you have songs that are played outside of Facebook, within the editor, or on Wall posts.</div>
</fb:dialog-content>
<fb:dialog-button type="button" value="Close" close_dialog=1 />
</fb:dialog>
