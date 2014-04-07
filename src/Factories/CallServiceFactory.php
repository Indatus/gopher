<?php namespace Indatus\Callbot\Factories;

use Services_Twilio;
use Indatus\Callbot\Config;
use Indatus\Callbot\TwilioCallService;

/**
 * This class is a factory for creating CallServiceInterface implementations
 */
class CallServiceFactory
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
     * Create a CallServiceInterface implementation
     *
     * @param string $driver Driver type
     *
     * @return CallServiceInterface
     */
    public function make($driver)
    {
        switch ($driver) {
            case 'twilio':
                return new TwilioCallService(
                    new Services_Twilio(
                        $this->config->get('callService.credentials.accountSid'),
                        $this->config->get('callService.credentials.authToken')
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