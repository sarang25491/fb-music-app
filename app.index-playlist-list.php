<?php $pre = 'skip_fbapi'; include 'include/config.php'; ?>

<?php
if (isset($userId)) //called from the external playlist (playlist.php)
	$id = $userId;
elseif (isset($_GET['fb_page_id']))
	$id = $_GET['fb_page_id'];
elseif (isset($_GET['fb_sig_user']))
	$id = $_GET['fb_sig_user'];
?>

<?php
$result = $mysqli->query("SELECT `title`,`artist`,`xid` FROM `userdb_uploads` WHERE `user` = '$id' ORDER BY `order`,`id` DESC");
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
					
					<td width="80%" valign="center">
						<div style="font-size:9pt;"><a style="padding-left: 2px; padding-right: 6px; vertical-align: middle;" href="#player" onclick="openPlayer(<?php echo $song['xid']; ?>)" ><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/track.gif" align="top" border="0"></a><a href="#player" onclick="openPlayer(<?php echo $song['xid']; ?>)" ><?php echo htmlspecialchars_decode(utf8_decode($song['title']), ENT_QUOTES); ?> by <?php echo htmlspecialchars_decode(utf8_decode($song['artist']), ENT_QUOTES); ?></a></div>
					</td>
					
					<?php if (!isset($userId)) { ?> 
					<td width="20%">
						<div align="right"><img src="images/key.png" border="0" onclick="showApiKey(<?php echo $song['xid']; ?>)" style="padding-right:2px;"><img src="images/info.png" border="0" onclick="showInfo(<?php echo $song['xid']; ?>)" style="padding-right:2px;"><img src="images/tag_blue_edit.png" border="0" onclick="editTag(<?php echo $song['xid']; ?>)" style="padding-right:2px;"><img src="images/delete.png" border="0" onclick="removeSong(<?php echo $song['xid']; ?>)"></div>
					</td>
					<?php } ?>
					
				</tr>
			</table>
		</div><!-- end playlistArray-XID -->
	
	<?php } ?>
<?php } ?>