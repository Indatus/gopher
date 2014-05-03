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
                        Config::get('callService.credentials.accountSid'),
                        Config::get('callService.credentials.authToken')
                    )
                );
                break;

            default:
                throw new \InvalidArgumentException;
                break;
        }
    }
}