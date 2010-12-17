<?php
if (isset($_GET['error']))
{
   echo '<div style="margin-top: -10px">';
   $message = urldecode($_GET['error']);
   error('ERROR: ' . $message . '');
   echo '</div>';
}
?>
