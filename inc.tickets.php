<?php
$admins = array('1340490250','1423830689');

if (isset($_POST['fb_sig_xid_action'])) {

	if (in_array($_POST['fb_sig_user'], $admins)) {
		$status = 'closed';
		$xid = str_replace('help_','',$_POST['fb_sig_xid']);
	} else {
		if($_POST['fb_sig_xid_action'] == 'delete') {
			$status = 'closed';
		} else {
			$status = 'open';
		}
		$xid = $_POST['fb_sig_user'];
	}

	if (count($db->Raw("SELECT * FROM `userdb_support` WHERE `user`='$xid'")) == 1)
		$db->Raw("UPDATE `userdb_support` SET `status`='$status' WHERE `user`='$xid'");
	else
		$db->Raw("INSERT INTO `userdb_support` (`user`,`status`) VALUES ('$xid','$status')");
}

if (isset($_GET['close'])){
	$db->Raw("UPDATE `userdb_support` SET `status`='closed' WHERE `user`='$_GET[close]'");
}

if (in_array($_POST['fb_sig_user'], $admins) AND !isset($_POST['fb_sig_xid_action'])) {
	$tickets = $db->Raw("SELECT `user` FROM `userdb_support` WHERE `status`='open'");
	
	if (count($tickets) == 0) {
		echo '<div style="margin-top: -10px;">';
		success('No open ticket threads!','YAY! Steve wasn\'t being lazy and cleared all this junk!');
		echo '</div>';
	} else {
		foreach ($tickets as $ticket) {
			echo '<div style="margin-left: 10px; margin-right: 10px; margin-bottom: 10px; padding-left: 15px; padding-top: 10px; padding-bottom: 10px; border: 1px solid #d4dae8;">';
			echo '<div style="padding: 10px; margin-right: 10px; margin-bottom: 10px; border: 1px solid #d4dae8; background-color: #eceff6;"><b><a href="http://www.facebook.com/profile.php?id=' . $ticket['user'] . '"><fb:name uid="' . $ticket['user'] . '" capitalize="true" ifcantsee="Anonymous" /></a></b> - <a href="?tab=help&close=' . $ticket['user'] . '">Close ticket?</a></div>';
			echo '<fb:comments xid="help_' . $ticket['user'] . '" canpost="true" candelete="true" send_notification_uid="' . $ticket['user'] . '" simple="true" showform="true"></fb:comments>';
			echo '</div>';
		}
	}
} elseif (isset($_POST['fb_sig_xid_action'])) {
	echo '<fb:comments xid="help_' . $xid . '" canpost="true" candelete="true" send_notification_uid="' . $xid . '" simple="true" showform="true"></fb:comments>';
} else {
	echo '<div style="margin-left: 10px; margin-right: 10px; padding-left: 15px; padding-top: 10px; padding-bottom: 10px; border: 1px solid #d4dae8;">';
	echo '<fb:comments xid="help_' . $user . '" canpost="true" candelete="false" send_notification_uid="1340490250" simple="true" showform="true"></fb:comments>';
	echo '</div>';
}

?>