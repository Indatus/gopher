<?php

namespace Indatus\Callbot\Factories;

use Services_Twilio;
use Indatus\Callbot\Config;

class CallServiceFactory
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function make($driver)
    {
        switch ($driver) {
            case 'twilio':
                return new Services_Twilio(
                    $this->config->get('callService.credentials.accountSid'),
                    $this->config->get('callService.credentials.authToken')
                );
                break;

            default:
                throw new \InvalidArgumentException;
                break;
        }
    }
}