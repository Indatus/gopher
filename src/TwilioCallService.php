<?php

namespace Indatus\Callbot;

use Services_Twilio;
use Indatus\Callbot\Contracts\CallServiceInterface;

class TwilioCallService implements CallServiceInterface
{
    public function __construct(Services_Twilio $twilio)
    {
        $this->twilio = $twilio;
    }

    public function call($from, $to, $callbackUrl)
    {
        return $this->twilio->account->calls->create(
            $from,
            $to,
            $callbackUrl,
            array('Method' => 'GET')
        );
    }
}