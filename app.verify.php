<?php

/*
COLUMNS: id, status, time_submitted
id - facebook user id
status
	0: no application on file
	1: application submitted, awaiting administration
	2: application reviewed, approved
	3: application reviewed, denied (submit email)
time_submitted
	the time the application was submitted
*/
$data = $db->Raw("SELECT `status` FROM `pages` WHERE `fb_page_id`='$user'");

if ($data[0]['status'] == '1') {
	error('Error', 'You are not permitted to submit another application.');
} elseif ($data[0]['status'] == '2') {
	success('All Good','Dunno how you got here, but you are aleady verified!');
} elseif ($data[0]['status'] == '3') {
	error('Verification Issue', 'There seems to have been a problem with your verification. Please check your email and reply to it to resolve this issue. If there seems to be an issue, please go to the help pages and submit a support request.');
} elseif ($data[0]['status'] == '0') {
	if (!isset($_GET['send'])) {
		if ($_GET['error'] == 1)
			error('Please check your submission for empty fields and try again.','');
		elseif ($_GET['error'] == 2)
			error('Please choose a proper form of identification and try again.','');
		else
			explanation('Application for Verification','A verified artist allows you to use certain features that are restricted to normal users. This includes the ability to add a download link and a buy link to your songs. Verifying also puts you on our artists area. But before you unlock stuff like that, we need to make sure you are you and in order to do so you need to submit ID proof to us. ')
?>
		<center>
		<table border="0" cellspacing="0" cellpadding="0" width="700px">
			<tr>
				<td>
				<center><div style="border: 1px solid #cccccc; width: 225px; margin: 10px; padding: 20px;" align="center">
				<form name="form1" enctype="multipart/form-data" method="post" action="<?php echo $config['fb']['appcallbackurl']; ?>?tab&verify&send<?php pages($_GET['fb_page_id']); ?>">
					<table class="editorkit" border="0" cellspacing="0" style="width:200px">
						<tr class="width_setter">
							<th style="width:75px"></th>
							<td></td>
							</tr><tr>
							<th><label>Email:</label></th>
							<td class="editorkit_row">
								<input name="email" type="text" size="25" value="" style="color: #003366; font-family: Verdana; font-weight: normal; font-size:11px">	
							</td>
							<td class="right_padding"></td>
						</tr>
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
								<input name='upload' type='submit' id='upload' class="editorkit_button action" value='Submit' clicktoshow="upload_loading" clickthrough="true" /> (click twice)
							</td>
							<td class="right_padding">
								<div id="upload_loading" style="display:none;">
									Sending... <br /> <img src="<?php echo $config['fb']['appcallbackurl']; ?>images/spinner.gif" />
								</div>
							</td>
						</tr>
					</table>
				</form>
				</div></center>
				</td>
				<td>
				<div style="background-color: #fff5b1; border: 1px solid #ffd04d; padding: 10px; font-size: 16px; text-align: center; margin-right: 20px;">
					<fb:intl>A proper form of identification involves a photo along with your name that has been created by an accredited source (gov. issued ID, student ID, etc.). Please make sure that your page has your affiliation with it.</fb:intl>
				</div>
				</td>
			</tr>
		</table>
		</center>
<?php
	} elseif (isset($_GET['send'])) {
		if ($_POST['email'] == '' || $_FILES['upfile']['tmp_name'] == NULL) {
			$facebook->redirect('' . $config['fb']['fburl'] . '?tab&verify&error=1&fb_page_id=' . $user . '');
			die();
		} elseif (!in_array(substr($_FILES['upfile']['name'], strrpos($_FILES['upfile']['name'], '.') + 1), array('jpg','png','pdf','gif','bmp'))) {
			$facebook->redirect('' . $config['fb']['fburl'] . '?tab&verify&error=2&fb_page_id=' . $user . '');
			die();
		}
		
		include 'include/class.phpmailer.php';
		$mail             = new PHPMailer();

		$mail->IsSMTP();
		$mail->Host       = "mail.burst-dev.com";
		$mail->Port       = 25;
		$mail->SMTPSecure = "";
		$mail->SMTPAuth	= true;
		$mail->Username	= "system@burst-dev.com";
		$mail->Password	= "enUxyws2ERwKq6JF";

		$user_details = $facebook->api_client->users_getInfo($_POST['fb_sig_user'], 'name');
		$mail->From       = $_POST['email'];
		$mail->FromName   = $user_details[0]['name'];

		$mail->Subject    = 'Application for Verification [' . $user . ']';
		
		$body = "The following user has submitted an application of verification.\n";
		$body .= "\n";
		$body .= "FB-ID: " . $_POST['fb_sig_user'] . "\n";
		$body .= "Email: " . $_POST['email'] . "\n";
		$body .= "\n";
		$body .= "http://www.facebook.com/profile.php?id=" . $user . "&ref=nf [Page]\n";
		$body .= "\n";
		$body .= "Please choose the following choices: \n";
		$body .= "http://apps.burst-dev.com/music/page_verification.php?id=" . $user . "&status=2 [Verify]\n";
		$body .= "http://apps.burst-dev.com/music/page_verification.php?id=" . $user . "&status=3 [Issues with Verfication]\n";
		$body .= "\n";
		$body .= "Respond to the ticket after selecting to a choice and close the ticket.\n";
		
		$mail->Body = $body;

		$mail->AddAttachment($_FILES['upfile']['tmp_name'], $_FILES['upfile']['name']);
		$mail->AddAddress("support@burst-dev.com", "Burst Development Support");
		
		if ($mail->Send())
			$db->Raw("UPDATE `pages` SET `status`='1' WHERE `fb_page_id`='$user'");
		
		$facebook->redirect('' . $config['fb']['fburl'] . '?tab=index&fb_page_id=' . $user . '');
	}
}
?>
