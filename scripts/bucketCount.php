<?php

include '../include/aws/sdk.class.php';

$s3 = new AmazonS3();

var_dump($s3->get_bucket_object_count('fb-music'));

?>
