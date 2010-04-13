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
$file = fopen('../statics/twitter.txt','w');
fwrite($file,'<b>Twitter Announcements Timeline</b><br />');

while ($end == 0) {
	$currentLine = addslashes_deep($timeline[$i]['text']);
	if (strpos(strtolower($currentLine), '#musicapp') === false) {
		echo 'nothing found';
	} else {
		$currentLine = str_replace('#musicapp','',$currentLine);
		$db->Raw("UPDATE `system` SET `data`='$currentLine' WHERE `var`='twitter'");
		$end = 1;
	}
	$i++;
}

foreach ($timeline as $tweet) {
	if (strpos(strtolower($tweet['text']), '#musicapp') > 0) { //strpos will return a position of the said string, if it doesnt find it it will return false
		fwrite($file,'[<fb:time t="' . strtotime($tweet['created_at']) . '" />] ' . str_replace('#musicapp','',$tweet['text']) . '<br />');
	}
}

fclose($file);
?>
