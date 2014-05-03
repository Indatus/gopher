<?php namespace Indatus\Callbot\Factories;

use Aws\S3\S3Client;
use Indatus\Callbot\Config;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\AwsS3;

/**
 * This class is a factory for creating FileSystem instances
 */
class FileSystemFactory
{
    /**
     * Create a FileSystem instance
     *
     * @param string $driver
     *
     * @return FileSystem
     */
    public function make($driver)
    {
        switch ($driver) {
            case 'S3':

                $client = S3Client::factory([
                    'key' => Config::get('fileSystem.credentials.accessKey'),
                    'secret' => Config::get('fileSystem.credentials.secretKey')
                ]);

                $parts = explode('/', Config::get('fileSystem.uploadDir'));
                $bucket = end($parts);

                return new FileSystem(new AwsS3($client, $bucket));
                break;

            default:
                throw new \InvalidArgumentException;
                break;
        }
    }
}