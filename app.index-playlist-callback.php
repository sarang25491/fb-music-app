<?php $pre = 'skip_fbapi'; include 'include/config.php'; ?>


<?php if (isset($getList) || isset($_GET['revertList'])) { ?>



<?php } ?>

<?php

if (isset($_GET['updateList'])) {
	$i=0;
	foreach ($_POST['playlist'] as $song)
	{
		$db->Raw("UPDATE `userdb_uploads` SET `order`='$i' WHERE `xid`='$song'");
		$i++;
	}
} elseif (isset($_GET['removeSong'])) {
	echo $_GET['id'];
}

?>