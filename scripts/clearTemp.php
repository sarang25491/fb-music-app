<?php
$pre = 'skip_fbapi';
include '../include/config.php';

$exp = date('Y-m-d H:i:s', strtotime('-1 hour'));

$temp = $db->Raw("SELECT user,location FROM userdb_temporary WHERE time < '$exp'");

echo 'Clearing about ' . count($temp) . ' files...';

foreach($temp as $file)
{
   $location = $file['location'];
   $uid = $file['user'];

   unlink($location);
   $db->Raw("DELETE FROM userdb_temporary WHERE user='$uid'");
} 

?>
