<?php

require_once('include/class.paypal.php'); 							 // include the class file
$p = new paypal_class; 												// initiate an instance of the class
$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
// $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     	// paypal url

$p->add_field('business', 'slu@me.com');
$p->add_field('return','http://apps.facebook.com/stevenlu/?tab=account'); // ***will most likely need a configureable variable which will lead them to a page confirming their order.***
$p->add_field('cancel_return','http://apps.facebook.com/stevenlu/?tab=account');

$this_script = '' . $config['fb']['appcallbackurl'] . 'app.paypal-subscribe.php';

switch ($_GET['action']) 
{

	// Subscription to be $2.99/mo., first 14 days free
	case 'subscribe':
		// specifying the cmd to paypal and which url to notify on any account changes.
		$p->add_field('cmd','_xclick-subscriptions'); // "_xclick" for one time, "_xclick-subscriptions" for on going subscriptions, "_donations" for donations
		$p->add_field('notify_url','' . $this_script . '?action=ipn');
		$p->add_field('item_name','Music App Pro');
		
		// specifies free trial period for 14 days where people can try it out.
		$p->add_field('a1','0');
		$p->add_field('p1','14'); 
		$p->add_field('t1','D');
		
		// specifies the price after the trial period.
		$p->add_field('a3','2.50');
		$p->add_field('p3','30'); 
		$p->add_field('t3','D');
		
		// recurring payment
		$p->add_field('src','1');
		
		$p->submit_paypal_post();
		
		break;
		
		
	case 'ipn': // for when paypal makes some calls to us.
		if ($p->validate_ipn()) {
			switch ($p->ipn_data['txn_type'])
			{
				case 'recurring_payment_profile_created': // for initial sign-ups
					$userRecurId = $p->ipn_data['recurring_payment_id']; // grab the ID number to recurr.
					$txnType = $p->ipn_data['txn_type'];
					$userRecurDate = $p->ipn_data['next_payment_date']; // when the next charge is
					$userEmail = $p->ipn_data['payer_email']; // grab's their PP email.
					$userId = $_GET['user']; // grab their user ID and who to award it to.
					
					// do the necessary code to modify user data
					$db->Raw("UPDATE `userdb_users` SET `pro`='1',`pp_email`='$userEmail',`pro_recur`='$proRecurDate',`pro_id`='$userRecurId' WHERE `user`='$userId';");
					
					// marking it inside transactions database
					$db->Raw("INSERT INTO `userdb_transactions` (`id`,`txn_type`,`user`,`payee`,`amount`) VALUES ('$userRecurId','$txnType','$userId','$userEmail','0');");
					
					break;
			
				case 'recurring_payment': // for recurring payments (just need to log them down).
					$txnId = $p->ipn_data['txn_id']; // paypal transaction
					$txnType = $p->ipn_data['txn_type'];
					$userEmail = $p->ipn_data['payer_email']; // grab's their PP email.
					$amount = $p->ipn_data['payment_gross'];
					$userId = $_GET['user']; // grab their user ID and who to award it to.
					
					// add to the transaction database
					$db->Raw("INSERT INTO `userdb_transactions` (`id`,`txn_type`,`user`,`payee`,`amount`) VALUES ('$txnId','$txnType','$userId','$userEmail','$amount');");
			
					break;
			
				case 'subscr_cancel': // for when some unsubscribes
					$userSubId = $p->ipn_data['subscr_id']; // grab the ID number to recurr.
					$txnType = $p->ipn_data['txn_type'];
					$userEmail = $p->ipn_data['payer_email']; // grab's their PP email.
					$userId = $_GET['user']; // grab their user ID and who to award it to.
					
					// add to the transaction database
					$db->Raw("INSERT INTO `userdb_transactions` (`id`,`txn_type`,`user`,`payee`,`amount`) VALUES ('$userSubId','$txnType','$userId','$userEmail','0');");
			
					break;
			
				case 'subscr_eot': // for when the unsub's time runs out completely
				
					// unsub the user
					$userSubId = $p->ipn_data['subscr_id']; // grab the ID number to recurr. // grab the ID number
					$txnType = $p->ipn_data['txn_type'];
					$userEmail = $p->ipn_data['payer_email']; // grab's their PP email.
					$userId = $_GET['user']; // grab their user ID and who to award it to.
					
					$db->Raw("UPDATE `userdb_users` SET `pro`='0',`pp_email`='',`pro_recur`='',`pro_id`='' WHERE `user`='$userId';");
			
					$db->Raw("INSERT INTO `userdb_transactions` (`id`,`txn_type`,`user`,`payee`,`amount`) VALUES ('$userSubId','$txnType','$userId','$userEmail','0');");
					break;
			}
		}
		break;
		
		
}

?>