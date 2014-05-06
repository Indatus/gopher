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
     * Create a CallServiceInterface implementation
     *
     * @param string $default
     *
     * @return CallServiceInterface
     */
    public function make($default)
    {
        $connection = Config::getConnection('callservice', $default);

        switch ($connection['driver']) {
            case 'twilio':
                return new TwilioCallService(
                    new Services_Twilio(
                        $connection['sid'],
                        $connection['token']
                    )
                );
                break;

            default:
                throw new \InvalidArgumentException('Unsupported driver provided');
                break;
        }
    }
}