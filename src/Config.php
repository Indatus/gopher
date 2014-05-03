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
        $config = require __DIR__ . '/../config.php';
        $keys = explode('.', $key);
        $numKeys = count($keys);

        switch ($numKeys) {
            case 3:
                return $config[$keys[0]][$keys[1]][$keys[2]];
                break;
            case 2:
                return $config[$keys[0]][$keys[1]];
                break;
            case 1:
                return $config[$key];
                break;
            default:
                return null;
                break;
        }
    }
}