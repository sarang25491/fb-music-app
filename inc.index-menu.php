<style>
.main_nav
{
  background: #eceff6;
  border: 1px solid #d4dae8;
  width:175px;
  height:30px;
  margin:0px 0px 10px 0px;
}

.main_nav ul
{
  margin:0;
  padding:0;
  list-style-type:none; 
}

.main_nav ul li
{
  position:relative;
  display:inline;
  float:left;  
}

.main_nav ul li a
{
  display:block;
  color: black;
  width: 157px;
  padding:8px 9px;
  text-decoration:none;
}

.main_nav ul li a:hover
{
  background: #3b5998;
  color:white;
  padding-bottom:8px;
}
   
.main_nav div
{
  position:absolute;
  visibility:hidden;
}
   
.main_nav div a
{
  position:relative;
  display:block;
  background:#eceff6;
}
   
.main_nav div a:hover
{
  background:white;
}

a.button{
   text-align: center;
   background-color:#eceff6;
   display: block;
   font-weight:bold;
   padding: 1px 1px;
   margin: 0 1px 5px 1px;
   text-decoration:none;
   width:83px;
   border: 1px solid #d4dae8;
}

a:hover.button{
   color:#0066CC;
}
</style>

<script>
function show(obj)
{
   document.getElementById('navbar'+obj).setStyle('visibility','visible');
}

function hide(obj)
{
   document.getElementById('navbar'+obj).setStyle('visibility','hidden');
}

</script>

<?php $pages = json_decode($_POST['fb_sig_page_id']); ?>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td>

<a class="button" clicktoshowdialog="add_music">
<table border="0"><tr>
   <td valign="middle"><img valign="middle" src="<?php echo $config['fb']['appcallbackurl']; ?>images/add.png"></td>
   <td valign="middle">Add Song</td>
</tr></table>
</a>

</td>
<td>

<a class="button">
<table border="0"><tr>
   <td valign="middle"><img valign="middle" src="<?php echo $config['fb']['appcallbackurl']; ?>images/layout_add.png"></td>
   <td valign="middle">Share</td>
</tr></table>
</a>

</td>
</tr>
</table>

<div class="main_nav">
   <ul>
   <li onmouseover="show(1)" onmouseout="hide(1)">
      <a id="nav1" style="margin-bottom: 1px;">
         <center>
         <b>Switch Editor To...</b>
         <img valign="top" style="margin: 3px 0 0 4px" src="<?=$config['fb']['appcallbackurl']?>images/down.gif">
         </center>
      </a>
      <div id="navbar1" style="margin-left: -1px; width: 175px; border: 1px solid #d4dae8">
         <a href="?tab=index">Personal Playlist</a>
         <?php foreach ($pages as $page) { ?>
            <?php if ($page[1] == null) continue; ?>
            <a href="?tab=index&fb_page_id=<?php echo $page[0]; ?>"><?php echo $page[1]; ?></a>
         <?php } ?>
         <a href="http://www.facebook.com/add.php?api_key=<?php echo $config['fb']['key']; ?>&pages&_fb_q=1"><b>Add Facebook Page...</b></a>
      </div>
   </li>
   </ul>
</div>
