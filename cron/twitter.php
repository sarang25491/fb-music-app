<?php 
$pre = 'skip_fbapi';
include '../include/config.php';
include '../include/functions.php';

function object2array($data){
   if(!is_object($data) && !is_array($data)) return $data;

   if(is_object($data)) $data = get_object_vars($data);

   return array_map('object2array', $data);
}

include '../include/class.twitter.php';
$t = new Twitter;
$t->username = 'stevenlu';
$t->password = '$hamR0ck3m';

$timeline = $t->userTimeline();
$end = 0;
$i = 0;

$timeline = object2array($timeline);
print_r($timeline);
$file = fopen('../statics/twitter.txt','w');
fwrite($file,'<b>Twitter Announcements Timeline</b><br />');

foreach ($timeline as $tweet) {
	if (strpos(strtolower($tweet['text']), '#musicapp') > 0) { //strpos will return a position of the said string, if it doesnt find it it will return false
		$text = htmlspecialchars(utf8_encode(str_replace('#musicapp','',$tweet['text'])), ENT_QUOTES);
		
		if (count($db->Raw("SELECT `id` FROM `twitter` WHERE `id`='$tweet[id]'")) == 0)
			$db->Raw("INSERT INTO `twitter` (`id`,`time`,`message`) VALUES ('$tweet[id]','$tweet[created_at]','$text')");
			
		fwrite($file,'[<fb:time t="' . strtotime($tweet['created_at']) . '" />] ' . $text . '<br />');
	}
}

fclose($file);
?>
