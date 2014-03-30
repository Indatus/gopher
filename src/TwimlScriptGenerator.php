<?php

namespace Indatus\Callbot;

use Indatus\Callbot\Contracts\ScriptGeneratorInterface;

class TwimlScriptGenerator implements ScriptGeneratorInterface
{
    public function __construct(Services_Twilio_Twiml $twiml)
    {
        $this->twiml = $twiml;
    }
}