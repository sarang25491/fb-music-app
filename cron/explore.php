<?php
$pre = 'skip_login';
include '../include/facebook/facebook.php';
include '../include/config.php';
include '../include/class.phpmailer.php';

system('rm -rf ' . $config['server']['internal_url'] . 'statics/explore/*');

$page_ids = $db->Raw("SELECT `fb_page_id` FROM `pages` WHERE `status`='2' ORDER BY RAND()"); // grabbing all IDs with proper admin auth

foreach ($page_ids as $ids) $page_ids_cs = "" . $page_ids_cs . "'" . $ids['fb_page_id'] . "',"; // transverse all IDs to create a SQL proper string
$page_ids_cs = substr_replace($page_ids_cs,"",-1); // removes the last comma of the string

//retrives all data from database of ALL pages
$page_uploads = $db->Raw("SELECT `id`,`user`,`title`,`artist`,`link` FROM `userdb_uploads` WHERE `user` IN ($page_ids_cs) ORDER BY `user` DESC");

$items_per_page = 10; // user defined variable
$page_number = 0; // this will determine the page number for pagination
$item_transversal = 0; // variable initialization

foreach ($page_ids as $transverse) {
	$item_transversal++;
	$item_count++;
	if ($item_transversal == 1) {
		$page_number++;
		$file = fopen('../statics/explore/' . $page_number . '.txt','w'); // this will open the file and attempt to create the file if it does not exist.
	}
	
	$fbml = '<div style="margin: 20px;">
	<table style="margin-bottom: 10px;" width="100%">
		<tr>
			<td>
				<table border="0" width="100%">
					<tr>
						<td width="40%" style="padding-bottom: 5px;">
							<center><span style="padding-bottom: 25px; color: #aaaaaa; font-size: 16px;">Music from: <fb:name uid="' . $transverse['fb_page_id'] . '" capitalize="true" ifcantsee="Anonymous" /></span></center>
						</td>
						<td width="60%">
							<form id="dummy_form_' . $transverse['fb_page_id'] . '"></form>
							<div id="player_' . $transverse['fb_page_id'] . '" style="padding-right: 5px;" align="right">
							<img src="' . $config['fb']['appcallbackurl'] . 'images/spinner.gif" id="spinner_' . $transverse['fb_page_id'] . '" style="display:none; padding-bottom: 5px;"/>
							</div>
						</td>
					</tr>
				</table>';
	
	$fcount = 0;
	$verify_songs = '';
	foreach($page_uploads as $uploads)
	{
		if($uploads['user'] == $transverse['fb_page_id']) $fcount++;
	}
	
	$fbml = '' . $fbml . '<div style="border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;"><table border="0" width="100%" cellpadding="0" cellspacing="0">';
	$dcount = 1;
	
	foreach($page_uploads as $display)
	{
		if($display['user'] == $transverse['fb_page_id']) 
		{
			$verify_songs = '' . $verify_songs . '' . $display['title'] . ' by ' . $display['artist'] . '
			';
			
			$fbml = '' . $fbml . ' 
				<tr>
					<td>
						<center>';
			
			if($dcount == 1) 
			{
				$fbml = '' . $fbml . '<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc; background-color: #F7F7F7; padding: 1px;">';
			} else {
				$fbml = '' . $fbml . '<div style="border-bottom: 1px solid #cccccc; background-color: #F7F7F7; padding: 1px;">';
			}
			
			$fbml = '' . $fbml . '
			<table border="0" width="100%">
				<tr>
					<td valign="center" width="5%">
						<div style="padding-right: 5px; padding-left: 5px;"><a clickrewriteurl="' . $config['fb']['appcallbackurl'] . 'player.php?upload=' . $display['id'] . '&from_explore=1" clickrewriteid="player_' . $transverse['fb_page_id'] . '" clickrewriteform="dummy_form_' . $transverse['fb_page_id'] . '" clicktoshow="spinner_' . $transverse['fb_page_id'] . '"><img src="' . $config['fb']['appcallbackurl'] . 'images/track.gif" align="top" border="0"></a></div>
					</td>
					<td valign="center" width="95%">
						<a clickrewriteurl="' . $config['fb']['appcallbackurl'] . 'player.php?upload=' . $display['id'] . '&from_explore=1" clickrewriteid="player_' . $transverse['fb_page_id'] . '" clickrewriteform="dummy_form_' . $transverse['fb_page_id'] . '" clicktoshow="spinner">' . $display['title'] . ' by ' . $display['artist']. '</a>
					</td>
				</tr>
			</table>
			</div>
			</td>
			</tr>';
			$dcount++;
		}
		
	}
	
	$fbml = '' . $fbml . '</table></div></td>
</tr>
</table>
</div>';
	if ($fcount !== 0) {
		fwrite($file, $fbml);
		echo "writing\n";
	} else {
		if($item_transversal == 1) {
			$item_transversal = 0;
			$page_number--;
		} else
			$item_transversal--;
	}	
		
	if ($item_transversal == $items_per_page) {
		fclose($file);
		$item_transversal = 0;
		echo $page_number;
		echo "closing file\n";
	}
}
try {
	fclose($file); // this will trunciate if there the item_transversal did not meet the items_per_page.
} catch (Exception $e) {
}

$db->Raw("UPDATE `system` SET `data`='$page_number' WHERE `var`='number_of_pages'");
?>
