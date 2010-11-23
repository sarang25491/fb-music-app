<?php

/* YOUTUBE SEARCH
 * Author: Steven Lu
 * Property of Burst Development, any disclosure outside is prohibited.
 *
 * DEPENDENCIES: PHP 5.2.4+, ZEND FRAMEWORK, GDATA API
 *
 * This script expects an operation variable from the URI.
 * This can include: search, confirm, submit
 *
 */

if(isset($_GET['search'])) {
	/*
	 * Loading the Zend GDATA API.
	 */
	require_once 'Zend/Loader.php';
	Zend_Loader::loadClass('Zend_Gdata_YouTube');
	Zend_Loader::loadClass('Zend_Gdata_AuthSub');
	Zend_Loader::loadClass('Zend_Gdata_App_Exception');
	
	$youTubeService = new Zend_Gdata_YouTube();
    $query = $youTubeService->newVideoQuery();
    $query->setQuery($_POST['search']);
    $query->setStartIndex(0);
    $query->setMaxResults(9);
    
	$feed = $youTubeService->getVideoFeed($query);
	
	/* Grabs the data received from Gdata and converts it into a nice array
	for people like me to work with. Its just so I handle the data better.
	It basically puts in the title of the video, the ID of the video,
	the link to the video and the URL of the largest thumbnail possible */
	$i = 0;
	$youtubeData = array();
	foreach($feed as $entry) {
		$youtubeData[$i]['title'] = $entry->getVideoTitle();
		$youtubeData[$i]['id'] = $entry->getVideoId();
		$youtubeData[$i]['video'] = $entry->getFlashPlayerUrl();
		$thumbnail = $entry->getVideoThumbnails();
		$youtubeData[$i]['img'] = $thumbnail[2]['url'];
		$i++;
	} //end data parsing
	echo '	<fb:dialog id="preview">
				<fb:dialog-title>Youtube Preview</fb:dialog-title>
				<fb:dialog-content>
					<form id="dummy_form"></form>
					<div id="player" align="center"></div>
				</fb:dialog-content>
				<fb:dialog-button type="button" value="Close" close_dialog=1 /> 
			</fb:dialog>';
	
	echo '	<div style="padding-top: 10px">
			<fb:editor action="?tab=index&display=add&method=youtube&search' . pages($_GET['fb_page_id']) . '" labelwidth="0">
				<fb:editor-text label="Search" name="search" value="' . $_POST['search'] . '"/>
				<fb:editor-buttonset>
				<fb:editor-button value="Search"/>
				</fb:editor-buttonset>
			</fb:editor>
			</div>';
	
	$tr = 0;
	echo '<div align="center" style="padding-top: 5px; padding-bottom: 10px;">';
	echo '<table border="0" cellspacing="5" cellpadding="0" width="700px">';
	foreach ($youtubeData as $entry) {
		if ($tr == 0)
			echo '<tr>';
		
		echo '<td width="33%" style="padding-top: 10px; border: 1px solid #cccccc">';
		echo '<fb:editor action="?tab=index&display=add&method=youtube&confirm&videoId=' . $entry['id'] . '' . pages($_GET['fb_page_id']) . '" labelwidth="0" width="10">';
		echo '<fb:editor-custom><input type="hidden" name="title" value="' . $entry['title'] . '"></fb:editor-custom>';
		echo '<center><img src="' . $entry['img'] . '" /><br />' . $entry['title'] . '<br /></center>';
		echo '<fb:editor-buttonset><fb:editor-button value="Select"></fb:editor-buttonset><div style="padding-left: 97px; padding-top: 5px; margin-bottom: -20px;"><a clickrewriteurl="' . $config['fb']['appcallbackurl'] . 'app.youtube-callback.php?vid=' . $entry['id'] . '" clickrewriteid="player" clickrewriteform="dummy_form" clicktoshowdialog="preview">preview</a></div>';
		echo '</fb:editor>';

		echo '</td>';
		
		$tr++;
		
		if ($tr == 3) {
			echo '</tr>';
			$tr = 0;
		}
	}
	echo '</table>';
	echo '</div>';
} elseif (isset($_GET['confirm'])) {
	
	list($artist,$title) = split(' - ', $_POST['title']);
	
	echo '<div style="padding-top: 15px;">
	<fb:editor action="?tab=index&display=add&method=youtube&submit' . pages($_GET['fb_page_id']) . '" labelwidth="50">
	<div align="center" style="font-size: 10pt;">
	<b>Youtube Link: </b><a href="http://www.youtube.com/watch?v=' . $_GET['videoId'] . '">http://www.youtube.com/watch?v=' . $_GET['videoId'] . '</a><br />
	<b>Youtube Title: </b>' . $_POST['title'] . '
	</div>
	<fb:editor-text label="Title" name="title" value="' . $title  . '" maxlength="100" />
	<fb:editor-text label="Artist" name="artist" value="' . $artist . '" maxlength="100" />
	
	<fb:editor-custom label="Post to Wall?">
		<input type="checkbox" name="wall" value="true">
	</fb:editor-custom>

	<fb:editor-custom>
		<input type="hidden" name="link" value="http://www.youtube.com/watch?v=' . $_GET['videoId'] . '">
	</fb:editor-custom>
	<fb:editor-buttonset>
		<fb:editor-button value="Submit"/>
	</fb:editor-buttonset>
	</fb:editor>
	</div>';

} elseif (isset($_GET['submit'])) {
	$title = htmlspecialchars(utf8_encode($_POST['title']), ENT_QUOTES); $artist = htmlspecialchars(utf8_encode($_POST['artist']), ENT_QUOTES);
	$db->Raw("INSERT INTO `userdb_uploads` (`user`,`title`,`artist`,`type`,`link`) VALUES ('$user','$title','$artist','link','$_POST[link]')");
	
	//need to get a STATIC XID from id
	$id = $db->Raw("SELECT `id` FROM `userdb_uploads` WHERE `user`='$user' ORDER BY `id` DESC LIMIT 1");
	$id = $id[0]['id'];
	$db->Raw("UPDATE `userdb_uploads` SET `xid`=`id` WHERE `id`='$id'");

	if($_POST['wall'])
		if(isset($_GET['fb_page_id'])) { redirect('' . $config['fb']['fburl'] . '?tab=index&publish&fb_page_id=' . $_GET['fb_page_id'] . ''); } else { redirect('' . $config['fb']['fburl'] . '?tab=index&publish'); }
	else
		if(isset($_GET['fb_page_id'])) { redirect('' . $config['fb']['fburl'] . '?tab=index&fb_page_id=' . $_GET['fb_page_id'] . ''); } else { redirect('' . $config['fb']['fburl'] . '?tab=index'); }
 
}
?>
