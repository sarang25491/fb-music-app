<?php /*
* When the editor is called, it asks the database for the song data.
* It will then display the the information that is preset into the
* form. The user can then edit the necessary information.
*
* @param $id The ID of the song in the specific database.
* @param $source Specifies if the ID is a uploaded or linked song.
* @return The form of the necessary information to edit.
*/ ?>
<?php if ($_GET['action'] == 'edit') { ?>
	
	<?php 
	$db_info = $db->Raw("SELECT `title`,`artist`,`buy_link`,`dl` FROM `userdb_uploads` WHERE `id`='$_GET[id]' AND `user`='$user'");

	if (count($db_info) <= 0) {
		die('404 - Not Found');
	}
	?>
	
	<?php explanation('<fb:intl>Edit Existing Song Info</fb:intl>','<fb:intl>Here you can edit the song information to an existing song in your profile.</fb:intl>'); ?>
	
	<fb:editor action="?tab=index&action=commit&update&id=<?php echo $_GET['id']; ?><?php pages($fb_page_id); ?>" labelwidth="0">
		<fb:editor-text label="Title" name="title" value="<?php echo htmlspecialchars_decode(utf8_decode($db_info[0]['title']), ENT_QUOTES); ?>" maxlength="100" />
		<fb:editor-text label="Artist" name="artist" value="<?php echo htmlspecialchars_decode(utf8_decode($db_info[0]['artist']), ENT_QUOTES); ?>" maxlength="100" />
		<?php
		if (isset($_GET['fb_page_id'])) {
			$verifyStatus = $db->Raw("SELECT `status` FROM `pages` WHERE `fb_page_id`='$_GET[fb_page_id]'");
			if ($verifyStatus[0]['status'] == 2) {
		?>
		<fb:editor-text label="Buy Link" name="buy_link" value="<?php echo $db_info[0]['buy_link']; ?>" maxlength="100" />
		<fb:editor-custom label="DL-able?">
			<select name="dl">
				<option value="0" <?php if($db_info[0]['dl'] == 0) echo 'selected'; ?>>downloadable to nobody</option>
				<option value="1" <?php if($db_info[0]['dl'] == 1) echo 'selected'; ?>>downloadable to all</option>
			</select>
		</fb:editor-custom>
		<?php
			}
		}
		?>
			<fb:editor-buttonset>
			<fb:editor-button value="Update"/>
		<fb:editor-cancel value="Cancel" href="index.php?tab=index&action=none<?php pages($fb_page_id); ?>" />
		</fb:editor-buttonset>
	</fb:editor>
	
<?php } else { ?>
	<?php if ($_GET['action'] == 'delete') { ?>
		<?php 
		$deleteData = $db->Raw("SELECT `type`,`link`,`server`,`drive` FROM `userdb_uploads` WHERE `id`='$id' LIMIT 1");
		if ($deleteData[0]['type'] == 'upload') { 
			$server = $deleteData[0]['server'];
			$serverData = $db->Raw("SELECT `internal_uri` FROM `servers` WHERE `name`='$server'");
			$userFolder = array_sum(str_split($user));
			
			if(file_exists('' . $serverData[0]['internal_uri'] . 'users/' . $deleteData[0]['drive'] . '/' . $userFolder . '/' . baseName($deleteData[0]['link']) . ''))
				unlink('' . $serverData[0]['internal_uri'] . 'users/' . $deleteData[0]['drive'] . '/' . $userFolder . '/' . baseName($deleteData[0]['link']) . '');
		}

		$db->Raw("DELETE FROM `userdb_uploads` WHERE `id`='$id'"); 
		?>
	<?php } elseif ($_GET['action'] == 'commit') { ?>
		<?php 
		$_POST['title'] = htmlspecialchars(utf8_encode($_POST['title']), ENT_QUOTES); $_POST['artist'] = htmlspecialchars(utf8_encode($_POST['artist']), ENT_QUOTES); 
		$db->Raw("UPDATE `userdb_uploads` SET `title`='$_POST[title]',`artist`='$_POST[artist]',`buy_link`='$_POST[buy_link]',`dl`='$_POST[dl]' WHERE `id`='$id'"); 
		?>
	<?php } elseif ($_GET['action'] == 'move') { ?>
		<?php
		$uploads = $db->Raw("SELECT `id` FROM `userdb_uploads` WHERE `user`='$user' ORDER BY `id`");
		if (count($uploads) == '1') {
			error('<fb:intl>Error</fb:intl>','<fb:intl>You are requesting to do something that is simply not possible to do.</fb:intl>'); 
			die();
		} else {
			if($_GET['where'] == 'up') {
				$db_ids = array(); $i = 0; 
				foreach($uploads as $uploads) { 
					$db_ids[$i] = $uploads['id']; 
					$i+=1; 
				}
				$request_key = array_search($id, $db_ids); 
				$replace_key = $request_key-1; 
				$replace_id = $db_ids[$replace_key];
			} elseif ($_GET['where'] == 'down') {
				$db_ids = array(); $i = 0; 
				foreach($uploads as $uploads) { 
					$db_ids[$i] = $uploads['id']; 
					$i+=1; 
				} 
				$request_key = array_search($id, $db_ids); 
				$replace_key = $request_key+1; 
				$replace_id = $db_ids[$replace_key];
			}
			
			if ($request_key < '0') {
				error('<fb:intl>Error</fb:intl>','<fb:intl>You are requesting to do something that is simply not possible to do.</fb:intl>'); 
				die();
			} else {
				$request_data = $db->Raw("SELECT * FROM `userdb_uploads` WHERE `id`='$id'");
				$replace_data = $db->Raw("SELECT * FROM `userdb_uploads` WHERE `id`='$replace_id'");
				unset($request_data[0]['id']);
				unset($request_data[0]['xid']);
				unset($request_data[0]['time']);
				unset($replace_data[0]['id']);
				unset($request_data[0]['xid']);
				unset($replace_data[0]['time']);
				
				// a makeshift way of not making it complicated... should be self explanitory.
				
				foreach ($request_data[0] as $key => $value) {
					$requestString = '' . $requestString . '`' . $key . '`=\'' . $value . '\',';
				}
				
				$requestString = substr_replace($requestString,"",-1);
				
				foreach ($replace_data[0] as $key => $value) {
					$replaceString = '' . $replaceString . '`' . $key . '`=\'' . $value . '\',';
				}
				
				$replaceString = substr_replace($replaceString,"",-1);
				
				$db->Raw("UPDATE `userdb_uploads` SET " . $replaceString . " WHERE `id`='$id'");
				$db->Raw("UPDATE `userdb_uploads` SET " . $requestString . " WHERE `id`='$replace_id'");
			}
		}
		?>	
	<?php } ?>
	
	<form id="dummy_form"></form>
	<div id="player" align="center">
	<img src="<?php echo $config['fb']['appcallbackurl']; ?>/images/spinner.gif" id="spinner" style="display:none; padding-bottom: 5px;"/>
	</div>
	
	<center>
	<?php $i=0; ?>
	<?php $uploads = $db->Raw("SELECT `id`,`title`,`artist` FROM `userdb_uploads` WHERE `user`='$user' ORDER BY `id`"); ?>
	<?php $uploads_count = count($uploads); ?>
	<?php $total_count = $uploads_count; ?>
	<?php if($total_count == 0) { ?>
		<?php error('No Songs','<center>You don\'t have any songs to create a playlist, click "add a song" to fix this issue.</center>'); ?>
	<?php } else { ?>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<?php $i=0; ?>
			<?php foreach($uploads as $display) { ?>
				<?php $i+=1; ?>
				<tr>
					<td>
						<center>

							<?php if ($uploads_count == $i) { ?>
								<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #F7F7F7; padding: 1px;">
							<?php } else { ?>
								<div style="border-top: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #F7F7F7; padding: 1px;">
							<?php } ?>

							<table border="0" width="100%">
								<tr>
									<td valign="center" width="5%">
										<div style="padding-right: 5px; padding-left: 5px;"><a clickrewriteurl="<?php echo $config['fb']['appcallbackurl']; ?>player.php?id=<?php echo $display['id']; ?>&from_edit=1" clickrewriteid="player" clickrewriteform="dummy_form" clicktoshow="spinner"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/track.gif" align="top" border="0"></a></div>
									</td>
									<td valign="center" width="70%">
										<a clickrewriteurl="<?php echo $config['fb']['appcallbackurl']; ?>player.php?id=<?php echo $display['id']; ?>&from_edit=1" clickrewriteid="player" clickrewriteform="dummy_form" clicktoshow="spinner"><?php echo htmlspecialchars_decode(utf8_decode($display['title']), ENT_QUOTES); ?> by <?php echo htmlspecialchars_decode(utf8_decode($display['artist']), ENT_QUOTES); ?></a>
									</td>
									<td valign="center" width="25%">
									<div align="right">

									<?php if($uploads_count > 1) { ?>
										<?php if($i==1) { ?>
											<a href="index.php?tab=index&action=move&update&where=down&id=<?php echo $display['id']; ?><?php pages($fb_page_id); ?>"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/arrow_down.png" align="top" border="0"></a>
										<?php } elseif($i<$uploads_count) { ?>
											<a href="index.php?tab=index&action=move&update&where=up&id=<?php echo $display['id']; ?><?php pages($fb_page_id); ?>"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/arrow_up.png" align="top" border="0"></a>
											<a href="index.php?tab=index&action=move&update&where=down&id=<?php echo $display['id']; ?><?php pages($fb_page_id); ?>"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/arrow_down.png" align="top" border="0"></a>
										<?php } elseif($i==$uploads_count) { ?>
											<a href="index.php?tab=index&action=move&update&where=up&id=<?php echo $display['id']; ?><?php pages($fb_page_id); ?>"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/arrow_up.png" align="top" border="0"></a>
											<img src="<?php echo $config['fb']['appcallbackurl']; ?>images/edit_place_holder.png" align="top" border="0">
										<?php } ?>
									<?php } ?>
										<a href="#" clicktoshowdialog="embed_type_upload_id_<?php echo $display['id']; ?>"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/embed.png" align="top" border="0"></a>
										<a href="#" clicktoshowdialog="share_type_upload_id_<?php echo $display['id']; ?>"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/link.png" align="top" border="0"></a>
										<a href="index.php?tab=index&action=edit&source=upload&id=<?php echo $display['id']; ?><?php pages($fb_page_id); ?>"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/tag_blue_edit.png" align="top" border="0"></a>
										<a href="index.php?tab=index&action=delete&update&source=upload&id=<?php echo $display['id']; ?><?php pages($fb_page_id); ?>"> <img src="<?php echo $config['fb']['appcallbackurl']; ?>images/delete.png" align="top" border="0"></a>
									</div>
									</td>
								</tr>
							</table>

							<fb:dialog id="share_type_upload_id_<?php echo $display['id']; ?>">
								<fb:dialog-title>Share Link</fb:dialog-title>
								<fb:dialog-content>
									<div style="margin: 5px; padding: 10px; border: 1px solid #cccccc; background-color: #F7F7F7; font-size: 1em;">
									<center>http://apps.burst-dev.com/music/player.php?upload=<?php echo $display['id']; ?>&from_share=1&challenge=<?php echo $user; ?>&autostart=true</center>
									</div>
								</fb:dialog-content>
								<fb:dialog-button type="button" value="Close" close_dialog=1 /> 
							</fb:dialog>
							
							<fb:dialog id="embed_type_upload_id_<?php echo $display['id']; ?>">
								<fb:dialog-title>Embed Code</fb:dialog-title>
								<fb:dialog-content>
									<div style="margin: 5px; padding: 10px; border: 1px solid #cccccc; background-color: #F7F7F7; font-size: 1em;">
										<center>&lt;iframe src="http://apps.burst-dev.com/music/player.php?upload=<?php echo $display['id']; ?>&from_embed=1&challenge=<?php echo $user; ?>" frameborder="0" height="45px" width="325px" /&gt;&lt;/iframe&gt;</center>
									</div>
								</fb:dialog-content>
								<fb:dialog-button type="button" value="Close" close_dialog=1 />
							</fb:dialog>
							
						</div></center>
					</td>
				</tr>
			<?php } ?>
			</table>
		</center>
	<?php } ?>
<?php } ?>