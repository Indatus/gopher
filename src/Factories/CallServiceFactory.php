<?php

namespace Indatus\Callbot\Factories;

use Services_Twilio;

class CallServiceFactory
{
    public function make($driver)
    {
        switch ($driver) {
            case 'twilio':
                return new Services_Twilio(TW_ACCOUNT_SID, TW_AUTH_TOKEN);
                break;

            default:
                throw new \InvalidArgumentException;
                break;
        }
    }
}