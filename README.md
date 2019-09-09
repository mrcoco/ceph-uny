# ceph-uny
Provides a CephClient for storing files UNY cloud object storage
## install via composer 
```sh
composer require mrcoco/ceph-uny dev-master
```

```php
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
```

### create bucket;
```php
$client->createBucket($bucketName);
</code>

### Dump all existing buckets.
```php
foreach ($client->getBuckets() as $bucket) {
 var_dump($bucket);
}
```

### put direct file
```php
$file = "uny.png";
try{
    $client->putFromFile($bucketName,$file);
}catch (Aws\S3\Exception\S3Exception $e){
    echo "There was an error uploading the file.\n";
}
```

### put direct file return URL
```php
$file = "uny.png";
try{
    $result = $client->putFromFile($bucketName,$file);
    echo $result['ObjectURL'].PHP_EOL;
    
}catch (Aws\S3\Exception\S3Exception $e){
    echo "There was an error uploading the file.\n";
}
```

### Get file from bucket
```php
$fileName = basename($file);
$result = $client->getFile($bucketName, $fileName);
var_dump($result);
```