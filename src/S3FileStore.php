<?php

namespace Indatus\Callbot;

use S3;
use Indatus\Callbot\Config;
use Indatus\Callbot\Contracts\FileStoreInterface;

class S3FileStore implements FileStoreInterface
{
    public function __construct(S3 $s3, Config $config)
    {
        $this->s3 = $s3;
        $this->config = $config;
    }

    public function put($script)
    {
        $this->s3->putObject(
            $script,
            $this->config->get('fileStore.credentials.bucketName'),
            $this->config->get('callDetails.destFile'),
            S3::ACL_PUBLIC_READ,
            array(),
            array('Content-Type' => 'text/xml')
        );
    }
}