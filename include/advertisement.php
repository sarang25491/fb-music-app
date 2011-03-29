<?php
// $user_info = $facebook->api_client->users_getInfo($user, array('birthday', 'sex'));
/*
if($user_info[0]['birthday']) {
	$born = $user_info[0]['birthday'];
	
	$dob = explode(' ', $born, 2); $dob2 = explode(', ', $dob[1]);
	$m = $dob[0]; $d = $dob2[0]; $y = $dob2[1];

	if($m == 'January') { $m = '01'; } elseif($m == 'February') { $m = '02'; } elseif($m == 'March') { $m = '03'; } elseif($m == 'April') { $m = '04'; } elseif($m == 'May') { $m = '05'; } elseif($m == 'June') { $m = '06'; } elseif($m == 'July') { $m = '07'; } elseif($m == 'August') { $m = '08'; } elseif($m == 'September') { $m = '09'; } elseif($m == 'October') { $m = '10'; } elseif($m == 'November') { $m = '11'; } elseif($m == 'December') { $m = '12'; }

	if($d < 10) { $d = '0'.$d; }
	
	$years = date("Y") - intval($y);

	$day   = str_pad(intval($d),   2, "0", STR_PAD_LEFT);
	$month = str_pad(intval($m), 2, "0", STR_PAD_LEFT);
	if(intval("$month$day") > intval(date("md")))
	  $years -= 1;
}
*/

/*
$rc_banner_id = '230';
$rc_width = '646';
$rc_height = '64';
try {
include_once('inc.rawclix.php');
} catch (Exception $e) {
	$rc_AdBanner = null;
}
*/

$timestamp = date("mdyHis");

$social_media = '<fb:iframe src="http://www.socialmedia.com/facebook/monetize.php?fmt=canvas&pubid=a0fb5ac60e4cd72075b1337eb6fb4336&bgcolor=F7F7F7&bordercolor=F7F7F7&textcolor=000&linkcolor=3B5998&e" border="0" width="645" height="60" scrolling="no" frameborder="0" />';
$cubics = "<fb:iframe src='http://social.bidsystem.com/displayAd.aspx?pid=14311&appId=43221&plid=15965&adSize=728x90&channel=banner' width='728' height='90' frameborder='0' border='0' scrolling='no'></fb:iframe>";
$zohark = '<script>var zohark_api_key = "cd92203d073c3556a00ca2167c661421";var zohark_background_color = "FFFFFF";var zohark_border_color = "6D84B4";var zohark_text_color = "000000";var zohark_title_color = "0000FF";var zohark_display_url_color = "6D84B4";var zohark_button_color = "6D84B4";var zohark_button_text = "FFFFFF";var zohark_button = "6D84B4-FFFFFF";</script><fb:ref url="http://www.niagaramedia.com/facebook/fbml_ads/?ad_format=646x60" />';
$lookery = '<fb:iframe id="lookery-ad-8169" name="lookery-ad-8169" src="http://ads.lookery.com/ad/?p=7c7945c922cf4735cdf2dbfc2e374c36&z=8169&d=645x60" framespacing="no" frameborder="no" scrolling="no" width="645" height="80"></fb:iframe>';
$super_rewards = '<center><fb:iframe src="http://apps.kitnmedia.com/superrewards/banner_fbml.php?h=utxwppn" frameborder="0" width="648" height="60" scrolling="no" /></center>';
$ad_blade = '<fb:iframe src="http://adblade.com/facebook/monetize.php?affid=397&app_id=1048&pos=1" border="0" width="645" height="60" frameborder="0" hspace="0" vspace="0" marginwidth="0" marginheight="0" scrolling="no" />';
// $tatto_media = '<fb:iframe src="http://banner.resulthost.org/adtag?source_id=5288&width=728&height=90&age=' . $years . '&gender=' . $user_info[0]['sex'] . '&ran=' . $timestamp . '" scrolling="no" frameborder="0" marginheight="0" marginwidth="0" width="728" height="90"></fb:iframe>';
$custom = '<fb:iframe src="http://burst-dev.com/advertisement.html" border="0" width="645" height="60" frameborder="0" hspace="0" vspace="0" marginwidth="0" marginheight="0" scrolling="no">';
$marimedia = '<fb:iframe src="http://ad.yieldmanager.com/st?ad_type=iframe&ad_size=728x90&section=1557280" width="728" height="90" scrolling="no" marginheight="0" marginwidth="0" frameborder="0" vspace="0" hspace="0" />';
$rockyou = '<fb:iframe src="http://burst-dev.com/rockyou.html" width="728" height="90" scrolling="no" marginheight="0" marginwidth="0" frameborder="0" vspace="0" hspace="0" />';
?>


<div style="border-bottom: 0px solid #cccccc; border-top: 0px solid #cccccc; background-color: #F7F7F7; width: 100%; margin-top: 0px;">
<center><?php echo $rockyou; ?></center>
</div>
