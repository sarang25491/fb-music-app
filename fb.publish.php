<?php

$pubData = $db->Raw("SELECT  `xid` , `type`,  `title` ,  `artist` ,  `filesize` ,  `playtime` FROM  `userdb_uploads` WHERE  `user`='$user' ORDER BY  `id` DESC LIMIT 1");

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

$message = 'has added a song to their profile.';
$attachment =  array(
      'name' => htmlspecialchars_decode(utf8_decode($pubData[0]['title']), ENT_QUOTES),
      'href' => 'http://apps.burst-dev.com/music/player.php?upload=' . $pubData[0]['xid'] . '&from_share=1&challenge=' . $user. '&autostart=true',
      'caption' => '{*actor*} added a song to their profile.',
      'properties' => $properties,
      'media' => array(array('type' => 'flash',
                             'swfsrc' => '' . $config['fb']['appcallbackurl'] . 'flash/player/player.swf?file=' . $config['fb']['appcallbackurl'] . 'player.php?from_feed=' . $pubData[0]['xid'] . '&skin=' . $config['fb']['appcallbackurl'] . 'flash/skin/skewd.zip&backcolor=#3b5998&lightcolor=#ffffff&frontcolor=#f7f7f7&autostart=true',
                             'imgsrc' => '' . $config['fb']['appcallbackurl'] . 'images/play.jpg',
                             'width' => '100', 
                             'height' => '80', 
                             'expanded_width' => '330', 
                             'expanded_height' => '30')));                     
?>

<script type="text/javascript">
var attachment = <?php echo json_encode($attachment); ?>;
Facebook.streamPublish('', attachment, null, <?php echo $user; ?>);	
</script>