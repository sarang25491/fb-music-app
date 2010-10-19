<center>
<div style="width: 100%; margin: 10px; background-color: #ffebe8; border-top: 1px solid #dd3c10; border-bottom: 1px solid #dd3c10;">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
   <td>
      <div style="margin: 10px 10px 10px 10px; padding: 10px; font-size: 16px; border: 1px solid #e2c822; background-color: #fff9d7;">
         <a href="http://www.facebook.com/board.php?uid=2436915755">View Discussion Board</a>
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

<fb:dialog id="support" width="500">
	<fb:dialog-title>Before Contacting Support</fb:dialog-title>
	<fb:dialog-content>
		<?php success('Please read the following support guidelines...', ''); ?>
		<div style="padding: 10px; margin: 0 10px 0 10px; border: 1px solid #d4dae8">
			<div style="margin: -10px 0 0 -20px;">
            <ol>
				   <li>All requests should be in English, we can't read anything else.</li>
				   <li>Make sure that you explain your situation thoroughly; give us what you were trying to do and what exactly happened (error messages, abnormalities, etc).</li>
				   <li>Read the FAQ and watch the videos. We don't know how much to express this, but most questions are answered by the FAQ.</li>
			   </ol>
         </div>			

			<b>Please note that if your support request does not follow the above, it will automatically be ignored due to the high number of requests.</b>
      </div>
		
		<?php success('<center>When you have read the above...<br /><a href="http://www.facebook.com/?compose=1&id=1340490250&sk=messages">Click to Submit a Support Request</a></center>',''); ?>
	</fb:dialog-content>
	<fb:dialog-button type="button" value="Close" close_dialog=1 />
</fb:dialog>

<?php explanation('1. Watch the Intro Videos','Having trouble? These videos should help out a little. Each link will redirect you to a page on screenr.'); ?>
<div style="margin-left: 20px; margin-right: 20px;">
<a href="http://screenr.com/62g" target="_blank">Uploading a Song From Your Computer to the Application</a><br />
<a href="http://screenr.com/o2g" target="_blank">Adding the Player as a Box or Tab to Your Profile</a><br />
<a href="http://screenr.com/Kxg" target="_blank">Using the Application With Your Facebook Page</a>
</div>

<?php explanation('<fb:intl>2. Check the FAQ</fb:intl>','Most questions asked to the developers are usually found here.'); ?>
<?php $faq_db = $db->Raw("SELECT * FROM `faq`"); ?>
<?php foreach($faq_db as $entry) { ?>
	<div style="margin-left: 20px;"><a href="#" clicktotoggle="faq_<?php echo $entry['id']; ?>"><fb:intl><?php echo $entry['question']; ?></fb:intl></a></div>
	<div id="faq_<?php echo $entry['id']; ?>" style="margin-left: 30px; margin-right:10px; margin-bottom: 10px; display: none; background-color: #d4dae8"><fb:intl><?php echo $entry['answer']; ?></fb:intl></div>
<?php } ?>

<?php explanation('3. What is new?','Something could have gone wrong, check out what the developers are doing!'); ?>
<div style="padding: 10px; margin-left: 10px; margin-bottom: 10px; margin-right: 10px; border: 1px solid #d4dae8;">
<?php include 'statics/twitter.txt'; ?>
</div>
