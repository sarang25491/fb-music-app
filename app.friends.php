<?php 

/*
Queries Facebook to get all the user's friends who have added the application.
It then asks Facebook for their name and their small profile photo.
*/


function render($user_friends_uploads, $user_friends_info, $rel_url)
{
   if (count($user_friends_uploads) == 0) {
      error('Your friends have not added songs to their profile!','Go yell at them, maybe they will snap!');
   }

   foreach ($user_friends_info as $transverse) {
      if (isset($transverse['page_id']))
         $transverse['uid'] = $transverse['page_id'];
      
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
                  <td valign="middle" width="5%">
                     <div style="padding-right: 5px; padding-left: 5px;"><a clicktoshowdialog="embed_player_friends" clickrewriteurl="' . $rel_url . 'player.php?upload=' . $display['id'] . '&from_friends=1&owner=' . $transverse['uid'] . '" clickrewriteid="player_friends" clickrewriteform="dummy_form_friends" clicktoshow="spinner_friends"><img src="' . $rel_url . 'images/track.gif" align="top" border="0"></a></div>
                  </td>
                  <td valign="middle" width="95%">
                     <a clicktoshowdialog="embed_player_friends" clickrewriteurl="' . $rel_url . 'player.php?upload=' . $display['id'] . '&from_friends=1&owner=' . $transverse['uid'] . '" clickrewriteid="player_friends" clickrewriteform="dummy_form_friends" clicktoshow="spinner">' . htmlspecialchars_decode(utf8_decode($display['title']), ENT_QUOTES) . ' by ' . htmlspecialchars_decode(utf8_decode($display['artist']), ENT_QUOTES) . '</a>
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

//multiquerying facebook db for the appropriate dataset
$fb_data = $facebook->api_client->fql_multiquery('{"query1":"SELECT name,pic_square,page_id FROM page WHERE page_id IN (SELECT page_id FROM page_fan WHERE uid=' . $user . ') AND has_added_app", "query2":"SELECT uid,name,pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=' . $user . ') AND is_app_user"}');

/*
Since MySQL cannot take array format to search,
we need to put it in comma seperated form.
*/
foreach($fb_data[1]['fql_result_set'] as $ids) $user_friends_ids = "" . $user_friends_ids . "'" . $ids['uid'] . "',";
$user_friends_ids = "" . $user_friends_ids . "'2436915755'";

foreach ($fb_data[0]['fql_result_set'] as $ids) $like_page_ids = "" . $like_page_ids . "'" . $ids['page_id'] . "',";
$like_page_ids = "" . $like_page_ids . "'2436915755'";

//Now we nicely ask our db in the proper format.
$user_friends_uploads = $db->Raw("SELECT `user`,`id`,`title`,`artist` FROM `userdb_uploads` WHERE `user` IN (" . $user_friends_ids . ") ORDER BY `user`,`order`,`id` ASC");

$like_page_uploads = $db->Raw("SELECT `user`,`id`,`title`,`artist` FROM `userdb_uploads` WHERE `user` IN (" . $like_page_ids . ") ORDER BY `user`,`order`,`id` ASC");

// Our popout player
echo '<fb:dialog id="embed_player_friends">
         <fb:dialog-title>External Player</fb:dialog-title>
            <fb:dialog-content>
               <form id="dummy_form_friends"></form>
               <div id="player_friends" style="padding-bottom: 0px;" align="center">
               <img src="' . $config['fb']['appcallbackurl'] . 'images/spinner.gif" id="spinner_friends" style="display:none; padding-bottom: 5px;"/>
               </div>
            </fb:dialog-content>
         <fb:dialog-button type="button" value="Close" close_dialog=1 />
      </fb:dialog>';

// rendering our looks
echo '<div style="margin: 15px; padding: 0 0 2px 8px; font-size: 14px; font-weight: bold; border-bottom: 1px solid #899cc1;">Pages</div>'; 
if (count($like_page_uploads) == 0)
   error('You don\'t like any pages to display any music from :(.');
else
   render($like_page_uploads, $fb_data[0]['fql_result_set'], $config['fb']['appcallbackurl']);

echo '<div style="margin: 15px; padding: 0 0 2px 8px; font-size: 14px; font-weight: bold; border-bottom: 1px solid #899cc1;">Friends</div>'; 
if (count($user_friends_uploads) == 0)
   error('None of your friends have uploaded any music :(.');
else
   render($user_friends_uploads, $fb_data[1]['fql_result_set'], $config['fb']['appcallbackurl']);
?>
