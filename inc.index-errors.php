<?php

if (isset($_GET['error']))
{
   $message = urldecode($_GET['error']);
   error($message);
}

?>
