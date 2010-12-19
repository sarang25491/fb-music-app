<?php
//sjlu: 12-19-2010 - third party id's need to be implemented before 1-1-2011
//we are assuming that the user needs to visit this page to order slots... and that's when we'll index the third party id

$thirdPartyId = $facebook->api_client->fql_query('SELECT third_party_id FROM user WHERE uid=' . $user . '');
//$thirdPartyId = $facebook->api(array('method' => 'fql.query', query => 'SELECT third_party_id FROM user WHERE uid=' . $user . '')); 
$thirdPartyId = $thirdPartyId[0]['third_party_id'];

$db->Raw("UPDATE `userdb_users` SET `third_party_id`='$thirdPartyId' WHERE `user`='$_POST[fb_sig_user]'");
?>

<center>
   <fb:iframe src="http://www.offers-kitnmedia.com/super/offers?h=cxkot.20309361680&uid=<?php echo $thirdPartyId; ?>&fb_hash=1" frameborder="0" width="100%" height="1700" scrolling="no"></fb:iframe>
</center>
