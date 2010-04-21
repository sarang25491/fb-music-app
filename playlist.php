<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<style>
body {
	font-family: "Tahoma", "MS Sans Serif", "Microsoft Sans Serif", "MS Serif",sans-serif;
	font-size: 10pt;
	margin: 0px;
	padding: 0px;
	text-align: left;
}

.buttonBlue
{
    font-size: 14px;
    background-color: #d8dfea;
    color: #3b5998;
    font-weight: bold;
    text-decoration: none;
    text-align: right;
}
</style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script> 
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>

<script>
function openPlayer(xid) {
	$("#playlist").slideUp(500);
	$.post("player.php?caller=editor&id=" + xid,
		function (response) {
			$("#player").html(response);
		}
	);
	
	$.post("app.index-playlist-callback.php?grabSongData&id=" + xid,
		function (response) {
			$("#playerData").html(response);
		}
	);
	
	
	$("#playerData").delay(500).slideDown(250);
	$("#playerBar").delay(500).fadeIn(250);
	$("#player").delay(1250).slideDown(500);
}

function showEditor() {
	$("#player").slideUp(500);
	$("#playerBar").fadeOut(250);
	$("#playerData").delay(500).slideUp(500);
	$("#playlist").delay(1000).slideDown(500);
}
</script>

<?php
$encodedInput = $_GET['id'];
$decodedInput = base64_decode($encodedInput);
list($userId, $timeIssued) = split("-", $decodedInput, 2);

$expires = date("U", strtotime("-10 min")); // 15 minutes in the past

if ($expires > $timeIssued)
	die ("Sorry, this player expired!");
?>

<div style="padding-top: 25px; padding-left: 25px;">
	<div id="playerData" style="width: 490px; display:none; background-color: #f7f7f7; font-size: 14px; padding: 5px;"></div>
	<div id="player" style="width:500px; display: none;"></div>

	<div style="margin-top: 5px; width: 500px; display: none; font-size: 14px;" id="playerBar">
		<div align="right"><a onclick="showEditor()" class="buttonBlue" style="margin-left: 10px; padding: 2px 5px 2px 5px;">Back to Playlist</a></div>
	</div>

	<div id="playlist" style="width: 500px">
		<?php include 'app.index-playlist-list.php';?>
	</div>
</div>