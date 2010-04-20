<?php 
$pre = 'skip_fbapi';
include 'include/config.php';

if ($_POST['method'] == 'publisher_getInterface') {
	$i=0;
	if (isset($_POST['fb_sig_profile_id']))
		$id = $_POST['fb_sig_profile_id'];
	else
		$id = $_POST['fb_sig_user'];
		
	$uploads = $db->Raw("SELECT `xid`,`title`,`artist` FROM `userdb_uploads` WHERE `user`='$id' ORDER BY `id`");
	$uploads_count = count($uploads);
	$total_count = $uploads_count;
			
	if ($total_count == '0') { 
		$fbml = '<center>No Music! Click <a href="' . $fb_url . '>here</a> to add some!"</center>';
	} elseif ($total_count > '0') { 
	
		$fbml = '
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		'; 
	
		foreach($uploads as $display) {
			$i+=1; 
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
							<div style="padding-right: 5px; padding-left: 5px;"><input type="radio" name="xid" value="' . $display['xid'] .'"></div>
						</td>
						<td valign="center" width="95%">
							' . htmlspecialchars_decode(utf8_decode($display['title']), ENT_QUOTES) . ' by ' . htmlspecialchars_decode(utf8_decode($display['artist']), ENT_QUOTES) . '
						</td>
					</tr>
				</table>
				
				</div>
				</center>
			</td>
			</tr>
			';
		}
			
		$fbml='' . $fbml . '
		</table>
		</center>
		';
	}
	
	echo json_encode(array(content => array(fbml => $fbml, publishEnabled => 'true'), method => 'publisher_getInterface'));
} elseif ($_POST['method'] == 'publisher_getFeedStory') {
	$xid = $_POST['app_params']['xid'];
	$pubData = $db->Raw("SELECT  `xid` , `type`,  `title` ,  `artist` ,  `filesize` ,  `playtime` FROM  `userdb_uploads` WHERE `xid`='$xid'");

	// converting from bytes to megabytes rounding to second decimal
	$filesize = round($pubData[0]['filesize']/1000000, 2);
	$durMin = floor($pubData[0]['playtime']/60);
	$durSec = $pubData[0]['playtime'] % 60;
	
	if($pubData[0]['type'] == 'upload') {
	
		$filesize = round($pubData[0]['filesize']/1000000, 2);
		$durMin = floor($pubData[0]['playtime']/60);
		$durSec = $pubData[0]['playtime'] % 60;
		
		$properties = array(		'Artist' => htmlspecialchars_decode(utf8_decode($pubData[0]['artist']), ENT_QUOTES),
		      						'Filesize' => '' . $filesize . 'M',
		      						'Duration' => '' . $durMin . 'min ' . $durSec . 'sec');
	} else {
		$properties = array(	'Artist' => $pubData[0]['artist'],
		      					'From' => 'External Source');
	}
	
	$attachment =  array(
	      'name' => htmlspecialchars_decode(utf8_decode($pubData[0]['title']), ENT_QUOTES),
	      'href' => 'http://apps.burst-dev.com/music/player.php?upload=' . $pubData[0]['xid'] . '&from_share=1&challenge=' . $user. '&autostart=true',
	      'caption' => '{*actor*} attached a song.',
	      'properties' => $properties,
	      'media' => array(array('type' => 'flash',
	                             'swfsrc' => '' . $config['fb']['appcallbackurl'] . 'flash/player/player.swf?file=' . $config['fb']['appcallbackurl'] . 'player.php?from_feed=' . $pubData[0]['xid'] . '&skin=' . $config['fb']['appcallbackurl'] . 'flash/skin/stylish.swf&backcolor=#3b5998&lightcolor=#ffffff&frontcolor=#f7f7f7&autostart=true',
	                             'imgsrc' => '' . $config['fb']['appcallbackurl'] . 'images/play.jpg',
	                             'width' => '100', 
	                             'height' => '80', 
	                             'expanded_width' => '330', 
	                             'expanded_height' => '30')));
	                             
	echo json_encode(array(content => array(attachment => $attachment), method => 'publisher_getFeedStory'));
}
?>