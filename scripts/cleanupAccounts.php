<?php
$pre = 'skip_login';
include '../include/facebook/facebook.php';
include '../include/config.php';
include '../include/aws/sdk.class.php';


function my_array_diff($a, $b) {
    $map = $out = array();
    foreach($a as $val) $map[$val] = 1;
    foreach($b as $val) if(isset($map[$val])) $map[$val] = 0;
    foreach($map as $val => $ok) if($ok) $out[] = $val;
    return $out;
}

function get_xids($db)
{
   $one = date("Y-m-d", strtotime("-1 month")); 
   $two = date("Y-m-d", strtotime("-2 months"));
   $three = date("Y-m-d", strtotime("-3 months"));

   // getting songs that have been
   //    - uploaded more than a month ago
   //    - user has not visited the app for more than a month
   $old_songs_db = $db->Raw("SELECT xid FROM userdb_uploads WHERE type='upload' AND time <= '$one' AND user IN (SELECT user from userdb_users WHERE time <= '$one')");
   
   // getting all song ids that have been:
   //    - played within the recent month
   $recently_played_db = $db->Raw("SELECT DISTINCT data FROM userdb_activity WHERE time > '$one'");

   // getting songs that have been:
   //    - uploaded by a page
   //    - older than a month
   //    - owner of that page has not visited in two months
   $old_pages_songs_db = $db->Raw("SELECT xid FROM userdb_uploads WHERE type='upload' AND time <= '$one' AND user IN (SELECT fb_page_id FROM pages WHERE owner IN (SELECT user FROM userdb_users WHERE time <= '$two'))");

   // getting songs that have:
   //    - no reference to a user
   //    - no reference to a page
   $unassociated_songs_db = $db->Raw("SELECT xid FROM userdb_uploads WHERE user NOT IN (SELECT user FROM userdb_users) AND user NOT IN (SELECT fb_page_id FROM pages) AND type='upload'");

   $old_songs = $recently_played = array();
   foreach($old_songs_db as $song)
      $old_songs[] = $song['xid'];

   foreach($old_pages_songs_db as $song)
      $old_songs[] = $song['xid'];

   foreach($unassociated_songs_db as $song)
      $old_songs[] = $song['xid'];

   foreach ($recently_played_db as $play)
      $recently_played[] = $play['data'];

   return my_array_diff($old_songs, $recently_played);

}

function delete_xid($db, $xid)
{
   $data = $db->Raw("SELECT server, link FROM userdb_uploads WHERE xid='$xid'");
   $server = $data[0]['server'];
   $link = $data[0]['link'];

   if ($server == 's3')
   {
      $s3 = new AmazonS3();
      $s3->delete_object('fb-music', $link);
   }
   else
   {
      $split = split("/", $data[0]['link']);
      $link = "/var/www/music/users/" . $split[4] . "/" . $split[5] . "/" . $split[6];
      unlink($link);
   }

   $db->Raw("DELETE FROM userdb_uploads WHERE xid='$xid'");
}

$data = get_xids($db); 

while(count($data) != 0)
{
   foreach ($data as $xid)
      delete_xid($db, $xid);
   
   $data = get_xids($db);
}
?>
