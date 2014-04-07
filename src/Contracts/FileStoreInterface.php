<?php namespace Indatus\Callbot\Contracts;

interface FileStoreInterface
{
    /**
     * Upload a call script to remote file store
     *
     * @param string $script   Contents of script
     * @param string $fileName Name of remote file
     *
     * @return boolean
     */
    public function put($script, $fileName);
}