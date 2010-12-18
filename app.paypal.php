<?php

/*  PHP Paypal IPN Integration Class Demonstration File
 *  4.16.2005 - Micah Carrick, email@micahcarrick.com
 *
 *  This file demonstrates the usage of class.paypal.php, a class designed  
 *  to aid in the interfacing between your website, paypal, and the instant
 *  payment notification (IPN) interface.  This single file serves as 4 
 *  virtual pages depending on the "action" varialble passed in the URL. It's
 *  the processing page which processes form data being submitted to paypal, it
 *  is the page paypal returns a user to upon success, it's the page paypal
 *  returns a user to upon canceling an order, and finally, it's the page that
 *  handles the IPN request from Paypal.
 *
 *  I tried to comment this file, aswell as the acutall class file, as well as
 *  I possibly could.  Please email me with questions, comments, and suggestions.
 *  See the header of class.paypal.php for additional resources and information.
*/

// Setup class
if (!isset($_GET['tab'])) {
	$pre = 'skip_fbapi';
	include_once 'include/config.php';
}

require_once('include/class.paypal.php');  // include the class file
$p = new paypal_class;             // initiate an instance of the class
// $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
            
// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = '' . $config['fb']['appcallbackurl'] . 'app.paypal.php';

// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'data'; 

switch ($_GET['action']) {
	case 'data':
		
		echo '
		<div id="header" style="padding-top: 15px; padding-right: 25px; padding-left: 20px; padding-bottom: 50px;">
		<div style="float: left;"><img src="' . $config['fb']['appcallbackurl'] . 'img/music.png" /></div>
		<div style="float: right; padding-top: 5px;"><b><font size="2px"><fb:intl>ORDERING SLOTS</fb:intl></font></b></div>
		</div>
	  
		<center>
		<fb:intl>
		Because this application requires huge amounts of hard drive space and bandwidth<br />
		we need help from the user to maintain this application and keep it running<br />
		we update this application on a nightly basis and help customers when needed,<br />
		donating to this application will not be in vein!<br />
		<br />
		<b>
		Each slot costs $1.50 (25% off), order more than 10 and we will remove all the ads.<br />
		Please indicate how many you would like to order below.<br />
		<br />
		Any problems should be reported through our <a href="' . $config['fb']['fburl'] . '?tab=help">help pages</a>.<br />
		We can provide refunds within 3 days of the order.<br />
		An confirmation email will be sent after successful payment.<br />
		</b>
		</fb:intl>
		</center>

		<fb:editor action="' . $this_script . '?action=process" labelwidth="0">
			<fb:editor-text label="Number of Slots" name="amount" value="5.00" />
			<fb:editor-custom>
				<input type="hidden" name="user" value="' . $_POST['fb_sig_user'] . '">
			</fb:editor-custom>
			<fb:editor-buttonset>
				<fb:editor-button value="Proceed to Paypal"/>
			</fb:editor-buttonset>
		</fb:editor>
		';
		
		die();
	
	case 'process':      // Process and order...

		// There should be no output at this point.  To process the POST data,
		// the submit_paypal_post() function will output all the HTML tags which
		// contains a FORM which is submited instantaneously using the BODY onload
		// attribute.  In other words, don't echo or printf anything when you're
		// going to be calling the submit_paypal_post() function.

		// This is where you would have your form validation  and all that jazz.
		// You would take your POST vars and load them into the class like below,
		// only using the POST values instead of constant string expressions.

		// For example, after ensureing all the POST variables from your custom
		// order form are valid, you might have:
		//
		// $p->add_field('first_name', $_POST['first_name']);
		// $p->add_field('last_name', $_POST['last_name']);

		if ($_GET['action'] == 'process') {
			if ($_POST['amount'] == NULL) {
				echo 'NOT ENOUGH DATA, PLEASE GO BACK AND TRY AGAIN';
				die();
			}

			if (!is_numeric($_POST['amount'])) {
				echo 'NOT A PROPER DONATION AMOUNT, PLEASE GO BACK AND TRY AGAIN';
				die();
			}
			
			$amount = $amount * 1.5;
		}

		$p->add_field('business', $config['pp']['pay_to']);
		$p->add_field('cmd','_donations');
		$p->add_field('return', $this_script.'?action=success');
		$p->add_field('cancel_return', $this_script.'?action=cancel');
		$p->add_field('notify_url', $this_script.'?action=ipn&user=' . $_POST['user'] . '');
		$p->add_field('item_name', 'Music Application Donation');
		$p->add_field('amount', $amount);

		$p->submit_paypal_post(); // submit the fields to paypal
		//$p->dump_fields();      // for debugging, output a table of all the fields
	break;
      
	case 'success':      // Order was successful...
   
		// This is where you would probably want to thank the user for their order
		// or what have you.  The order information at this point is in POST 
		// variables.  However, you don't want to "process" the order until you
		// get validation from the IPN.  That's where you would have the code to
		// email an admin, update the database with payment status, activate a
		// membership, etc.  

		echo "<html><head><title>Donation Completed</title></head><body><h3>Thank you for your donation, it may require some time to process your donation depending on the verification. We are now redirecting you back to the application.</h3>";
		echo "<meta http-equiv='REFRESH' content='2;url=" . $config['fb']['fburl'] . "'>";
		foreach ($_POST as $key => $value) { echo "$key: $value<br>"; }
		echo "</body></html>";

		// You could also simply re-direct them to another page, or your own 
		// order status page which presents the user with the status of their
		// order based on a database (which can be modified with the IPN code 
		// below).

	break;
      
	case 'cancel':       // Order was canceled...

		// The order was canceled before being completed.

		echo "<html><head><title>Canceled</title></head><body><h3>The donation process was canceled. Redirecting...</h3>";
		echo "<meta http-equiv='REFRESH' content='2;url=" . $config['fb']['fburl'] . "'>";
		echo "</body></html>";

	break;
      
	case 'ipn':          // Paypal is calling page for IPN validation...
   
		// It's important to remember that paypal calling this script.  There
		// is no output here.  This is where you validate the IPN data and if it's
		// valid, update your database to signify that the user has payed.  If
		// you try and use an echo or printf function here it's not going to do you
		// a bit of good.  This is on the "backend".  That is why, by default, the
		// class logs all IPN data to a text file.
      
		if ($p->validate_ipn()) {
          
	        // Payment has been recieved and IPN is verified.  This is where you
	        // update your database to activate or process the order, or setup
	        // the database with the user's order details, email an administrator,
	        // etc.  You can access a slew of information via the ipn_data() array.
  
	        // Check the paypal documentation for specifics on what information
	        // is available in the IPN POST variables.  Basically, all the POST vars
	        // which paypal sends, which we send back for validation, are now stored
	        // in the ipn_data() array.
  
	        // For this example, we'll just email ourselves ALL the data.
			$payee_name = $p->ipn_data['address_name'];
			$payee = $p->ipn_data['payer_email'];
			$amount = $p->ipn_data['payment_gross'];
			$txn_id = $p->ipn_data['txn_id'];
			$txn_date = $p->ipn_data['payment_date'];
		 
			// The following is added for the Music application's DB.
			// This will send an email to whoever is the application administrator.
				 
			include 'include/class.phpmailer.php';
			$mail = new PHPMailer();

			$mail->IsMail();
			
			$mail->From       	= "support@burst-dev.com";
			$mail->FromName   	= "Burst Development Support";
			
			// $mail->AddCC("accounting@burst-dev.com", "Burst Development Accounting");
			
			$mail->Subject		= "Your Donation to the Music Application (" . $txn_id . ")";
		 
			$db->Raw("INSERT INTO `userdb_transactions` (`id`,`txn_type`,`user`,`payee`,`amount`) VALUES ('$txn_id','web_accept','$user','$payee','$amount');");
		
			$user_data = $db->Raw("SELECT `pro`,`override` FROM `userdb_users` WHERE `user`='$user'"); 
			$exists = count($user_data);
		
			if ($exists == '1')
			{
				$slots = $user_data[0]['override']+(round($amount/1.5));
				$db->Raw("UPDATE `userdb_users` SET `override`='$slots',`pp_email`='$payee' WHERE `user`='$user';");
			} else {
				$slots = round($amount/1.5);
				$db->Raw("INSERT INTO `userdb_users` (`user`,`credit`,`override`,`pro`,`pp_email`) VALUES ('$user','0','$slots','0','$payee');");
			}
		 
		 	if(($user_data[0]['override']+round($amount/1.5)) >= 10.00)
		 		$db->Raw("UPDATE `userdb_users` SET `pro`='1' WHERE `user`='$user'");
			
			$body = "Thanks for your donation to the Music Application!\n";
			$body .=	"This is a confirmation email, so please keep this for your records.\n";
			$body .=	"\n"; 
			$body .=	"We have recieved your donation. of $" . $amount . ", giving you a total of " . $slots . " slots to upload music (including other donations).\n";
			$body .=	"This transaction was made on " . $txn_date . " and has the transaction ID of " . $txn_id . ".\n";
			$body .=	"\n";
			$body .=	"Again, thanks for your support and we hope you enjoy the application as much as we enjoyed developing it.\n";
			$body .=	"If you have any issues, please do not hesitate to email us through our application or by email at support@burst-dev.com.\n";
			$body .=	"\n";
			$body .=	"- The Burst Development Team\n";
			
			$mail->Body = $body;
			$mail->AddAddress($payee, $payee_name);
			$mail->Send();

      	}
 	break;

} // end switch     

?>
