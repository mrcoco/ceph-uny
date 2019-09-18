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

    /**
     * Client constructor.
     * @param array $client
     */
    public function __construct($client = array())
    {
        $this->s3Client = new S3Client($client);
    }

    /**
     * Create Bucket
     * @param string $name
     * @return Result
     */
    public function createBucket(string $name): Result
    {
        return $this->s3Client->createBucket([
            'Bucket' => $name,
        ]);
    }

    /**
     * Delete existing Bucket
     * @param string $name
     * @return Result
     */
    public function deleteBucket(string $name): Result
    {
        return $this->s3Client->deleteBucket([
            'Bucket' => $name,
        ]);
    }

    /**
     * get list Bucket
     * @return Result
     */
    public function getBuckets(): Result
    {
        return $this->s3Client->listBuckets();
    }

    /**
     * Get file in buket
     * @param string $bucket
     * @param string $name
     * @return Result
     */
    public function getFile(string $bucket, string $name): Result
    {
        return $this->s3Client->getObject([
            'Bucket' => $bucket,
            'Key' => $name,
        ]);
    }

    /**
     * put file from url into bucket
     * @param string $bucket
     * @param string $file
     * @param string|null $name
     * @return Result
     */
    public function putFromUrl(string $bucket, string $file, string $name = null): Result
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

    /**
     * put file into bucket
     * @param string $bucket
     * @param string $file
     * @param string|null $name
     * @return Result
     */
    public function putFromFile(string $bucket, string $file, string $name = null): Result
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


    /**
     * Remove object file in bucket
     * @param string $bucket
     * @param string $name
     * @return Result
     */
    public function removeFile(string $bucket, string $name): Result
    {
        return $this->s3Client->deleteObject([
            'Bucket' => $bucket,
            'Key' => $name,
        ]);
    }

    /**
     * move object file to another bucket
     * @param string $sourceBucket
     * @param string $sourceName
     * @param string $destinationBucket
     * @param string|null $destinationName
     * @return Result
     */
    public function moveFile(string $sourceBucket,
                             string $sourceName,
                             string $destinationBucket,
                             string $destinationName = null): Result
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

    /**
     * Download stream object file
     * @param string $bucket
     * @param string $name
     * @return mixed
     */
    public function downloadFile(string $bucket, string $name)
    {
        $result = $this->s3Client->getObject([
            'Bucket' => $bucket,
            'Key' => $name,
        ]);
        header("Content-Type: {$result['ContentType']}");
        return $result['Body'];
    }

    /**
     * Get generate url object file
     * @param string $bucket
     * @param string $name
     * @return string
     */
    public function getUrl(string $bucket, string $name)
    {
        return $this->s3Client->getObjectUrl($bucket,$name);
    }

    /**
     * get generate url object with expired
     * @param string $bucket
     * @param string $name
     * @param string $timeout
     * @return string
     */
    public function preSignUrl(string  $bucket, string $name, string $timeout)
    {
        $cmd = $this->s3Client->getCommand('GetObject',[
            'Bucket' => $bucket,
            'Key' => $name,
            ]);
        $request = $this->s3Client->createPresignedRequest($cmd,$timeout);
        return (string) $request->getUri();

    }

    public function listFile(string $bucket)
    {
        $object = $this->s3Client->listObjects(['bucket' => $bucket]);
        $list = [];
        foreach ($object['Content'] as $item) {
            $item[] = $item['Key'];
        }
        return $list;
    }
}