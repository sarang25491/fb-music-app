<div style="margin-bottom: -5px; padding: 5px; border-bottom: 1px solid #cccccc; background-color: #eceff5;">
<?php if(isset($_GET['fb_page_id'])) { ?>
	You are currently editing <b><fb:name uid="<?php echo $_GET[fb_page_id]; ?>" />'s playlist</b>, change back to your <b><a href="<?php echo $config['fb']['fburl'] ?>">profile playlist</a></b>?
<?php } else { ?>
	<?php 
	$pdata = $db->Raw("SELECT `fb_page_id` FROM `pages` WHERE `owner`='$user'");
	if(count($pdata) > 0) {
		$count = 0;
		foreach ($pdata as $parse) {
			$count = $count+1;
			if($count !== 1 AND $count == count($pdata)) $page_string='' . $page_string . 'or ';
			$page_string = '' . $page_string . '<b><a href="' . $config['fb']['fburl'] . '?fb_page_id=' . $parse['fb_page_id'] . '"><fb:name uid="' . $parse['fb_page_id'] . '" /></a></b>, ';
		} 
		
		$page_string = substr_replace($page_string,"",-2);
		?>
		
		You are currently editing your <b>profile playlist</b>, change playlist to <?php echo $page_string; ?>? <b><a href="http://www.facebook.com/add.php?api_key=<?php echo $config['fb']['key']; ?>&pages&_fb_q=1">Add more</a></b>?
	<?php 
	} else {
	?>
		You are currently editing your <b>profile playlist</b>. No other playlists have been found, <b><a href="http://www.facebook.com/add.php?api_key=<?php echo $config['fb']['key']; ?>&pages&_fb_q=1">add a page</a> playlist</b>?
	<?php } ?>
<?php } ?>
</div>