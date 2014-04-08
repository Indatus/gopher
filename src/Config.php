<?php namespace Indatus\Callbot;

/**
 * This class is used to interact with the configuration file.
 */
class Config
{
    /**
     * Array of config information
     *
     * @var array
     */
    protected $config;

    /**
     * Constructor pulls in config array
     */
    public function __construct()
    {
        $this->config = require __DIR__ . '/../config.php';
    }

    /**
     * Get an element from the config array
     *
     * @param string $key Config key to get
     *
     * @return mixed
     */
    public function get($key)
    {
        $keys = explode('.', $key);
        $numKeys = count($keys);

        switch ($numKeys) {
            case 3:
                return $this->config[$keys[0]][$keys[1]][$keys[2]];
                break;
            case 2:
                return $this->config[$keys[0]][$keys[1]];
                break;
            case 1:
                return $this->config[$key];
                break;
            default:
                return null;
                break;
        }
    }
}