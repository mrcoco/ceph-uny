<?php
/**
 * Created by PhpStorm.
 * User: dwiagus
 * Date: 9/9/19
 * Time: 11:40 AM
 */


include 'vendor/autoload.php';

use CephUny\Client;
$config = array(
    'version' => 'latest',
    'region'  => '',
    'endpoint' => 'AWS_HOST',
    'credentials' => array(
        'key' => 'AWS_KEY',
        'secret' => 'AWS_SECRET_KEY',
    )
);
$client = new Client($config);
$bucketName = 'my-bucket';

// create bucket;
$client->createBucket($bucketName);

// Dump all existing buckets.
foreach ($client->getBuckets() as $bucket) {
 var_dump($bucket);
}
// put direct file
$file = "uny.png";
try{
    $client->putFromFile($bucketName,$file);
}catch (Aws\S3\Exception\S3Exception $e){
    echo "There was an error uploading the file.\n";
}