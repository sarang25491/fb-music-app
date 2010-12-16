<?php
/*
First display page (display == null) will show the editing player functions for the user.

The page is split into two columns by a table, then in those columns are tables.
The left hand column will display the user's player and edit functions,
while the right hand column simply displays editing information.

Below the editor is a button where a user can add a song.
*/
?>

<?php // include_once 'app.index-header.php'; ?>

<?php if (isset($_GET['publish'])) include_once 'fb.publish.php'; ?>

<?php if ($_GET['display'] == NULL) { ?>
   
   <div style="margin: 10px;">
   <table border="0" width="100%" cellspacing="5px">
      <tr>
         <td valign="top">
            <div style="height: 16px;">
               <div style="float: left; text-align: center; background-color: #eceff6; margin: 0 0 0px 0; padding: 3px 5px 3px 5px; font-weight: bold; border-left: 1px solid #d4dae8; border-right: 1px solid #d4dae8; border-top: 1px solid #d4dae8;">
               Playlist
               </div>
            </div>            

            <?php include 'inc.index-errors.php'; ?>

            <div style="border: 1px solid #cccccc; padding: 10px; margin-top: 4px; margin-bottom: 5px;">
            <table border="0" width="100%">
               <tr>
                  <td valign="top" style="padding-left: 10px;">
                     <fb:iframe src='<?php echo $config['fb']['appcallbackurl']; ?>app.index-playlist.php?<?php echo pages($_GET['fb_page_id']); ?>' width="510" height="500" frameborder="0" scrolling="no" name="editor" resizeable="true" />
                     <br />
                  <td>  
               </tr>
            </table>
            </div>
            <div style="height: 16px; margin: -5px 20px 10px 0;">
               <div style="float: right; padding: 3px 5px 3px 5px; border-left: 1px solid #d4dae8; border-bottom: 1px solid #d4dae8; border-right: 1px solid #d4dae8; background-color: #eceff6;">
                  <b>Playlist Link</b>: <a href="<?php echo $config['fb']['appcallbackurl']; ?>playlist.php?id=<?php echo $user; ?>"><?php echo $config['fb']['appcallbackurl']; ?>playlist.php?id=<?php echo $user; ?></a>
               </div>
            </div> 

         </td>

         <td width="170px" valign="top">
         <div style="">
            <?php include_once('inc.index-menu.php'); ?>
            <div style="height: 16px;">
               <div style="float: left; text-align: center; background-color: #eceff6; padding: 3px 5px 3px 5px; font-weight: bold; border-left: 1px solid #d4dae8; border-right: 1px solid #d4dae8; border-top: 1px solid #d4dae8;">
               Editor Key
               </div>
            </div>
            <table border="0" style="padding: 10px; border: 1px solid #cccccc;" width="100%">
               <tr>
                  <td width="16px" style="border: 1px solid #cccccc; padding: 2px; background-color: #f7f7f7;"></td><td style="padding-left: 5px;">Drag & Drop to <br />Order Playlist</td>
               </tr>
               <tr>
                  <td style="border: 1px solid #cccccc; padding: 2px;"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/track.gif" align="top" border="0"></td><td style="padding-left: 5px;">Play Song</td>
               </tr>
               <tr>  
                  <td style="border: 1px solid #cccccc; padding: 2px;"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/tag_blue_edit.png" align="top" border="0"></td><td style="padding-left: 5px;">Edit Info</td>
               </tr>
               <tr>  
                  <td style="border: 1px solid #cccccc; padding: 2px;"><img src="<?php echo $config['fb']['appcallbackurl']; ?>images/delete.png" align="top" border="0"></td><td style="padding-left: 5px;">Delete Song</td>


               </tr>
            </table>
      </td>

      </tr>
   </table>
   </div>
      
      <fb:dialog id="add_music" width="500">
          <fb:dialog-title>Add Song</fb:dialog-title>
             <fb:dialog-content>
               <?php include_once('inc.add.php'); ?>
             </fb:dialog-content>
          <fb:dialog-button type="button" value="Close" close_dialog=1 />
      </fb:dialog>
<?php } else { ?>

   <?php 
   if($_GET['method'] == 'upload') 
   {
      include 'app.upload.php';
   }
   elseif ($_GET['method'] == 'link')
   { 
      include 'app.link.php';
   }
   elseif ($_GET['method'] == 'youtube')
   {
      include 'app.youtube.php';
   }
   ?>

<?php } ?>
