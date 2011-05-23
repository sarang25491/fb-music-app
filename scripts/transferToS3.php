<?php
ini_set('memory_limit', '1G');

$pre = 'skip_fbapi';
include 'include/config.php';
include 'include/aws/sdk.class.php';

$s3 = new AmazonS3();

$query = $db->Raw("SELECT `id`,`link` FROM `userdb_uploads` WHERE `type`='upload' AND `server`!='s3'");
//echo count($query);
//print_r($query);

foreach ($query as $upload)
{
   $id = $upload['id'];
   $split = split("/", $upload['link']);
   $link = "/var/www/music/users/" . $split[4] . "/" . $split[5] . "/" . basename($upload['link']); 
   $file = basename($link);
//   echo $link;

   if (file_exists($link))
   {
      $s3->create_object('fb-music', $file, array(
         'fileUpload' => $link,
         'acl' => AmazonS3::ACL_AUTH_READ,
         'storage' => AmazonS3::STORAGE_REDUCED,
      ));
   }


   unlink($link);
   $db->Raw("UPDATE `userdb_uploads` SET `server`='s3', `link`='$file' WHERE `id`='$id'");
}
?>
