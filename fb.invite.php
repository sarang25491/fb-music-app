<?php
$rs = $facebook->api_client->fql_query("SELECT uid FROM user WHERE has_added_app=1 and uid IN (SELECT uid2 FROM friend WHERE uid1 = $user)");
$arFriends = "";

if ($rs)
{
	for ( $i = 0; $i < count($rs); $i++ )
	{
		if ( $arFriends != "" )
			$arFriends .= ",";
	
		$arFriends .= $rs[$i]["uid"];
	}
}

$sNextUrl = urlencode("http://apps.facebook.com/stevenlu/");

$invfbml = <<<FBML
You've been invited to add the Music application! <fb:name uid="$user" firstnameonly="true" shownetwork="false"/> wants you to add Music so you can start adding songs to your profile with your own files and start listening to new music!
<fb:req-choice url="http://www.facebook.com/add.php?api_key=$appapikey&next=$sNextUrl" label="Allow" />
FBML;
?>

<fb:request-form type="Music" action="index.php" content="<?=htmlentities($invfbml)?>" invite="true">
<fb:multi-friend-selector max="20" actiontext="Invite your friends and listen to what they're listening to!" showborder="true" rows="5" exclude_ids="<?=$arFriends?>" />
</fb:request-form>