<?php
// just an easier way to hnadle fql queries
// this way we can change this up if they method
// facebook uses changes. this will also handle
// fql_multiquries if $query is an array()
function fql_query($query, $facebook)
{
	if (is_array($query))
	{
		$string = '{';
		for ($i = 0; $i < count($query); $i++)
		{
			$j = $i+1;
			$string .= '"query' . $j . '":"' . $query[$i] . '",';
		}
		
		$string = substr($string, 0, -1);
		$string .= '}';
		
		return $facebook->api_client->fql_multiquery($string);
		// $req_array = array('method' => 'fql.multiquery', 'queries' => $string);
	}
	else
	{
		return $facebook->api_client->fql_query($query);
		// $req_array = array('method' => 'fql.query', 'query' => $query);
	}
}

function pages($fb_page_id) {
	if($fb_page_id !== 0 AND $fb_page_id !== NULL) { return '&fb_page_id=' . $fb_page_id . ''; }
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

function error($header,$message='') 
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

function success($header,$message='') 
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

function explanation($header,$message='') 
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
