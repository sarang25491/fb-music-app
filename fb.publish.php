<?php
include_once('include/config.php');

$pubData = $db->Raw("SELECT  `xid` ,  `title` ,  `artist`  FROM  `userdb_uploads` WHERE  `user`='$user' ORDER BY  `id` DESC LIMIT 1");

$attachment =  array(
      'name' => '' . htmlspecialchars_decode(utf8_decode($pubData[0]['title']), ENT_QUOTES) . ' by ' .  htmlspecialchars_decode(utf8_decode($pubData[0]['artist']), ENT_QUOTES) . '',
      'caption' => "Availability of this song depends on if it exists in {*actor*}'s playlist.",
      'media' => array(array('type' => 'flash',
                             'swfsrc' => '' . $config['fb']['appcallbackurl'] . 'flash/player/player.swf?plugins=' . $config['fb']['appcallbackurl'] . 'flash/plugins/burstplugin.swf&burstplugin.xid=' . $pubData[0]['xid'] . '&skin=' . $config['fb']['appcallbackurl'] . 'flash/skin/skewd.zip&autostart=true',
                             'imgsrc' => '' . $config['fb']['appcallbackurl'] . 'img/transparent_square.png',
                             'width' => '40', 
                             'height' => '32', 
                             'expanded_width' => '360', 
                             'expanded_height' => '28')));                     
?>

<script type="text/javascript">
var attachment = <?php echo json_encode($attachment); ?>;
Facebook.streamPublish('', attachment, [], <?php echo $user; ?>);	
</script>
