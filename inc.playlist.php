<?php $pre = 'skip_fbapi'; include 'include/config.php'; ?>

<link rel="stylesheet" type="text/css" href="javascript/colortip-1.0-jquery.css"/>
<script type="text/javascript" src="javascript/colortip-1.0-jquery.js"></script>

<script type="text/javascript">
$(document).ready(function(){

   /* Adding a colortip to any tag with a title attribute: */

   $('[title]').colorTip({color:'yellow'});

});
</script>

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
						<div style="font-size:9pt; margin: 1px 0 0 0;"><a class="blue" title="Play" href="#player" onclick="openPlayer(<?php echo $song['xid']; ?>)" ><img src="<?php echo $config['fb']['appcallbackurl']; ?>img/track.gif" style="margin: -4px 6px 0 4px" valign="middle" border="0"><?php echo htmlspecialchars_decode(utf8_decode($song['title']), ENT_QUOTES); ?> by <?php echo htmlspecialchars_decode(utf8_decode($song['artist']), ENT_QUOTES); ?></a></div>
					</td>
					
					<?php if (!isset($userId)) { ?> 
					<td width="20%">
						<div align="right" style="margin-top: 2px; padding-right: 4px;">
                     <a class="blue" valign="middle" title="Song Info" onclick="showInfo(<?php echo $song['xid']; ?>)"><img src="img/info.png" border="0" style="padding-right:2px;"></a>
                     <a class="move" border="0" title="Hold & Drag to Sort"><img src="img/move.png" border="0" style="padding-right: 2px;"></a>
                     <a class="blue" valign="middle" title="Edit Tags" onclick="editTag(<?php echo $song['xid']; ?>)"><img src="img/tag_blue_edit.png" border="0" style="padding-right:2px;"></a>
                     <a class="red" valign="middle" title="Delete" onclick="removeSong(<?php echo $song['xid']; ?>)"><img src="img/delete.png" border="0"></a>
                  </div>
					</td>
					<?php } ?>
					
				</tr>
			</table>
		</div><!-- end playlistArray-XID -->
	
	<?php } ?>
<?php } ?>
