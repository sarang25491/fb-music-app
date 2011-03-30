<?php
if (isset($_GET['fb_page_id']))
{
   $otherids = fql_query("SELECT page_id FROM page WHERE page_id IN (SELECT page_id FROM page_admin WHERE uid=" . $_POST['fb_sig_user'] . ") AND has_added_app=1", $facebook);
   $array_otherids = array();
   foreach ($otherids as $page) $array_otherids[] = $page['page_id'];
}
else
{
   $otherids = json_decode($_POST['fb_sig_page_id']);
   $array_otherids = array();
   foreach ($otherids as $page) $array_otherids[] = $page[0];
}

$credit = $db->getSlots($_POST['fb_sig_user']);
$usage = $db->getUsage($_POST['fb_sig_user']);
if (count($otherids) > 0) $usage += $db->getUsage($otherids);
$postHash = md5($credit . ':' . $usage . ':' . $user . ':' . $config['fb']['secret']);

?>

<div style="margin: 10px">
<fb:iframe src='http://social.bidsystem.com/displayAd.aspx?pid=14311&appId=43221&plid=15965&adSize=468x60&channel=' width='468' height='60' frameborder='0' border='0' scrolling='no'></fb:iframe>
   <table border="0" width="100%">
      <tr>

         <td>
         
         <table border="0" width="100%">
            <tr>
               <td>
                  <font size="2em"><b>Search Youtube Database</b> (keyword)</font>
                  
                  <?php 
                  if ($_GET['error'] == 'empty')
                  {
                     error('Nothing Submitted','We cannot continue until you give us a link to a file on the web.');
                  }
                  ?>
                  
               </td>
            </tr>
            
            <tr>
               <td>
                  <fb:editor action="?tab=index&display=add&method=youtube&search<?php echo pages($_GET['fb_page_id']); ?>" labelwidth="0">
                     <fb:editor-text label="Search" name="search" value="" />
                     <fb:editor-buttonset>
                        <fb:editor-button value="Search"/>
                     </fb:editor-buttonset>
                  </fb:editor>
               </td>
            </tr>
         </table>
         
         <table border="0" width="100%">
            <tr>
               <td>
                  <?php error('Info on Uploads','We will be keeping the current "slot" system intact for now. However, new rules will be implemented into each slot including expiration dates and bandwidth restrictions. Sorry for all this, but it is getting necessary for us to implement it so that we can push for our new "pro" features.'); ?>
                  <table border="0" cellpadding="0" cellspacing="1">
                     <tr>
                        <td>
                           <font size="2em"><b>Upload File&nbsp;</b></font>
                        </td>
                        
                        <td>
                           <font size="2em">(mp3, m4a, flv supported; max 20MBs)</font>
                        </td>
                     </tr>
                     
                     <tr>
                        <td>
                        </td>
                        
                        <td>
                           <font size="2em"><u><?php echo $credit+$config['basicSlots']; ?></u> total slots, <u><?php echo ($credit+$config['basicSlots'])-$usage; ?></u> available for use, <b><a href="<?php echo $config['fb']['fburl']; ?>?tab=offers">get more here</a></b></font>
                        </td>
                     </tr>
                  </table>
                  
               </td>
            </tr>
            
            <tr>
               <td>
                     
                     <?php $check_temporary = $db->Raw("SELECT COUNT(*) FROM `userdb_temporary` WHERE `user`='$user'"); ?>
                     <?php $check_temporary = $check_temporary[0]['COUNT(*)']; ?>
                     <?php if ($check_temporary >= 1) { ?>
                        <?php 
                        if(isset($_GET['fb_page_id'])) 
                        { 
                           error("Incomplete","Looks like you forgot to finish an upload, would you like to continue?<br /><a href='" .  $config['fb']['fburl'] . "?tab=index&display=add&method=upload&step=3&fb_page_id=" . $_GET['fb_page_id'] . "'>Yes, continue!</a> - <a href='" . $config['fb']['fburl'] . "?tab=index&display=add&method=upload&step=reset&fb_page_id=" . $_GET['fb_page_id'] . "'>No, remove it.</a>"); 
                        } else 
                        { 
                           error("Incomplete","Looks like you forgot to finish an upload, would you like to continue? <a href='" . $config['fb']['fburl'] . "?tab=index&display=add&method=upload&step=3'>Yes, continue!</a> - <a href='" .$config['fb']['fburl'] . "?tab=index&display=add&method=upload&step=reset'>No, remove it.</a>"); 
                        } 
                        ?>
                     <?php } elseif ($credit+$config['basicSlots'] <= $usage) { ?>
                        <?php error('Not enough slots!','You need more slots to use this feature! <a href="' . $config['fb']['fburl'] . '?tab=offers">Click here to get some!</a>'); // I want this an image overlaying the actual upload system ?>
                     <?php } else { ?>
                           <?php $progress_id = '' . $user . '.' . time() . ''; ?>
                           <form name="form1" enctype="multipart/form-data" method="post" action="<?php echo $config['fb']['appcallbackurl']; ?>?tab=index&display=add&method=upload&step=2<?php echo pages($_GET['fb_page_id']); ?>&hash=<?php echo $postHash; ?>&credit=<?php echo $credit; ?>&usage=<?php echo $usage; ?>&X-Progress-ID=<?php echo md5($progress_id); ?>">
                              <table class="editorkit" border="0" cellspacing="0" style="width:425px">
                                 <tr class="width_setter">
                                    <th style="width:75px"></th>
                                    <td></td>
                                    </tr><tr>
                                    <th><label>File:</label></th>
                                    <td class="editorkit_row">
                                       <input name="upfile" type="file" size="23" style="color: #003366; font-family: Verdana; font-weight: normal; font-size:11px">
                                    </td>
                                    <td class="right_padding"></td>
                                 </tr>
                                 <tr>
                                    <th></th>
                                    <td class="editorkit_buttonset">
                                       <input name='upload' type='submit' id='upload' class="editorkit_button action" value='Upload' clickthrough="true" onclick="document.getElementById('upload_progress').contentWindow.$(#status).smartupdaterRestart();" />
                                    </td>
                                    <td class="right_padding">
                                       
                                    </td>
                                 </tr>
                              </table>
                              <div style="margin-left: 200px; margin-top: -40px;">
                                 <fb:iframe id="upload_progress" src="<?php echo $config['fb']['appcallbackurl']; ?>uploadprogress.php?id=<?php echo md5($progress_id); ?>" width="250" height="45" frameborder="0" scrolling="no"></fb:iframe>
                              </div>
                           </form>
                     <?php } ?>
                     
               </td>
            </tr>
         </table>
         
         <br />
         
         <table border="0" width="100%">
            <tr>
               <td>
                  <font size="2em"><b>Add External Link</b> (mp3, m4a, youtube supported)</font>
                  
                  <?php 
                  if ($_GET['error'] == 'no_link_submitted')
                  {
                     error('Nothing Submitted','We cannot continue until you give us a link to a file on the web.');
                  }
                  elseif ($_GET['error'] == 'does_not_end_in_mp3')
                  {
                     error('Not an Audio File','You need to specify a link that leads to an audio file.');
                  }
                  elseif ($_GET['error'] == 'not_valid_link')
                  {
                     error('File Inexistant','The file you have specified does not exist, please check the link and try again!');
                  }
                  ?>
                  
               </td>
            </tr>
            
            <tr>
               <td>
                  <fb:editor action="?tab=index&display=add&method=link&step=2<?php echo pages($_GET['fb_page_id']); ?>" labelwidth="0">
                     <fb:editor-text label="Link" name="link" value="http://"/>
                     <fb:editor-buttonset>
                        <fb:editor-button value="Submit"/>
                     </fb:editor-buttonset>
                  </fb:editor>
               </td>
            </tr>
         </table>
         
         <br />
         
         
         
         <br />
         
         <td>
      </tr>
   </table>
</div>
