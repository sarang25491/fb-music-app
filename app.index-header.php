<div style="margin-bottom: -5px; padding: 5px; border-bottom: 1px solid #cccccc; background-color: #eceff5;">
<?php if(isset($_GET['fb_page_id'])) { ?>
	<?php echo translate('You are currently editing'); ?> <b><fb:name uid="<?php echo $_GET[fb_page_id]; ?>" />'s <?php echo translate('playlist'); ?></b>, <?php echo translate('change back to your'); ?> <b><a href="<?php echo $config['fb']['fburl'] ?>"><?php echo translate('profile playlist'); ?></a></b>?
<?php } else { ?>
	<?php 
	$pdata = json_decode($_POST['fb_sig_page_id']);
	if(count($pdata) > 0) {
		$count = 0;
		foreach ($pdata as $parse) {
         // don't ask me, this is just how FQL works :/
         $page_id = $parse[0];
         $page_name = $parse[1];

			$count = $count+1;
			if($count !== 1 AND $count == count($pdata)) $page_string='' . $page_string . 'or ';
			$page_string = '' . $page_string . '<b><a href="' . $config['fb']['fburl'] . '?fb_page_id=' . $page_id . '"><fb:name uid="' . $page_id . '" /></a></b>, ';
		} 
		
		$page_string = substr_replace($page_string,"",-2);
		?>
		
		<?php echo translate('You are currently editing your <b>profile playlist</b>, change playlist to'); ?> <?php echo $page_string; ?>? <b><a href="http://www.facebook.com/add.php?api_key=<?php echo $config['fb']['key']; ?>&pages&_fb_q=1"><?php echo translate('Add more'); ?></a></b>?
	<?php 
	} else {
	?>
		<?php echo translate('You are currently editing your <b>profile playlist</b>. No other playlists have been found,'); ?> <b><a href="http://www.facebook.com/add.php?api_key=<?php echo $config['fb']['key']; ?>&pages&_fb_q=1"><?php echo translate('add a page'); ?></a> <?php echo translate('playlist'); ?></b>?
	<?php } ?>
<?php } ?>
</div>
