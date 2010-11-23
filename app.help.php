<fb:dialog id="support" width="500">
	<fb:dialog-title>Before Contacting Support</fb:dialog-title>
	<fb:dialog-content>
		<?php success('Please read the following support guidelines...', ''); ?>
		<div style="padding: 10px; margin: 0 10px 0 10px; border: 1px solid #d4dae8">
			<div style="margin: -10px 0 0 -20px;">
            <ol>
				   <li>All requests should be in English, we can't read anything else.</li>
				   <li>Make sure that you explain your situation thoroughly; give us what you were trying to do and what exactly happened (error messages, abnormalities, etc.).</li>
				   <li>Read the FAQ and watch the videos. We don't know how much to express this, but most questions are answered by the FAQ.</li>
			      <li>Don't submit multiple tickets, it's not going to help us serve you any faster.</li>
               <li>Suggestions and ideas don't go here, please use our <a href="http://burst.uservoice.com/">Uservoice</a> for this.</li> 
            </ol>
         </div>			

			<b>Please note that if your support request does not follow the above, it will automatically be ignored due to the high number of requests.</b>
      </div>
		
		<?php success('<center>When you have read the above...<br /><a href="http://www.facebook.com/?compose=1&id=1340490250&sk=messages">Click to Submit a Support Request</a></center>',''); ?>
	</fb:dialog-content>
	<fb:dialog-button type="button" value="Close" close_dialog=1 />
</fb:dialog>

<?php $announcements = $facebook->api_client->fql_query("SELECT status_id,time,message FROM status WHERE uid='2436915755' ORDER BY time DESC LIMIT 10"); ?>

<fb:dialog id="ann" width="500">
   <fb:dialog-title>Announcements</fb:dialog-title>
   <fb:dialog-content>
      <?php foreach ($announcements as $announcement) { ?>
      [<fb:time t="<?php echo $announcement['time']; ?>" />] <?php echo $announcement['message']; ?> <br /><br />
      <?php } ?>
   </fb:dialog-content>
   <fb:dialog-button type="button" value="Close" close_dialog=1 />
</fb:dialog>

<div style="padding: 10px; margin: 10px; border: 1px solid #e2c822; background-color: #fff9d7; font-weight: bold; font-size: 12px;">
   <?php echo preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>',$announcements[0]['message']); ?>
</div>
<div style="height: 16px; margin: -10px 20px 10px 0;">
  <div style="float: right; padding: 3px 5px 3px 5px; border-left: 1px solid #d4dae8; border-bottom: 1px solid #d4dae8; border-right: 1px solid #d4dae8; background-color: #eceff6;">
      <?php echo translate('Message posted'); ?> <fb:time t="<?php echo $announcements[0]['time']; ?>" /> - <a clicktoshowdialog="ann"><?php echo translate('View More Recent Anouncements'); ?></a> 
  </div>
</div> 

<table border="0" width="100%" style="margin: 0 0 10px 0;">
<tr>
   <td valign="top" width="50%">
      <?php explanation('Watch These Videos...','If you\'re new to this app, watch these videos.'); ?>
      <div style="margin-left: 20px; margin-right: 20px;">
         <a href="http://screenr.com/62g" target="_blank">Uploading a Song From Your Computer to the Application</a><br />
         <a href="http://screenr.com/o2g" target="_blank">Adding the Player as a Box or Tab to Your Profile</a><br />
         <a href="http://screenr.com/Kxg" target="_blank">Using the Application With Your Facebook Page</a>
      </div>
   </td>

   <td valign="top" width="50%">
      <?php explanation('<fb:intl>Read the FAQ...</fb:intl>','Most questions asked usually linger around here.'); ?>
      <?php $faq_db = $db->Raw("SELECT * FROM `faq`"); ?>
      <?php foreach($faq_db as $entry) { ?>
	      <div style="margin-left: 20px;"><a href="#" clicktotoggle="faq_<?php echo $entry['id']; ?>"><fb:intl><?php echo $entry['question']; ?></fb:intl></a></div>
	      <div id="faq_<?php echo $entry['id']; ?>" style="margin: 0 20px 5px 30px; display: none;"><fb:intl><?php echo $entry['answer']; ?></fb:intl></div>
      <?php } ?>
   </td>
</tr>
</table>

<center>
<div style="width: 100%; margin: 10px; background-color: #ffebe8; border-top: 1px solid #dd3c10; border-bottom: 1px solid #dd3c10; font-weight: bold;">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
   <td>
      <div style="margin: 10px; padding: 10px; font-size: 16px; border: 1px solid #e2c822; background-color: #fff9d7;">
         <a href="http://www.facebook.com/board.php?uid=2436915755">View Discussion Board</a>
     </div>
   </td>

   <td>
      <div style="margin: 10px 10px 10px 0; padding: 10px; font-size: 16px; border: 1px solid #e2c822; background-color: #fff9d7;">
         <a href="https://burst.uservoice.com/forums/86033-general">Give Ideas & Suggestions</a>
      </div>
   </td>

   <td>
      <div style="margin: 10px 10px 10px 0; padding: 10px; font-size: 16px; border: 1px solid #e2c822; background-color: #fff9d7;">
         <a clicktoshowdialog="support">Submit Support Ticket</a>
      </div>
   </td>
</tr>
</table>
</div>
</center>
