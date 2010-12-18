<?php
$pre = 'skip_fbapi'; include 'include/config.php';
?>

<fb:swf 
		swfsrc="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf"
		flashvars="player=v5&file=http://www.youtube.com/watch?v=<?php echo $_GET['vid']; ?>&provider=youtube&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/skewd.zip&autostart=1&volume=75"
		height="360" 
		width="420" 
/>