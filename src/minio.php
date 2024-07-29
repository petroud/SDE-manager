<?php
// Include the SDK using the Composer autoloader
date_default_timezone_set('Europe/Athens');
require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
  'version' => 'latest',
  'region'  => 'us-east-1',
  'endpoint' => 'http://10.10.10.3:9000',
  'use_path_style_endpoint' => true,
  'credentials' => [
     'key'    => 'bWCy7NfDu7IfmjEpVgpi',
     'secret' => '',
 ],
]);

function putObject($s3, $key, $content){
     // Send a PutObject request and get the result object.
     $insert = $s3->putObject([
          'Bucket' => 'sde-manager',
          'Key'    => $key,
          'Body'   => $content
     ]);
}


function getObject($s3, $key){
     // Download the contents of the object.
     $retrieve = $s3->getObject([
     'Bucket' => 'sde-manager',
     'Key'    => $key,
     ]);
     return $retrieve;
}
