<?php

namespace Indatus\Callbot\Factories;

use S3;

class FileStoreFactory
{
    public function make($driver)
    {
        switch ($driver) {
            case 'S3':
                return new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);
                break;

            default:
                throw new \InvalidArgumentException;
                break;
        }
    }
}