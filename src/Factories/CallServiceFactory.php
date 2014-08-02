<?php namespace Indatus\Gopher\Factories;

use Services_Twilio;
use Indatus\Gopher\Config;
use Indatus\Gopher\TwilioCallService;

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
        } // @codeCoverageIgnore
    } // @codeCoverageIgnore
}