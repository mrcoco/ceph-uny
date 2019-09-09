# ceph-uny
Provides a CephClient for storing files UNY cloud object storage
## install via composer 
composer require mrcoco/ceph-uny dev-master

<code>
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
</code>

### create bucket;
<code>
$client->createBucket($bucketName);
</code>

### Dump all existing buckets.
<code>
foreach ($client->getBuckets() as $bucket) {
 var_dump($bucket);
}
</code>

### put direct file
<code>
$file = "uny.png";
try{
    $client->putFromFile($bucketName,$file);
}catch (Aws\S3\Exception\S3Exception $e){
    echo "There was an error uploading the file.\n";
}
</code>

### put direct file return URL
<code>
$file = "uny.png";
try{
    $result = $client->putFromFile($bucketName,$file);
    echo $result['ObjectURL'].PHP_EOL;
    
}catch (Aws\S3\Exception\S3Exception $e){
    echo "There was an error uploading the file.\n";
}
</code>

### Get file from bucket
<code>
$fileName = basename($file);
$result = $client->getFile($bucketName, $fileName);
var_dump($result);
</code>