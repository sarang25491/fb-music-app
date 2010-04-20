<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<?php
// PAGE IS PULLED BY IFRAME BY "app.index.php"
// THIS PAGE IS NOT ATTACHED TO THE APPLICATION

// $_GET['fb_sig_user] gets the current user ID.
?>

<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
<script type="text/javascript">
    FB_RequireFeatures(["CanvasUtil"], function()
    { 
      FB.XdComm.Server.init("/xd_receiver.html?v=2");
      FB.CanvasClient.startTimerToSizeToContent();
    });
</script>

<?php 
if(isset($_GET['fb_page_id']))
	$id = $_GET['fb_page_id'];
else
	$id = $_GET['fb_sig_user'];
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script> 
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>

<style>
body {
	font-family: "Tahoma", "MS Sans Serif", "Microsoft Sans Serif", "MS Serif",sans-serif;
	font-size: 8pt;
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
.buttonBlue:hover
{
    background-color: #3b5998;
    color: #ffffff;
    cursor: hand;
}

.buttonRed
{
    font-size: 14px;
    background-color: #ffebe8;
    color: #dd3c10;
    font-weight: bold;
    text-decoration: none;
    text-align: right;
}
.buttonRed:hover
{
    background-color: #dd3c10;
    color: #ffffff;
    cursor: hand;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
	$(function() {
	
		$("#playlist").sortable({ 
			opacity: 0.6, 
			cursor: 'move', 
			update: 
				function() {
					$("#menuBar").fadeOut(250);
					$("#saveBar").delay(250).fadeIn(250);														 
				}								  
		});
	});
});

function removeSong (id) {
	$("#playlist_" + id).slideUp(250, function () {
		$("#playlist_" + id).remove();
		$("#menuBar").fadeOut(250);
		$("#saveBar").delay(250).fadeIn(250);	
	});
}

function savePlaylist() {
	$("#status").slideUp(50);

	$("#saveBar").fadeOut(250, function () {
		var order = $("#playlist").sortable("serialize");
		$.post("app.index-playlist-callback.php?id=<?php echo $id; ?>&updateList", order, 
			function(response) {
				$("#status").html(response);
			}
		);
	});
	
	$("#menuBar").delay(250).fadeIn(250);
	$("#status").delay(1500).slideDown(250);
	$("#status").delay(2500).slideUp(250);
}

function revertPlaylist() {
	$("#playlist").slideUp(500);
	$("#saveBar").fadeOut(250, function () {
		$.post("app.index-playlist-list.php?id=<?php echo $id; ?>", 
			function(response) {
				$("#playlist").html(response);
			}
		);
	});
	$("#playlist").slideDown(500);
	$("#menuBar").delay(250).fadeIn(250);
	
}

function openPlayer(xid) {
	$("#editor").slideUp(500);
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
	$("#editor").delay(1000).slideDown(500);
}

function showInfo(xid) {
	$("#status").slideUp(50);
	$.post("app.index-playlist-callback.php?grabInfo&id=" +xid,
		function (response) {
			$("#status").delay(500).html(response);
		}
	)
	$("#status").delay(500).slideDown(250);
}

function showApiKey(xid) {
	$("#status").slideUp(50);
	$.post("app.index-playlist-callback.php?grabApiKey&id=" +xid,
		function (response) {
			$("#status").delay(500).html(response);
		}
	);
	$("#status").delay(500).slideDown(250);
}
</script>

<div id="playerData" style="width: 490px; display:none; background-color: #f7f7f7; font-size: 14px; padding: 5px;"></div>
<div id="player" style="width:500px; display: none;"></div>

<div style="margin-top: 5px; width: 500px; display: none; font-size: 14px;" id="playerBar">
	<div align="right"><a onclick="showEditor()" class="buttonBlue" style="margin-left: 10px; padding: 2px 5px 2px 5px;">Back to Playlist</a></div>
</div>

<div id="editor">
	
	<div align="right" id="status" style="width: 490px; display:none; background-color: #eceff5; font-size: 14px; padding: 5px;"></div>
	
	<div id="playlist" style="width:500px;">
			
		<?php include 'app.index-playlist-list.php'; ?>
			
	</div><!-- end playlist -->
	
	<div style="margin-top: 5px; width: 500px; display: none; font-size: 14px;" id="saveBar">
	<div align="right"><a onclick="savePlaylist()" class="buttonBlue" style="margin-left: 10px; padding: 2px 5px 2px 5px;">Save</a><a onclick="revertPlaylist()" class="buttonRed" style="padding: 2px 5px 2px 5px;">Revert</a></div>
	</div>
	
	<div style="margin-top: 5px; width: 500px; font-size: 14px;" id="menuBar">
		<div align="right">
		<a target="_parent" href="<?php echo $config['fb']['fburl']; ?>?tab=index&display=add<?php if(isset($_GET['fb_page_id'])) echo '&fb_page_id=' . $id . ''; ?>" class="buttonBlue" style="margin-left: 10px; padding: 2px 5px 2px 5px;">Add a Song</a>
		</div>
	</div>
</div>

<div style="padding: 10px;">
</div>
	