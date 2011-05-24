<?php
$pre = 'skip_fbapi';
include '../include/config.php';

$day = date('Y-m-d H:i:s', strtotime('-1 day'));

$temp = $db->Raw("SELECT user,location FROM userdb_temporary WHERE time < '$day'");

echo 'Clearing about ' . count($temp) . ' files...';

foreach($temp as $file)
{
   $location = $file['location'];
   $uid = $file['user'];

   unlink($location);
   $db->Raw("DELETE FROM userdb_temporary WHERE user='$uid'");
} 

?>
