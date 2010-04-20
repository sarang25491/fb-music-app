<table border="0" cellspacing="0" cellpadding="10" width="100%">
	<tr>
		
		<td width="50%" valign="top">
		
			<?php
			if (isset($_GET['hide']))
				$db->Raw("UPDATE `userdb_plays` SET `hide`='1' WHERE `player`='$user' AND `owner`='$_GET[target]' AND `id`='$_GET[hide]'");
			?>
		
			<div style="padding-left: 10px; font-size: 14px; font-weight: bold;">Songs You've Recently Played</div>
			<?php $recent_plays = $db->Raw("SELECT DISTINCT `id`,`owner`,`title`,`artist` FROM `userdb_plays` WHERE `player`='$user' AND `hide`='0' ORDER BY `time` DESC LIMIT 10"); ?>
			<?php $recent_plays = stripslashes_deep($recent_plays); ?>
			<center>
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
				<tr>
					<td style="border-bottom: 1px solid #899cc1;" width="5%">
					</td>
					
					<td style="border-bottom: 1px solid #899cc1;" width="95%">
					</td>
				</tr>
				<?php foreach($recent_plays as $display) { ?>
					<tr>
						<td width="85%">
						
						<div style="padding-left: 15px;"><?php echo htmlspecialchars_decode(utf8_decode($display['title']), ENT_QUOTES); ?> by <?php echo htmlspecialchars_decode(utf8_decode($display['artist']), ENT_QUOTES); ?></div>
												
						</td>
					
						<td width="15%">
						<div style="padding-right:15px;" align="right"><b><a href="<?php echo $config['fb']['fburl']; ?>?tab=index&hide=<?php echo $display['id']; ?>&target=<?php echo $display['owner']; ?>">hide</a></b></div>
						</td>
						

					</tr>
				<?php } ?>

			</table>
			</center>
			
			
		</td>
		<td width="50%" valign="top">
			
			<div style="padding-left: 10px; font-size: 14px; font-weight: bold;">Recent Listeners</div>
			<?php $recent_plays = $db->Raw("SELECT DISTINCT `player`,`title`,`artist` FROM `userdb_plays` WHERE `owner`='$user' AND `player`!='$user' ORDER BY `time` DESC LIMIT 10"); ?>
			<?php $recent_plays = stripslashes_deep($recent_plays); ?>
			<center>
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
				<tr>
					<td style="border-bottom: 1px solid #899cc1;" width="100%">
					</td>
				</tr>
				<?php foreach($recent_plays as $display) { ?>
					<tr>
						<td>
						<div align="left" style="padding-left:15px; padding-right:15px;"><b><a href="http://www.facebook.com/profile.php?id=<?php echo $display['player']; ?>"><fb:name uid="<?php echo $display['player']; ?>" capitalize="true" ifcantsee="Anonymous" /></a></b> played "<?php echo htmlspecialchars_decode(utf8_decode($display['title']), ENT_QUOTES); ?> - <?php echo htmlspecialchars_decode(utf8_decode($display['artist']), ENT_QUOTES); ?>"</div>
						</td>
					</tr>
				<?php } ?>

			</table>
			</center>
			
		</td>
	</tr>
</table>
