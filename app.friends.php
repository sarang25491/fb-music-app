<?php 

/*
Queries Facebook to get all the user's friends who have added the application.
It then asks Facebook for their name and their small profile photo.
*/

//$user_friends = $facebook->api_client->friends_getAppUsers();
$user_friends_info = $facebook->api_client->fql_query("SELECT uid,name,pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1='" . $user . "') AND is_app_user");
//$user_friends_info = $facebook->api_client->users_getInfo($user_friends, array('name','uid','pic_square'));


if (count($user_friends_info) <= 1) {
	error('You do not have any friends using this application!','Invite some and you might be able to see some music that they like!');
} else {
	/*
	Since MySQL cannot take array format to search,
	we need to put it in comma seperated form.
	*/
	
	foreach($user_friends_info as $ids) $user_friends_ids = "" . $user_friends_ids . "'" . $ids['uid'] . "',";
	$user_friends_ids = "" . $user_friends_ids . "'2436915755'";

	// $user_friends_with_songs = $db->Raw("SELECT userdb_uploads.user, userdb_links.user FROM `userdb_uploads`, `userdb_links` WHERE `user` IN (" . $user_friends_ids . ") GROUP BY `user`");

	/*
	Takes the proper SQL syntax IDs from Facebook and queries it to the database.
	This will grab all of the information and place it into a nice array.
	*/
	
	$user_friends_uploads = $db->Raw("SELECT * FROM `userdb_uploads` WHERE `user` IN (" . $user_friends_ids . ") ORDER BY `user`,`order`,`id` ASC");
	
	/*
	Puts the data from SQL format to more user-friendly format.
	*/

	$user_friends_uploads = stripslashes_deep($user_friends_uploads);

	if (count($user_friends_uploads) == 0) {
		error('Your friends have not added songs to their profile!','Go yell at them, maybe they will snap!');
	}

	echo '	<fb:dialog id="embed_player_friends">
				<fb:dialog-title>External Player</fb:dialog-title>
				<fb:dialog-content>
					<form id="dummy_form_friends"></form>
					<div id="player_friends" style="padding-bottom: 0px;" align="center">
					<img src="' . $config['fb']['appcallbackurl'] . 'images/spinner.gif" id="spinner_friends" style="display:none; padding-bottom: 5px;"/>
					</div>
				</fb:dialog-content>
				<fb:dialog-button type="button" value="Close" close_dialog=1 />
			</fb:dialog>	';

	foreach ($user_friends_info as $transverse) {
		$fbml = '<div style="margin: 20px;">
		<table style="margin-bottom: 10px;" width="100%">
			<tr>
				<td>';

		$fcount = 0;
		foreach($user_friends_uploads as $uploads)
		{
			if($uploads['user'] == $transverse['uid']) $fcount++;
		}

		$fbml = '' . $fbml . '<div style="border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;"><table border="0" width="100%" cellpadding="0" cellspacing="0">';
		$dcount = 1;

		foreach($user_friends_uploads as $display)
		{
			if($display['user'] == $transverse['uid']) 
			{

				$fbml = '' . $fbml . ' 
					<tr>
						<td>
							<center>';

				if($dcount == 1) 
				{
					$fbml = '' . $fbml . '<div style="border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc; background-color: #F7F7F7; padding: 1px;">';
				} else {
					$fbml = '' . $fbml . '<div style="border-bottom: 1px solid #cccccc; background-color: #F7F7F7; padding: 1px;">';
				}

				$fbml = '' . $fbml . '
				<table border="0" width="100%">
					<tr>
						<td valign="center" width="5%">
							<div style="padding-right: 5px; padding-left: 5px;"><a clicktoshowdialog="embed_player_friends" clickrewriteurl="' . $config['fb']['appcallbackurl'] . 'player.php?upload=' . $display['id'] . '&from_friends=1&owner=' . $transverse['uid'] . '" clickrewriteid="player_friends" clickrewriteform="dummy_form_friends" clicktoshow="spinner_friends"><img src="' . $config['fb']['appcallbackurl'] . 'images/track.gif" align="top" border="0"></a></div>
						</td>
						<td valign="center" width="95%">
							<a clicktoshowdialog="embed_player_friends" clickrewriteurl="' . $config['fb']['appcallbackurl'] . 'player.php?upload=' . $display['id'] . '&from_friends=1&owner=' . $transverse['uid'] . '" clickrewriteid="player_friends" clickrewriteform="dummy_form_friends" clicktoshow="spinner">' . $display['title'] . ' by ' . $display['artist']. '</a>
						</td>
					</tr>
				</table>
				</div>
				</td>
				</tr>';
				$dcount++;
			}

		}

		if($transverse['pic_square'] == NULL)
			$pic_square = "http://static.ak.fbcdn.net/pics/t_silhouette.jpg";
		else
			$pic_square = $transverse['pic_square'];

		$fbml = '' . $fbml . '</table></div></td>
		<td style="padding-left: 8px; width: 40px;"><a href="http://hs.facebook.com/profile.php?id=' . $transverse['uid'] . '"><img src="' . $pic_square . '"></a>
		</td>
		<td style="padding-left: 8px; width: 100px;"><span style="margin-bottom: 2px; color: #aaaaaa; font-size: 10.5px;">Music from:</span><br /><span style="font-weight: bold;"><a href="http://hs.facebook.com/profile.php?id=' . $transverse['uid'] . '">' . $transverse['name'] . '</a></span>
		</td>
	</tr>
	</table>
	</div>';
		if($fcount !== 0) echo $fbml;
	}
}
?>
