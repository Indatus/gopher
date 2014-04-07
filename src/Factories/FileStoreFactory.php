<?php namespace Indatus\Callbot\Factories;

use S3;
use Indatus\Callbot\Config;
use Indatus\Callbot\S3FileStore;

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
                return new S3FileStore(
                    new S3(
                        $this->config->get('fileStore.credentials.accessKey'),
                        $this->config->get('fileStore.credentials.secretKey')
                    ),
                    $this->config
                );
                break;

            default:
                throw new \InvalidArgumentException;
                break;
        }
    }
}