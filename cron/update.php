<?php
$pre = 'skip_fbapi';
include '../include/config.php';
?>

<?php
$system = system("svn cleanup; svn update " . $config['server']['internal_url'] . "");
?>