<?php $pre = 'skip_fbapi'; include 'include/config.php'; ?>

<?php 
// $playlist = $db->Raw("SELECT * FROM `userdb_uploads` WHERE `user` = '$_GET[fb_sig_user]'"); 
$orderedList = $db->Raw("SELECT COUNT(*) FROM `userdb_uploads` WHERE `user` = '1340490250' AND `order`='0'");
$orderedList = $orderedList[0]['COUNT(*)'];

if ($orderedList > 1) {
	$result = $mysqli->query("SELECT `title`,`artist`,`xid` FROM `userdb_uploads` WHERE `user` = '1340490250' ORDER BY `id` DESC");
} else {
	$result = $mysqli->query("SELECT `title`,`artist`,`xid` FROM `userdb_uploads` WHERE `user` = '1340490250' ORDER BY `order` ASC");
}

while ($row = $result->fetch_assoc()) {
	$playlist[] = $row;
}
?>

<?php foreach ($playlist as $song) { ?>
	
	<div style="border: 1px solid #cccccc; margin-bottom:-1px; padding: 3px; background-color: #f7f7f7;" id="playlist_<?php echo $song['xid']; ?>">
	
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td width="4%" valign="center">
					<img src="<?php echo $config['fb']['appcallbackurl']; ?>images/track.gif" align="top" border="0">
				</td>
				<td width="55%" valign="center">
					<div style="font-size:0.8em;"><?php echo $song['title']; ?> by <?php echo $song['artist']; ?></div>
				</td>
				<td width="40%">
					<div align="right"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/delete.png" align="top" border="0" onclick="removeSong(<?php echo $song['xid']; ?>)" ></div>
				</td>
			</tr>
		</table>
	</div><!-- end playlistArray-XID -->

<?php } ?>