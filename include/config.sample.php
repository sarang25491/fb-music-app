<?php
include 'database.php';
ini_set('register_globals', 'Off');

$config = array();
$testing = '';

########## Facebook Developer API Information ##########
# This information can be retrieved at the Facebook Developer
# website. In order to get this information, you must create
# your own application which will create the API information.
############################################################
$config['fb']['key'] = 'cd92203d073c3556a00ca2167c661421';
$config['fb']['secret'] = '';
$config['fb']['appcallbackurl'] = 'http://music.burst-dev.com/' . $testing . '';
$config['fb']['fburl'] = 'http://apps.facebook.com/stevenlu/' . $testing . '';
$config['fb']['about_url'] = 'http://www.facebook.com/apps/application.php?id=2436915755';

########## Server Information ##########
# This should be a reflection of your 
# server infomation, its as simple as that.
########################################
$config['server']['internal_url'] = '/var/www/music/' . $testing . '';
$config['server']['secret'] = "";
$config['server']['streaming'] = "http://music-stream.burst-dev.com";
$config['server']['uri_prefix'] = "/stream/";

########## Paypal Instant Payment Notification ##########
# Instantly inputs all data into the database;
# instant payment processing.
############################################################
$config['pp']['pay_to'] = 'accounting@burst-dev.com';
$config['basicSlots'] = 2;
	
########## Database Information ##########
# The MySQL database information needed
# to store user information.
########################################
$config['db']['host'] = "localhost";
$config['db']['username'] = 'music';
$config['db']['password'] = '';
$config['db']['table'] = 'music';	

########## DO NOT TOUCH ANYTHING BELOW HERE ##########
# Simply logs into Facebook, the MySQL database
# and sees if the Facebook user is an administrator.
##################################################

if ($pre !== 'skip_fbapi') {
	$facebook = new Facebook($config['fb']['key'], $config['fb']['secret']);
	if($pre !== 'skip_login')
	{
		if (isset($fb_page_id)) 
		{
			$user = $fb_page_id;
		} 
		else
		{	
			$user = $facebook->require_login();
			if ($user == NULL OR $user == 0) {
				$user = $_POST['fb_sig_user'];
			}
		}
	}
}

$db = new BurstMySQL ($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['table']);	
$mysqli = new mysqli($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['table']);

?>
