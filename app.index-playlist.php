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
//					var order = $(this).sortable("serialize"); 
//					$.post("app.index-playlist-callback.php?updateList", order
//					, 
//					function(theResponse){
//						$("#callback").html(theResponse);
//					}
//				); 															 
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
		$.post("app.index-playlist-callback.php?updateList", order);
	});
}

function revertPlaylist() {
	$("#playlist").slideUp(500);
	$("#saveBar").fadeOut(250, function () {
		$.post("app.index-playlist-list.php", 
			function(theResponse) {
				$("#playlist").html(theResponse);
			}
		);
	});
	$("#playlist").slideDown(500);
	
}
</script>



<div id="playlist" style="width:600px;">
		
	<?php include 'app.index-playlist-list.php'; ?>
		
</div><!-- end playlist -->

<div style="margin-top: 5px; width: 600px; display: none; font-size: 14px;" id="saveBar">
<div align="right"><a onclick="savePlaylist()" class="buttonBlue" style="width:100px; margin-left: 10px; padding: 2px 5px 2px 5px;">Save</a><a onclick="revertPlaylist()" class="buttonRed" style="width:100px; padding: 2px 5px 2px 5px;">Revert</a></div>
</div>

<div id="callback">
</div>
	