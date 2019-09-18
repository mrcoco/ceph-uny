<?php
/**
 * Created by PhpStorm.
 * User: dwiagus
 * Date: 9/9/19
 * Time: 11:29 AM
 */

namespace CephUny;


interface ClientInterface
{
    public function createBucket(string $name);
    public function deleteBucket(string $name);
    public function getBuckets();
    public function getFile(string $bucket, string $name);
    public function getUrl(string $bucket, string $name);
    public function putFromUrl(string $bucket, string $file, string $name = null);
    public function putFromFile(string $bucket, string $file, string $name = null);
    public function removeFile(string $bucket, string $name);
    public function downloadFile(string $bucket, string $name);
    public function listFile(string $bucket);
    public function moveFile(
        string $sourceBucket,
        string $sourceName,
        string $destinationBucket,
        string $destinationName = null
    );
    public function preSignUrl(string  $bucket, string $name, string $timeout);
}