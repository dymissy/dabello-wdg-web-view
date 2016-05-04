<?php

namespace DabelloWdg;

use Aws\S3\S3Client;
use Symfony\Component\Finder\Finder;

class ImageFinder
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

    protected function getFullImages()
    {
        $finder = new Finder();
        $finder->in($this->fullPath);

        return $finder;
    }

    protected function getDownloadableUrl($path)
    {
        $relativePath = str_replace("s3://" . $this->bucketName . "/", "", $path);

        return $this->s3client->getObjectUrl($this->bucketName, $relativePath, '+60 minutes');
    }

    protected function getThumbByFullImage($fullPath)
    {
        $thumbPath = str_replace("full/", "thumb/", $fullPath);

        if(!file_exists($thumbPath)) {
            return $fullPath;
        }

        return $thumbPath;
    }

    public function render()
    {
        $images = [];

        $finder = $this->getFullImages();
        foreach($finder as $file) {
            $images[] = [
                'full' => $this->getDownloadableUrl($file->getPathname()),
                'thumb' => $this->getDownloadableUrl(
                    $this->getThumbByFullImage($file->getPathname())
                ),
            ];
        }

        return $images;
    }
}
