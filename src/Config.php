<?php

namespace Indatus\Callbot;

class Config
{
    protected $config;

    public function __construct()
    {
        $this->config = require 'config.php';
    }

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