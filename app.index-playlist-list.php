<?php $pre = 'skip_fbapi'; include 'include/config.php'; ?>

<?php
if(!isset($_GET['id'])) {
	if (isset($_GET['fb_page_id']))
		$id = $_GET['fb_page_id'];
	else
		$id = $_GET['fb_sig_user'];
} else
	$id = $_GET['id'];
?>

<?php 
$orderedList = $db->Raw("SELECT * FROM `userdb_uploads` WHERE `user` = '$id'"); 
//$orderedList = $db->Raw("SELECT COUNT(*) FROM `userdb_uploads` WHERE `user` = '1340490250' AND `order`='0'");
$orderedList = $orderedList[0]['COUNT(*)'];

if ($orderedList > 1) {
	$result = $mysqli->query("SELECT `title`,`artist`,`xid` FROM `userdb_uploads` WHERE `user` = '$id' ORDER BY `id` DESC");
} else {
	$result = $mysqli->query("SELECT `title`,`artist`,`xid` FROM `userdb_uploads` WHERE `user` = '$id' ORDER BY `order` ASC");
}

while ($row = $result->fetch_assoc()) {
	$playlist[] = $row;
}
?>

<style>
A:link {text-decoration: none; color: #3b5998;}
A:visited {text-decoration: none; color: #3b5998;}
A:active {text-decoration: none; color: #3b5998;}
A:hover {text-decoration: underline; color: red;}
</style>

<?php if (count($playlist) == 0) { ?>
	<div align="center" style="border: 1px solid #dd3c10; background-color: #ffebe8; padding: 10px; font-size: 2em; font-weight: bold;">whaa?? no music?! go <a target="_parent" href="<?php echo $config['fb']['fburl']; ?>?tab=index&display=add<?php if(isset($_GET['fb_page_id'])) echo '&fb_page_id=' . $id . ''; ?>">add</a> some!</div>
<?php } else {?>

	<?php foreach ($playlist as $song) { ?>
		
		<div style="border: 1px solid #cccccc; margin-bottom:-1px; padding: 3px; background-color: #f7f7f7;" id="playlist_<?php echo $song['xid']; ?>">
		
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td width="5%" valign="center">
						<div style="padding-left: 2px;padding-right: 4px;"><a href="#player" onclick="openPlayer(<?php echo $song['xid']; ?>)" ><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/track.gif" align="top" border="0"></a></div>
					</td>
					<td width="55%" valign="center">
						<div style="font-size:1em;"><a href="#player" onclick="openPlayer(<?php echo $song['xid']; ?>)" ><?php echo $song['title']; ?> by <?php echo $song['artist']; ?></a></div>
					</td>
					<td width="40%">
						<div align="right"><img src="images/key.png" border="0" onclick="showApiKey(<?php echo $song['xid']; ?>)" style="padding-right:2px;"><img src="images/info.png" border="0" onclick="showInfo(<?php echo $song['xid']; ?>)" style="padding-right:2px;"><img src="images/tag_blue_edit.png" border="0" onclick="editTag(<?php echo $song['xid']; ?>)" style="padding-right:2px;"><img src="images/delete.png" border="0" onclick="removeSong(<?php echo $song['xid']; ?>)"></div>
					</td>
				</tr>
			</table>
		</div><!-- end playlistArray-XID -->
	
	<?php } ?>
<?php } ?>