<?php
$pre = 'skip_fbapi';
include 'include/config.php';

$db->Raw("UPDATE `pages` SET `status`='$_GET[status]' WHERE `fb_page_id`='$_GET[id]'");
?>

	<script language="javascript"> 
	<!-- 
	setTimeout("self.close();",10) 
	//--> 
	</script>

Administraiton decision completed for <?php echo $_GET['id']; ?>.