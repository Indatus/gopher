<?php namespace Indatus\Callbot\Factories;

use Aws\S3\S3Client;
use Indatus\Callbot\Config;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\AwsS3 as Adapter;

/**
 * This class is a factory for creating FileStoreInterface implementations
 */
class FileStoreFactory
{
    /**
     * Config instance
     *
     * @var Indatus\Callbot\Config
     */
    protected $config;

    /**
     * Constructor injects dependancies
     *
     * @param Config $config Config instance
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Create a FileStoreInterface implementation
     *
     * @param string $driver Driver type
     *
     * @return FileStoreInterface
     */
    public function make($driver)
    {
        switch ($driver) {
            case 'S3':

                $client = S3Client::factory([
                    'key'    => $this->config->get('fileStore.credentials.accessKey'),
                    'secret' => $this->config->get('fileStore.credentials.secretKey')
                ]);

                return new FileSystem(
                    new Adapter(
                        $client,
                        $this->config->get('fileStore.bucketName')
                    )
                );

                break;

            default:
                throw new \InvalidArgumentException;
                break;
        }
    }
}