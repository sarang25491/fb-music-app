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
    $query->setMaxResults(5);
    
	$feed = $youTubeService->getVideoFeed($query);
	
	echo '	<form id="dummy_form"></form>
			<div id="player" style="padding-bottom: 0px;" align="center"></div>';
	
	echo '<fb:editor action="?tab=index&display=add&method=youtube&confirm" labelwidth="0">';
		echo '<fb:editor-custom label="Selection">';
		echo '<table border="0" cellpadding="0" cellspacing="0" width="500">';
		
		$count = 0;
		foreach ($feed as $entry) {
			
			echo '<tr><td width="5%" valign="top">';
			echo '<input type="radio" name="id" value="' . $entry->getVideoId() .'">';
			echo '</td><td width="60%" valign="top">';
			echo '' . $entry->getVideoTitle() . '';
			echo '</td><td width="35%" valign="top">';
			echo '(<a href="http://www.youtube.com/watch?v=' . $entry->getVideoId() . '" target="_blank">preview</a>)';
			echo '</td></tr>';
			
			$count++;
			
		}
		
		echo '</table>';
		echo '</fb:editor-custom>';
		echo '<fb:editor-buttonset><fb:editor-button value="Submit"></fb:editor-buttonset>';
	echo '</fb:editor>';
} elseif (isset($_GET['confirm'])) {
	
	echo '<fb:editor action="?tab=index&display=add&method=youtube&submit" labelwidth="0">
	<center><b><a href="http://www.youtube.com/watch?v=' . $_POST['id'] . '">http://www.youtube.com/watch?v=' . $_POST['id'] . '</a></b></center>
	<fb:editor-text label="Title" name="title" value="" maxlength="100" />
	<fb:editor-text label="Artist" name="artist" value="" maxlength="100" />
	<fb:editor-custom>
		<input type="hidden" name="link" value="http://www.youtube.com/watch?v=' . $_POST['id'] . '">
	</fb:editor-custom>
	<fb:editor-buttonset>
		<fb:editor-button value="Submit"/>
	</fb:editor-buttonset>
	</fb:editor>';

} elseif (isset($_GET['submit'])) {
	$title = htmlspecialchars(utf8_encode($_POST['title']), ENT_QUOTES); $artist = htmlspecialchars(utf8_encode($_POST['artist']), ENT_QUOTES);
	$db->Raw("INSERT INTO `userdb_uploads` (`user`,`title`,`artist`,`type`,`link`) VALUES ('$user','$title','$artist','link','$_POST[link]')");
	
	//need to get a STATIC XID from id
	$id = $db->Raw("SELECT `id` FROM `userdb_uploads` WHERE `user`='$user' ORDER BY `id` DESC LIMIT 1");
	$id = $id[0]['id'];
	$xid = md5($id);
	$db->Raw("UPDATE `userdb_uploads` SET `xid`='$xid' WHERE `id`='$id'");
	
	include 'fb.profile.php';
	if(isset($_GET['fb_page_id'])) { redirect('' . $config['fb']['fburl'] . '?tab=index&fb_page_id=' . $_GET['fb_page_id'] . ''); } else { redirect('' . $config['fb']['fburl'] . '?tab=index&publish'); } 
}
?>