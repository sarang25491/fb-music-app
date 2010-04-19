<?php
/*
IMPLEMENTS LIGHTTD 1.5 MOD_UPLOADPROGRESS
AUTHOR: STEVEN LU
EMAIL: SLU@BURST-DEV.COM

This script takes the improperly formatted JSON array and converts it to a PHPizeable "stdClass Object"
http://redmine.lighttpd.net/wiki/1/Docs:ModUploadProgress
*/
?>

<?php if(!isset($_GET['update'])) { ?>
<script type="text/javascript" src="javascript/prototype.js"></script>
<script type="text/javascript" src="javascript/scriptaculous.js"></script>

<div id="status">
<?php } ?>

<?php
$md5 = $_GET['id']; // Part of the music app.

$jsonArray = file_get_contents('http://127.0.0.1/progress?X-Progress-ID=' . $md5 . '');
$jsonArray = str_replace(array("new","Object","(",")"), '', $jsonArray);
$jsonArray = str_replace("'",'"', $jsonArray);

$lighttpd = json_decode($jsonArray);

// There is state, status, size, received. Unfortunately, its very limited.
$state = $lighttpd->{'state'}; // can be starting, error, done, uploading
$status = $lighttpd->{'status'}; // http error status
$size = $lighttpd->{'size'}; // size of the file
$received = $lighttpd->{'received'}; // how much we got of the file.
?>

<?php if($state == 'uploading') { ?>

	<?php
	if($size == 0) die(); // nothing to track 
	
	// our own little calculations
	$percent = round(($received/$size)*100, 2);
	$sizeM = round($size/1000000, 2);
	$receivedM = round($receivedM/1000000, 2);
	?>
	
	<?php echo 'Sent <b>' . $percent . '%</b> of <b>' . $sizeM . 'MB</b>...'; ?> 
<?php } ?>

<?php if(!isset($_GET['update'])) { ?>
</div>
<script type="text/javascript">
	new Ajax.PeriodicalUpdater('status', 'uploadprogress.php?update&id=<?php echo $md5; ?>', {asynchronous:true, frequency:0.5});
</script>
<?php } ?>