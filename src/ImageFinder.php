<?php

namespace DabelloWdg;

use Aws\S3\S3Client;
use Symfony\Component\Finder\Finder;


class ImageFinder
{
    const BUCKET_NAME = 'dabello-wdg';
    const BASE_PATH = 's3://dabello-wdg/photos';
    const FULL_PATH = 's3://dabello-wdg/photos/full';
    const THUMB_PATH = 's3://dabello-wdg/photos/thumb';

    /**
     * @var S3Client
     */
    private $s3client;

    public function __construct(S3Client $s3client)
    {
        $this->s3client = $s3client;
        $this->s3client->registerStreamWrapper();
    }

    protected function getFullImages()
    {
        $finder = new Finder();
        $finder->in(self::FULL_PATH);

        return $finder;
    }

    protected function getDownloadableUrl($path)
    {
        $relativePath = str_replace("s3://" . self::BUCKET_NAME . "/", "", $path);
        
        return $this->s3client->getObjectUrl(self::BUCKET_NAME, $relativePath, '+60 minutes');
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
