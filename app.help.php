<div style="margin-bottom: -10px;">
<?php include 'statics/campfire.txt'; ?>
</div>

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
<div style="padding: 10px; margin-left: 10px; margin-right: 10px; border: 1px solid #d4dae8;">
<?php include 'statics/redmine.txt'; ?><br />
<?php include 'statics/twitter.txt'; ?>
</div>

<?php explanation('<fb:intl>3. Ask Others</fb:intl>','Most of the time, the community will be able to help!'); ?>
<div style="margin-left: 10px; margin-right: 10px; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;">
<fb:board xid="help" numtopics="3" candelete="<?php if ($user == "1340490250") { echo 'true'; } else { echo 'false'; } ?>" canmark="<?php if ($user == "1340490250") { echo 'true'; } else { echo 'false'; } ?>">
<fb:title><fb:intl>Community Forum</fb:intl></fb:title></fb:board>
</div>

<?php if($error == NULL AND $action == NULL) { ?>
	<?php explanation('<fb:intl>4. Contact the Developers</fb:intl>','<fb:intl>This is your last resort if you cannot get an answer. Use the comment box below to contact the developers, all data here can only be seen by you and the developers. It usually takes up to twenty-four hours for a reply.</fb:intl>'); ?>
<?php } ?>

<?php include 'inc.tickets.php'; ?>
