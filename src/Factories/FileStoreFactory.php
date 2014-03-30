<?php

namespace Indatus\Callbot\Factories;

use S3;
use Indatus\Callbot\Config;
use Indatus\Callbot\S3FileStore;

class FileStoreFactory
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function make($driver)
    {
        switch ($driver) {
            case 'S3':
                return new S3FileStore(
                    new S3(
                        $this->config->get('fileStore.credentials.accessKey'),
                        $this->config->get('fileStore.credentials.secretKey')
                    ),
                    new Config
                );
                break;

            default:
                throw new \InvalidArgumentException;
                break;
        }
    }
}