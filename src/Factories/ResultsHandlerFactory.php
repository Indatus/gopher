<?php namespace Indatus\Gopher\Factories;

use Indatus\Gopher\Config;
use Indatus\Gopher\Services\ResultsHandlers\TwilioResultsHandler;

class ResultsHandlerFactory
{
     /**
     * Create a ResultsHandlerInterface implementation
     *
     * @param string $default
     *
     * @return ResultsHandler
     */
    public function make($default)
    {
        $connection = Config::getConnection('callservice', $default);

        switch ($connection['driver']) {
            case 'twilio':
                return new TwilioResultsHandler;
                break;
        } // @codeCoverageIgnore
    } // @codeCoverageIgnore
}