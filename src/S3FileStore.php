<?php namespace Indatus\Callbot;

use S3;
use Indatus\Callbot\Config;
use Indatus\Callbot\Contracts\FileStoreInterface;

/**
 * This class is an Amazon S3 implementation of the FileStoreInterface
 */
class S3FileStore implements FileStoreInterface
{
    /**
     * S3 instance
     *
     * @var S3
     */
    protected $s3;

    /**
     * Config instance
     *
     * @var Indatus\Callbot\Config
     */
    protected $config;

    /**
     * Constructor injects dependancies
     *
     * @param S3     $s3     S3 instance
     * @param Config $config Config instance
     *
     */
    public function __construct(S3 $s3, Config $config)
    {
        $this->s3 = $s3;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function put($script, $fileName)
    {
        $parts = explode('/', $this->config->get('fileStore.uploadDir'));

        $bucket = end($parts);

        return $this->s3->putObject(
            $script,
            $bucket,
            $fileName,
            S3::ACL_PUBLIC_READ,
            array(),
            array('Content-Type' => 'text/xml')
        );
    }
}