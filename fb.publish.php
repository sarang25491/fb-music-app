<?php

$pubData = $db->Raw("SELECT  `xid` ,  `title` ,  `artist`  FROM  `userdb_uploads` WHERE  `user`='$user' ORDER BY  `id` DESC LIMIT 1");

$attachment =  array(
      'name' => '' . htmlspecialchars_decode(utf8_decode($pubData[0]['title']), ENT_QUOTES) . ' by ' .  htmlspecialchars_decode(utf8_decode($pubData[0]['artist']), ENT_QUOTES) . '',
      'caption' => "Availability of this song depends on if it exists in {*actor*}'s playlist.",
      'media' => array(array('type' => 'flash',
                             'swfsrc' => '' . $config['fb']['appcallbackurl'] . 'flash/player/player.swf?file=' . $config['fb']['appcallbackurl'] . 'player.php?from_feed=' . $pubData[0]['xid'] . '&skin=' . $config['fb']['appcallbackurl'] . 'flash/skin/skewd.zip&autostart=true',
                             'imgsrc' => '' . $config['fb']['appcallbackurl'] . 'images/transparent_square.png',
                             'width' => '40', 
                             'height' => '31', 
                             'expanded_width' => '360', 
                             'expanded_height' => '30')));                     
?>

<script type="text/javascript">
var attachment = <?php echo json_encode($attachment); ?>;
Facebook.streamPublish('', attachment, null, <?php echo $user; ?>);	
</script>
