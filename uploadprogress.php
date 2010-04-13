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
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js"></script>

<style>
.progressBar{
	width:216px;
	height:41px;
	background:url(images/bg_bar.gif) no-repeat 0 0;
	position:relative;
}
.progressBar span{
	position:absolute;
	display:block;
	width:200px;
	height:25px;
	background:url(images/bar.gif) no-repeat 0 0;
	top:8px;
	left:8px;
	overflow:hidden;
	text-indent:-8000px;
}
.progressBar em{
	position:absolute;
	display:block;
	width:200px;
	height:25px;
	background:url(images/bg_cover.gif) repeat-x 0 0;
	top:0;
}
</style>

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
	
	<div style="font-family: 'lucida grande', tahoma, verdana, arial, sans-serif; font-size: 11px; margin-top: -10px;">
	<p class="progressBar">
		<span><em style="left:<?php echo round($percent*2); ?>px"></em></span>
	</p>
	</div>
<?php } ?>

<?php if(!isset($_GET['update'])) { ?>
</div>
<script type="text/javascript">
	new Ajax.PeriodicalUpdater('status', 'uploadprogress.php?update&id=<?php echo $md5; ?>', {asynchronous:true, frequency:0.5});
</script>
<?php } ?>