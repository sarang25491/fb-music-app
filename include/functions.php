<?php
function pages($fb_page_id) {
	if($fb_page_id !== 0 AND $fb_page_id !== NULL) { echo '&fb_page_id=' . $fb_page_id . ''; }
}

function execution_start()
{    
    $iMicrotime = microtime();
    $iMicrotime = explode(' ', $iMicrotime);
    $GLOBALS['start_exec'] = $iMicrotime[1] + $iMicrotime[0];
}

function execution_stop()
{
    $iMicrotime = microtime();
    $iMicrotime = explode(' ', $iMicrotime);
    $GLOBALS['end_exec'] = $iMicrotime[1] + $iMicrotime[0];
}

function disk_percentage_used($i) {
	$disk_total = disk_total_space("" . $config['server']['internal_url'] . "users/" . $i . "/");
	$disk_free = disk_free_space("" . $config['server']['internal_url'] . "users/" . $i . "/");
	
	return round(100-(($disk_free/$disk_total)*100), 1);
}

function error($header,$message) 
{
	echo '<div style="padding: 10px;">';
	if(!is_null($message))
	{
		echo("<fb:error><fb:message>$header</fb:message>$message</fb:error>");
	}
	else
	{
		echo("<fb:error><fb:message>$header</fb:message></fb:error>");
	}
	echo '</div>';
}

function success($header,$message) 
{
	echo '<div style="padding: 10px;">';
	if(!is_null($message))
	{
		echo("<fb:success><fb:message>$header</fb:message>$message</fb:success>");
	}
	else
	{
		echo("<fb:success><fb:message>$header</fb:message></fb:success>");
	}
	echo '</div>';
}

function explanation($header,$message) 
{
	echo '<div style="padding: 10px;">';
	if(!is_null($message))
	{
		echo("<fb:explanation><fb:message>$header</fb:message>$message</fb:explanation>");
	}
	else
	{
		echo("<fb:explanation><fb:message>$header</fb:message></fb:explanation>");
	}
	echo '</div>';
}

function redirect($url) {
	echo("<fb:redirect url='$url' />");
}

function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}

function addslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('addslashes_deep', $value) :
                addslashes($value);

    return $value;
}
?>
