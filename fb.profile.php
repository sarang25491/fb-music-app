<?php

$orderedList = $db->Raw("SELECT * FROM `userdb_uploads` WHERE `user` = '$user'");
$orderedList = $orderedList[0]['COUNT(*)'];

if ($orderedList > 1) {
	$result = $mysqli->query("SELECT `id`,`title`,`artist`,`link` FROM `userdb_uploads` WHERE `user` = '$user' ORDER BY `id` DESC");
} else {
	$result = $mysqli->query("SELECT `id`,`title`,`artist`,`link` FROM `userdb_uploads` WHERE `user` = '$user' ORDER BY `order` ASC");
}

while ($row = $result->fetch_assoc()) {
	$uploads[] = $row;
}

$i=0;
$uploads_count = count($uploads);
$total_count = $uploads_count;
?>
		
<?php if ($total_count == '0') { ?>
	<?php $fbml = '<center>No Music! Click <a href="' . $fb_url . '>here</a> to add some!"</center>'; ?>
<?php } elseif ($total_count > '0') { ?>

	<?php 
	$fbml = '
	<fb:fbml version="1.0">
	<center>
	<form id="dummy_form"></form>
	<div id="player">
	<img src="' . $config['fb']['appcallbackurl'] . '/images/spinner.gif" id="spinner" style="display:none; padding-bottom: 5px;"/>
	</div>
	
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	'; 
	?>

	<?php foreach($uploads as $display) { ?>
		<?php $i+=1; ?>
		<?php 
		$fbml = '' . $fbml . '
		<tr>
		<td>
			<center>
		';
		?>

			<?php if ($uploads_count == $i) { ?>
				<?php $fbml = '' . $fbml . '<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #F7F7F7; padding: 1px;">'; ?>
			<?php } else { ?>
				<?php $fbml = '' . $fbml . '<div style="border-top: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #F7F7F7; padding: 1px;">'; ?>
			<?php } ?>

			<?php 
			$fbml = '' . $fbml . '
			<table border="0" width="100%">
				<tr>
					<td valign="center" width="5%">
						<div style="padding-right: 5px; padding-left: 5px;"><a clickrewriteurl="' . $config['fb']['appcallbackurl'] . 'player.php?id=' . $display['id'] . '" clickrewriteid="player" clickrewriteform="dummy_form" clicktoshow="spinner"><img src="http://apps.burst-dev.com/music/images/track.gif" align="top" border="0"></a></div>
					</td>
					<td valign="center" width="95%">
						<a clickrewriteurl="' . $config['fb']['appcallbackurl'] . 'player.php?id=' . $display['id'] . '" clickrewriteid="player" clickrewriteform="dummy_form" clicktoshow="spinner">' . htmlspecialchars_decode(utf8_decode($display['title']), ENT_QUOTES) . ' by ' . htmlspecialchars_decode(utf8_decode($display['artist']), ENT_QUOTES) . '</a>
					</td>
				</tr>
			</table>
			
			</div>
			</center>
		</td>
		</tr>
		'; ?>
	<?php } ?>
		
	<?php 
	$fbml='' . $fbml . '
	</table>
	</center>
	';?>
<?php } ?>

<?php $facebook->api_client->profile_setFBML(NULL,$user,$fbml,NULL,NULL,$fbml); ?>