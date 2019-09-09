<?php
/**
 * Created by PhpStorm.
 * User: dwiagus
 * Date: 9/9/19
 * Time: 11:28 AM
 */

namespace CephUny;
use Aws\Result;
use Aws\S3\S3Client;

class Client implements ClientInterface
{

    private $s3Client;

    public function __construct($client = array())
    {
        $this->s3Client = new S3Client($client);
    }

    public function createBucket(string $name): Result
    {
        return $this->s3Client->createBucket([
            'Bucket' => $name,
        ]);
    }

    public function deleteBucket(string $name)
    {
        return $this->s3Client->deleteBucket([
            'Bucket' => $name,
        ]);
    }

    public function getBuckets()
    {
        return $this->s3Client->listBuckets();
    }

    public function getFile(string $bucket, string $name)
    {
        return $this->s3Client->getObject([
            'Bucket' => $bucket,
            'Key' => $name,
        ]);
    }

    public function putFromUrl(string $bucket, string $file, string $name = null)
    {
        if (is_null($name) || empty($name)) {
            $name = basename($file);
        }
        return $this->s3Client->putObject([
            'Bucket' => $bucket,
            'Key' => $name,
            'SourceFile' => $file,
            'ACL' => 'public-read'
        ]);
    }

    public function putFromFile(string $bucket, string $file, string $name = null)
    {
        if (is_null($name) || empty($name)) {
            $name = basename($file);
        }
        return $this->s3Client->putObject([
            'Bucket' => $bucket,
            'Key' => $name,
            'Body' => fopen($file,'r'),
            'ACL' => 'public-read'
        ]);
    }



    public function removeFile(string $bucket, string $name)
    {
        return $this->s3Client->deleteObject([
            'Bucket' => $bucket,
            'Key' => $name,
        ]);
    }

    public function moveFile(string $sourceBucket,
                             string $sourceName,
                             string $destinationBucket,
                             string $destinationName = null)
    {
        if (is_null($destinationName) || empty($destinationName)) {
            $destinationName = $sourceName;
        }
        return $this->s3Client->copyObject([
            'Bucket' => $sourceBucket,
            'Key' => $sourceName,
            'CopySource' => "{$destinationBucket}/{$destinationName}",
        ]);
    }

    public function downloadFile(string $bucket, string $name)
    {
        $result = $this->s3Client->getObject([
            'Bucket' => $bucket,
            'Key' => $name,
        ]);
        header("Content-Type: {$result['ContentType']}");
        return $result['Body'];
    }
}