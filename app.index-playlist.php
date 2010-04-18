<?php
// PAGE IS PULLED BY IFRAME BY "app.index.php"
// THIS PAGE IS NOT ATTACHED TO THE APPLICATION

// $_GET['fb_sig_user] gets the current user ID.
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
					$("#saveBar").fadeIn(250);														 
				}								  
		});
	});
});
</script>

<script type="text/javascript">

function removeSong (id) {
	$("#playlist_" + id).slideUp(250, function () {
		$("#playlist_" + id).remove();
		$("#saveBar").fadeIn(250);
	});
}

function savePlaylist() {
	$("#saveBar").fadeOut(250, function () {
		var order = $("#playlist").sortable("serialize");
		$.post("app.index-playlist-callback.php?id=<?php echo $_GET['fb_sig_user']; ?>&updateList", order, 
			function(theResponse) {
				$("#status").html(theResponse);
			}
		);
	});
	
	$("#status").delay(1000).slideDown(250);
	$("#status").delay(2000).slideUp(250);
}

function revertPlaylist() {
	$("#playlist").slideUp(500);
	$("#saveBar").fadeOut(250, function () {
		$.post("app.index-playlist-list.php?id=<?php echo $_GET['fb_sig_user']; ?>", 
			function(theResponse) {
				$("#playlist").html(theResponse);
			}
		);
	});
	$("#playlist").slideDown(500);
	
}

function openPlayer(xid) {
	$("#editor").slideUp(500);
	$.post("player.php?from_embed=1&challenge=<?php echo $_GET['fb_sig_user']; ?>&autostart=1&id=" + xid,
		function (theResponse) {
			$("#player").html(theResponse);
		}
	);
	
	$.post("app.index-playlist-callback.php?grabSongData&id=" + xid,
		function (theResponse) {
			$("#playerData").html(theResponse);
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
</script>

<div id="playerData" style="width: 590px; display:none; background-color: #f7f7f7; font-size: 14px; padding: 5px;"></div>
<div id="player" style="width:600px; display: none;"></div>

<div style="margin-top: 5px; width: 600px; display: none; font-size: 14px;" id="playerBar">
	<div align="right"><a onclick="showEditor()" class="buttonBlue" style="margin-left: 10px; padding: 2px 5px 2px 5px;">Back to Playlist</a></div>
</div>

<div id="editor">
	
	<div align="right" id="status" style="width: 590px; display:none; background-color: #eceff5; font-size: 14px; padding: 5px;"></div>
	
	<div id="playlist" style="width:600px;">
			
		<?php include 'app.index-playlist-list.php'; ?>
			
	</div><!-- end playlist -->
	
	<div style="margin-top: 5px; width: 600px; display: none; font-size: 14px;" id="saveBar">
	<div align="right"><a onclick="savePlaylist()" class="buttonBlue" style="margin-left: 10px; padding: 2px 5px 2px 5px;">Save</a><a onclick="revertPlaylist()" class="buttonRed" style="padding: 2px 5px 2px 5px;">Revert</a></div>
	</div>
</div>

<div id="callback">
</div>
	