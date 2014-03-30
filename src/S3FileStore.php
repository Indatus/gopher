<?php

namespace Indatus\Callbot;

use S3;
use Indatus\Callbot\Contracts\FileStoreInterface;

class S3FileStore implements FileStoreInterface
{
    public function __construct(S3 $fileStore)
    {
        $this->fileStore = $fileStore;
    }
}