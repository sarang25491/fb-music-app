<?php
/*
First display page (display == null) will show the editing player functions for the user.

The page is split into two columns by a table, then in those columns are tables.
The left hand column will display the user's player and edit functions,
while the right hand column simply displays editing information.

Below the editor is a button where a user can add a song.
*/
?>

<?php include_once 'app.index-header.php'; ?>

<?php if ($_GET['display'] == NULL) { ?>
	<div style="margin: 10px;">
	<table border="0" width="100%" cellspacing="5px">
		<tr>
			<td valign="top">
				<div style="border: 1px solid #cccccc; padding: 10px; margin-top: 5px; margin-bottom: 5px;">
				<table border="0" width="100%">
					<tr>
						<td valign="top">
							<div>
								<?php 
								if (isset($_GET['hide_box'])) {
									if (isset($_GET['fb_page_id']))
										$db->Raw("UPDATE `pages` SET `comment_box`='0' WHERE `fb_page_id`='$_GET[fb_page_id]'");
									else
										$db->Raw("UPDATE `userdb_users` SET `comment_box`='0' WHERE `user`='$user'");
								} elseif (isset($_GET['show_box'])) {
									if (isset($_GET['fb_page_id']))
										$db->Raw("UPDATE `pages` SET `comment_box`='1' WHERE `fb_page_id`='$_GET[fb_page_id]'");
									else
										$db->Raw("UPDATE `userdb_users` SET `comment_box`='1' WHERE `user`='$user'");
								}
								?>
								
								<?php 
								if (isset($_GET['fb_page_id']))
									$commentBoxStatus = $db->Raw("SELECT `comment_box` FROM `pages` WHERE `fb_page_id`='$_GET[fb_page_id]'");
								else
									$commentBoxStatus = $db->Raw("SELECT `comment_box` FROM `userdb_users` WHERE `user`='$user'");
								?>

							</div>
						</td>
					</tr>
					<tr>
						<td valign="top">
							
							<center><fb:iframe src="<?php echo $config['fb']['appcallbackurl']; ?>app.index-playlist.php?<?php echo pages($_GET['fb_page_id']); ?>" width="500" height="1000" frameborder="0" scrolling="no" name="editor" resizeable="true" /></center>
							<?php if (isset($_GET['publish'])) { 
								include 'fb.publish.php';
							}
							?>
							<br />
						<td>	
					</tr>
				</table>
				</div>
			</td>

			<td width="170px" valign="top">
			<div style="margin: 5px; padding: 10px; border: 1px solid #cccccc;">
				<center><b>EDITOR KEY</b></center>
				<table border="0">
					<tr>
						<td width="16px" style="border: 1px solid #cccccc; padding: 2px; background-color: #f7f7f7;"></td><td style="padding-left: 5px;">move songs by drag 'n dropping grey areas</td>
					</tr>
					
					<tr>
						<td style="border: 1px solid #cccccc; padding: 2px;"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/track.gif" align="top" border="0"></td><td style="padding-left: 5px;"><fb:intl>play song</fb:intl></td>
					</tr>
					
					<tr>
						<td style="border: 1px solid #cccccc; padding: 2px;"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/key.png" align="top" border="0"></td><td style="padding-left: 5px;"><fb:intl>get api key</fb:intl></td>
					</tr>
					<tr>	
						<td style="border: 1px solid #cccccc; padding: 2px;"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/tag_blue_edit.png" align="top" border="0"></td><td style="padding-left: 5px;"><fb:intl>edit settings</fb:intl></td>
					</tr>
					<tr>	
						<td style="border: 1px solid #cccccc; padding: 2px;"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/delete.png" align="top" border="0"></td><td style="padding-left: 5px;"><fb:intl>delete song</fb:intl></td>

					</tr>
				</table>
				<center><b>Remember to save your changes!!!</b></center>
			</td>

		</tr>
	</table>
	<?php include 'inc.stats.php'; ?>
	</div>
<?php } elseif ($_GET['display'] == 'add') { ?>
	<?php 
	if($_GET['method'] == 'upload') 
	{
		include 'app.upload.php';
	}
	elseif ($_GET['method'] == 'link')
	{ 
		include 'app.link.php';
	}
	elseif ($_GET['method'] == 'youtube')
	{
		include 'app.youtube.php';
	}
	elseif ($_GET['method'] == NULL) 
	{ 
	?>
	
		<?php
		// $sr = new SuperRewardsAPI($config['sr']['key'], $config['sr']['secret']);
		// $sr->set_facebook($facebook);
		// $credit = $sr->users_getPoints($facebook->user);
		// $credit = $credit[0]['points'];
		// $check_ifexists = $db->Raw("SELECT COUNT(*) FROM `userdb_users` WHERE `user`='$user'");
		
		// if ($check_ifexists[0]['COUNT(*)'] == 0) $db->Raw("INSERT INTO `userdb_users` (`user`,`credit`,`override`) VALUES ('$user','$credit','0')");
		//	else $db->Raw("UPDATE `userdb_users` SET `credit`='$credit' WHERE `user`='$user'");
		?>
		
			<?php
			// checks how many credits the user has available
			// pulls it from the database and sets it to a variable
			// if it is a facebook page, it will also take the owner's available slots

			$credit = $db->Raw("SELECT `credit`,`override` FROM `userdb_users` WHERE `user`='$user'");
			$credit = $credit[0]['credit']+$credit[0]['override'];

			$usage = $db->Raw("SELECT COUNT(*) FROM `userdb_uploads` WHERE `user`='$user' AND `type`='upload'");
			$usage = $usage[0]['COUNT(*)'];

			if (isset($_GET['fb_page_id'])) 
			{
				$credit_of_owner = $db->Raw("SELECT `credit`,`override` FROM `userdb_users` WHERE `user`='$_POST[fb_sig_user]'");
				$credit = $credit + $credit_of_owner[0]['credit'] + $credit_of_owner[0]['override'];

				$usage_of_owner = $db->Raw("SELECT COUNT(*) FROM `userdb_uploads` WHERE `user`='$_POST[fb_sig_user]'");
				$usage = $usage + $usage_of_owner[0]['COUNT(*)'];
			}
			else
			{
				$users_pages = $db->Raw("SELECT `fb_page_id` FROM `pages` WHERE `owner`=$user");
				
				if (count($users_pages) !== 0) {
					foreach ($users_pages as $page_parse) 
					{
						$page_credit = $db->Raw("SELECT `credit`,`override` FROM `userdb_users` WHERE `user`='$page_parse[fb_page_id]'");
						$credit = $credit + $page_credit[0]['credit'] + $page_credit[0]['override'];
	
						$page_usage = $db->Raw("SELECT COUNT(*) FROM `userdb_uploads` WHERE `user`='$page_parse[fb_page_id]'");
						$usage = $usage + $page_usage[0]['COUNT(*)'];
					}
				}
			}
			?>
			
			<div style="margin: 10px">
			<table border="0" width="100%" cellspacing="5px">
				<tr>
				
					<td width="40%" valign="top">
						<div style="background-color: #fff5b1; border: 1px solid #ffd04d; padding: 10px; font-size: 16px; text-align: left;">Start at <b>searching Youtube</b> for what you want. If you can't find what you need, try <b>uploading the file</b>. More advanced users can also <b>add their own links</b> from other websites.</div>
					</td>
				
					<td width="60%">
						<table border="0" width="100%">
							<tr>

								<td>
								
								<table border="0" width="100%">
									<tr>
										<td>
											<font size="2em"><b>Search Youtube Database</b> (keyword)</font>
											
											<?php 
											if ($_GET['error'] == 'empty')
											{
												error('Nothing Submitted','We cannot continue until you give us a link to a file on the web.');
											}
											?>
											
										</td>
									</tr>
									
									<tr>
										<td>
											<fb:editor action="?tab=index&display=add&method=youtube&search<?php echo pages($_GET['fb_page_id']); ?>" labelwidth="0">
												<fb:editor-text label="Search" name="search" value="" />
												<fb:editor-buttonset>
													<fb:editor-button value="Search"/>
												</fb:editor-buttonset>
											</fb:editor>
										</td>
									</tr>
								</table>
								
								<table border="0" width="100%">
									<tr>
										<td>
											<table border="0" cellpadding="0" cellspacing="1">
												<tr>
													<td>
														<font size="2em"><b>Upload File&nbsp;</b></font>
													</td>
													
													<td>
														<font size="2em">(mp3, m4a, flv supported; max 20MBs)</font>
													</td>
												</tr>
												
												<tr>
													<td>
													</td>
													
													<td>
														<font size="2em"><u><?php echo $credit+2; ?></u> total slots, <u><?php echo ($credit+2)-$usage; ?></u> available for use, <b><a href="<?php echo $config['fb']['fburl']; ?>?tab=offers">get more here</a></b></font>
													</td>
												</tr>
											</table>
													
											<?php 
											if ($_GET['error'] == 'file_format')
											{
												error('Not an Acceptable File','You did not give us a file that we accept, you must upload a MP3, M4A, MP4, or AAC audio file.');
											}
											elseif ($_GET['error'] == 'no_file')
											{
												error('Nothing Uploaded','We cannot continue unless you give us an audio file.');
											}
											elseif ($_GET['error'] == 'file_size')
											{
												error('File Too Large','The file uploaded exceeds the maximum limit');
											}
											?>
											
										</td>
									</tr>
									
									<tr>
										<td>
												
												<?php $check_temporary = $db->Raw("SELECT COUNT(*) FROM `userdb_temporary` WHERE `user`='$user'"); ?>
												<?php $check_temporary = $check_temporary[0]['COUNT(*)']; ?>
												<?php if ($check_temporary >= 1) { ?>
													<?php 
													if(isset($_GET['fb_page_id'])) 
													{ 
														error("Incomplete","Looks like you forgot to finish an upload, would you like to continue?<br /><a href='" .  $config['fb']['fburl'] . "?tab=index&display=add&method=upload&step=3&fb_page_id=" . $_GET['fb_page_id'] . "'>Yes, continue!</a> - <a href='" . $config['fb']['fburl'] . "?tab=index&display=add&method=upload&step=reset&fb_page_id=" . $_GET['fb_page_id'] . "'>No, remove it.</a>"); 
													} else 
													{ 
														error("Incomplete","Looks like you forgot to finish an upload, would you like to continue? <a href='" . $config['fb']['fburl'] . "?tab=index&display=add&method=upload&step=3'>Yes, continue!</a> - <a href='" .$config['fb']['fburl'] . "?tab=index&display=add&method=upload&step=reset'>No, remove it.</a>"); 
													} 
													?>
												<?php } elseif ($credit+$config['basicSlots'] <= $usage) { ?>
													<?php error('Not enough slots!','You need more slots to use this feature! <a href="' . $config['fb']['fburl'] . '?tab=offers">Click here to get some!</a>'); // I want this an image overlaying the actual upload system ?>
												<?php } else { ?>
														<form name="form1" enctype="multipart/form-data" method="post" action="<?php echo $config['fb']['appcallbackurl']; ?>?tab=index&display=add&method=upload&step=2<?php echo pages($_GET['fb_page_id']); ?>&X-Progress-ID=<?php echo md5($user); ?>">
															<table class="editorkit" border="0" cellspacing="0" style="width:425px">
																<tr class="width_setter">
																	<th style="width:75px"></th>
																	<td></td>
																	</tr><tr>
																	<th><label>File:</label></th>
																	<td class="editorkit_row">
																		<input name="upfile" type="file" size="23" style="color: #003366; font-family: Verdana; font-weight: normal; font-size:11px">
																	</td>
																	<td class="right_padding"></td>
																</tr>
																<tr>
																	<th></th>
																	<td class="editorkit_buttonset">
																		<input name='upload' type='submit' id='upload' class="editorkit_button action" value='Upload' clickthrough="true" />
																	</td>
																	<td class="right_padding">
																		
																	</td>
																</tr>
															</table>
															<div style="margin-left: 200px; margin-top: -40px;">
															<fb:iframe src="<?php echo $config['fb']['appcallbackurl']; ?>uploadprogress.php?id=<?php echo md5($user); ?>" width="250" height="45" frameborder="0" scrolling="no"></fb:iframe></div>
														</form>
												<?php } ?>
												
										</td>
									</tr>
								</table>
								
								<br />
								
								<table border="0" width="100%">
									<tr>
										<td>
											<font size="2em"><b>Add External Link</b> (mp3, m4a, youtube supported)</font>
											
											<?php 
											if ($_GET['error'] == 'no_link_submitted')
											{
												error('Nothing Submitted','We cannot continue until you give us a link to a file on the web.');
											}
											elseif ($_GET['error'] == 'does_not_end_in_mp3')
											{
												error('Not an Audio File','You need to specify a link that leads to an audio file.');
											}
											elseif ($_GET['error'] == 'not_valid_link')
											{
												error('File Inexistant','The file you have specified does not exist, please check the link and try again!');
											}
											?>
											
										</td>
									</tr>
									
									<tr>
										<td>
											<fb:editor action="?tab=index&display=add&method=link&step=2<?php echo pages($_GET['fb_page_id']); ?>" labelwidth="0">
												<fb:editor-text label="Link" name="link" value="http://"/>
												<fb:editor-buttonset>
													<fb:editor-button value="Submit"/>
												</fb:editor-buttonset>
											</fb:editor>
										</td>
									</tr>
								</table>
								
								<br />
								
								
								
								<br />
								
								<td>
							</tr>
						</table>
					</td>



				</tr>
			</table>
			</div>

	<?php } ?>
<?php } ?>
