<?php
$pre = 'skip_fbapi'; include 'include/config.php';
?>

<fb:swf 
		swfsrc="<?php echo $config['fb']['appcallbackurl']; ?>flash/player/player.swf" 
		flashvars="file=http://www.youtube.com/watch?v=<?php echo $_GET['vid']; ?>&autostart=true&fullscreen=false&skin=<?php echo $config['fb']['appcallbackurl']; ?>flash/skin/stylish.swf&backcolor=#3b5998&lightcolor=#ffffff&frontcolor=#f7f7f7&abouttext=0&aboutlink=0&volume=75&bufferlength=1&provider=youtube"
		quality="high" 
		height="360" 
		width="420" 
/>