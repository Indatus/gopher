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
     * @param string $default
     *
     * @return FileSystem
     */
    public function make($default)
    {
        $connection = Config::getConnection('filesystem', $default);

        switch ($connection['driver']) {
            case 's3':

                $client = S3Client::factory([
                    'key' => $connection['key'],
                    'secret' => $connection['secret']
                ]);

                $bucket = $connection['bucket'];

                return new FileSystem(new AwsS3($client, $bucket));
                break;
        }
    }
}