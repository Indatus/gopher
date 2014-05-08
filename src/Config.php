<?php namespace Indatus\Callbot;

/**
 * This class is used to interact with the configuration file.
 */
class Config
{
    /**
     * Get an element from the config array
     *
     * @param string $key Config key to get
     *
     * @return mixed
     */
    public static function get($key)
    {
        $keys = explode('.', $key);
        $file = array_shift($keys);
        $file = __DIR__ . '/../config/' . $file . '.php';

        if (file_exists($file)) {
            $config = require $file;
        } else {
            throw new \InvalidArgumentException('Invalid config file provided');
        }

        $numKeys = count($keys);

        switch ($numKeys) {
            case 3:
                return $config[$keys[0]][$keys[1]][$keys[2]];
                break;
            case 2:
                return $config[$keys[0]][$keys[1]];
                break;
            case 1:
                return $config[$keys[0]];
                break;
            default:
                throw new \InvalidArgumentException('Invalid config key provided');
                break;
        }
    }

    /**
     * Get a default connection array
     *
     * @param  string $file
     * @param  string $default
     *
     * @return array
     */
    public static function getConnection($file, $default)
    {
        $connections = static::get($file . '.connections');

        if (isset($connections[$default])) {
            return $connections[$default];
        } else {
            throw new \InvalidArgumentException('Invalid connection key provided');
        }
    }

    /**
     * Get the remote filesystem directory
     *
     * @return string
     */
    public static function getRemoteDir()
    {
        $connection = static::getConnection('filesystem', static::get('filesystem.default'));

        switch($connection['driver']) {
            case 's3':
                return 'https://s3.amazonaws.com/' . $connection['bucket'] . '/';
        }
    }
}