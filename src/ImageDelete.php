<?php

namespace DabelloWdg;

use Aws\S3\S3Client;
use Symfony\Component\Finder\Finder;

class ImageDelete
{
    const BUCKET_NAME = 'dabello-wdg';
    const BASE_PATH = 's3://%s/photos';
    const FULL_PATH = 's3://%s/photos/full';
    const THUMB_PATH = 's3://%s/photos/thumb';

    /**
     * @var S3Client
     */
    private $s3client;

    /**
     * @var string
     */
    private $bucketName;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var string
     */
    private $fullPath;

    /**
     * @var string
     */
    private $thumbPath;

    public function __construct(S3Client $s3client, $bucketName = null)
    {
        $this->s3client = $s3client;
        $this->s3client->registerStreamWrapper();
        $this->bucketName = $bucketName;

        if (!$bucketName) {
            $this->bucketName = self::BUCKET_NAME;
        }

        $this->basePath = sprintf(self::BASE_PATH, $this->bucketName);
        $this->fullPath = sprintf(self::FULL_PATH, $this->bucketName);
        $this->thumbPath = sprintf(self::THUMB_PATH, $this->bucketName);
    }

    public function delete($filename)
    {
        $path = sprintf("%s/%s", $this->fullPath, $filename);

        if(!file_exists($path)) {
            throw new \RuntimeException('File not found');
        }

        if(!unlink($path)) {
            throw new \RuntimeException('An error occurred while trying to delete the file');
        }

        return true;
    }
}
