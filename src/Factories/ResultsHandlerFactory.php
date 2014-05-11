<?php namespace Indatus\Callbot\Factories;

use Indatus\Callbot\Config;
use Indatus\Callbot\Services\ResultsHandlers\TwilioResultsHandler;

class ResultsHandlerFactory
{
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