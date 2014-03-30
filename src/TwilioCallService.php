<?php

namespace Indatus\Callbot;

use Services_Twilio;
use Indatus\Callbot\Contracts\CallServiceInterface;

class TwilioCallService implements CallServiceInterface
{
    public function __construct(Services_Twilio $callService)
    {
        $this->callService = $callService;
    }
}