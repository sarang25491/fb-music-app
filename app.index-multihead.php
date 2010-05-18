<div style="margin-bottom: -5px; padding: 5px; border-bottom: 1px solid #cccccc; background-color: #eceff5;">
<?php if(isset($_GET['fb_page_id'])) { ?>
	<?php 
	$pdata = $db->Raw("SELECT `name`,`status` FROM `pages` WHERE `fb_page_id`='$_GET[fb_page_id]'");
	
	if ($pdata[0]['name'] == '') {
		$pdata = $facebook->api_client->fql_query("SELECT name FROM page WHERE page_id='$_GET[fb_page_id]'");
		$parse['name'] = $pname[0]['name'];
		$pname = htmlspecialchars(utf8_encode($parse['name']));
		$db->Raw("UPDATE `pages` SET `name`='$pname' WHERE `fb_page_id`='$_GET[fb_page_id]'");
	}
	?>
		
	You are currently editing page, <b><?php echo $pdata[0]['name']; ?></b>, change back to your <b><a href="<?php echo $config['fb']['fburl'] ?>">profile</a></b>?
<?php } else { ?>
	<?php 
	$pdata = $db->Raw("SELECT `fb_page_id`,`name` FROM `pages` WHERE `owner`='$user'");
	if(count($pdata) > 0) {
		$count = 0;
		foreach ($pdata as $parse) {
			$count = $count+1;
			if($count !== 1 AND $count == count($pdata)) $page_string='' . $page_string . 'or ';
			if($parse['name'] == '') {
				$pname = $facebook->api_client->fql_query("SELECT name FROM page WHERE page_id='$parse[fb_page_id]'");
				$parse['name'] = $pname[0]['name'];
				$pname = htmlspecialchars(utf8_encode($parse['name']));
				
				$db->Raw("UPDATE `pages` SET `name`='$pname' WHERE `fb_page_id`='$parse[fb_page_id]'");
			}
			$page_string = '' . $page_string . '<b><a href="' . $config['fb']['fburl'] . '?fb_page_id=' . $parse['fb_page_id'] . '">' . $parse['name'] . '</a></b>';
			$page_string='' . $page_string . ', ';
		} 
		
		$page_string = substr_replace($page_string,"",-2);
		?>
		
		You are currently editing your <b>profile</b>, change to page <?php echo $page_string; ?>? <b><a href="http://www.facebook.com/add.php?api_key=<?php echo $config['fb']['key']; ?>&pages&_fb_q=1">Add more</a></b>?
	<?php 
	} else {
	?>
		You are currently editing your <b>profile</b>. No other players or pages have been detected, <b><a href="http://www.facebook.com/add.php?api_key=<?php echo $config['fb']['key']; ?>&pages&_fb_q=1">add a page</a></b>.
	<?php } ?>
<?php } ?>
</div>