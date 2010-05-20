<?php if ($_GET['step'] == 2) { ?>
	<?php $link = $_POST['link']; ?>
	
	<?php
	$link = str_replace(" ", "", $link);
	
	function link_available($url, $timeout = 30) {
        $ch = curl_init(); // get cURL handle

        // set cURL options
        $opts = array(CURLOPT_RETURNTRANSFER => true, // do not output to browser
                                  CURLOPT_URL => $url,            // set URL
                                  CURLOPT_NOBODY => true,                 // do a HEAD request only
                                  CURLOPT_TIMEOUT => $timeout);   // set timeout
        curl_setopt_array($ch, $opts); 

        curl_exec($ch); // do it!

        $retval = curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200; // check if HTTP OK

        curl_close($ch); // close handle

        return $retval;
	}
	?>
	
	<?php if(isset($_GET['error'])) { ?>
		<?php if ($_GET['error'] == 'missing_information') { ?>
			<?php error('Not Enough Information','I need ALL of the boxes filled below.'); ?>
		<?php } ?>
	<?php } elseif (strpos($link, 'youtube.com') === false) { ?>
		<?php if ($link == NULL || $link == 'http://') { ?>
			<?php if(isset($_GET['fb_page_id'])) { redirect('index.php?tab=index&display=add&error=no_link_submitted&fb_page_id=' . $_GET['fb_page_id'] . ''); } else { redirect('index.php?tab=index&display=add&error=no_link_submitted'); } ?>
		<?php } elseif (!in_array(strtolower(end(explode('.',$link))), array('mp3','m4a','mp4','aac','flv'))) { ?>
			<?php if(isset($_GET['fb_page_id'])) { redirect('index.php?tab=index&display=add&error=does_not_end_in_mp3&fb_page_id=' . $_GET['fb_page_id'] . ''); } else { redirect('index.php?tab=index&display=add&error=does_not_end_in_mp3'); } ?>
		<?php } elseif (!link_available($link)) { ?>
			<?php if(isset($_GET['fb_page_id'])) { redirect('index.php?tab=index&display=add&error=not_valid_link &fb_page_id=' . $_GET['fb_page_id'] . ''); } else { redirect('index.php?tab=index&display=add&error=not_valid_link'); } ?>
		<?php } ?>
		<?php explanation('Input Song Information','I need some information about the song before we can continue.'); ?>
	<?php } ?>
	
	<fb:editor action="?tab=index&display=add&method=link&step=3<?php if(isset($_GET['fb_page_id'])) { echo '&fb_page_id=' . $_GET['fb_page_id'] . ''; } ?>" labelwidth="0">
		<fb:editor-text label="Title" name="title" value="" maxlength="100" />
		<fb:editor-text label="Artist" name="artist" value="" maxlength="100" />
		<fb:editor-custom>
			<input type="hidden" name="link" value="<?php echo $link; ?>">
		</fb:editor-custom>
		<fb:editor-buttonset>
			<fb:editor-button value="Submit"/>
		</fb:editor-buttonset>
	</fb:editor>
<?php } elseif ($_GET['step'] == 3) { ?>
	<?php $title = htmlspecialchars(utf8_encode($_POST['title']), ENT_QUOTES); $artist = htmlspecialchars(utf8_encode($_POST['artist']), ENT_QUOTES); $link = $_POST['link']; ?>
	<?php if($title == NULL or $artist == NULL) { ?>
		<?php if(isset($_GET['fb_page_id'])) { redirect('' . $config['fb']['fburl'] . '?tab=index&display=add&method=link&step=2&error=missing_information&fb_page_id=' . $_GET['fb_page_id'] . ''); } else { redirect('' . $config['fb']['fburl'] . '?tab=index&display=add&method=link&step=2&error=missing_information'); } ?>
	<?php } else { ?>
		<?php $db->Raw("INSERT INTO `userdb_uploads` (`user`,`title`,`artist`,`type`,`link`) VALUES ('$user','$title','$artist','link','$link')"); ?>
		<?php
		//need to get a STATIC XID from id
		$id = $db->Raw("SELECT `id` FROM `userdb_uploads` WHERE `user`='$user' ORDER BY `id` DESC LIMIT 1");
		$id = $id[0]['id'];
		$db->Raw("UPDATE `userdb_uploads` SET `xid`=`id` WHERE `id`='$id'");
		?>

		<?php // if(!isset($_GET['fb_page_id'])) { include 'fb.feed.php'; } ?>
		<?php include 'fb.profile.php'; ?>
		<?php if(isset($_GET['fb_page_id'])) { redirect('' . $config['fb']['fburl'] . '?tab=index&fb_page_id=' . $_GET['fb_page_id'] . ''); } else { redirect('' . $config['fb']['fburl'] . '?tab=index&publish'); } ?>
	<?php } ?>
<?php } ?>
	