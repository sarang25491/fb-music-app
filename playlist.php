<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>


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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js" type="text/javascript"></script>

<script>
function openPlayer(xid) {
   $("#player").slideUp(250);
	$("#playerData").delay(250).slideUp(250);
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
	$("#player").delay(1250).slideDown(500);
}
</script>

<?php
//$encodedInput = $_GET['id'];
//$decodedInput = base64_decode($encodedInput);
//list($userId, $timeIssued) = split("-", $decodedInput, 2);

//$expires = date("U", strtotime("-7 days")); // 15 minutes in the past

//if ($expires > $timeIssued)
//	die ("Sorry, this playlist link has expired! You will need to request another from whoever you got it from.");
$userId = $_GET['id'];
?>

<div style="padding-top: 25px; padding-left: 25px;">
   <!--- Cubics Ad Code START -->
   <script type="text/javascript">
   var pid = 14311;
   var appId = 43221;
   var plid = 15965;
   var adSize = "468x60";
   var channel = "";
   </script>
   <script language="javascript" type="text/javascript" src="http://social.bidsystem.com/displayAd.js"></script>
   <!--- Cubics Ad Code END -->

	<div id="playerData" style="width: 490px; display:none; background-color: #f7f7f7; font-size: 14px; padding: 5px;"></div>
	<div id="player" style="width:500px; display: none;"></div>

	<div id="playlist" style="width: 500px">
		<?php include 'inc.playlist.php';?>
	</div>

   <div style="margin: 5px 0 0 5px;">
   Get your own shareable playlist through the <a href="<?php echo $config['fb']['fburl']; ?>">music app</a>.
   </div>
</div>
