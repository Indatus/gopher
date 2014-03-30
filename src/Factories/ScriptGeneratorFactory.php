<?php

namespace Indatus\Callbot\Factories;

use Services_Twilio_Twiml;
use Indatus\Callbot\TwimlScriptGenerator;

class ScriptGeneratorFactory
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function make($driver)
    {
        switch ($driver) {
            case 'twilio':
                return new TwimlScriptGenerator(new Services_Twilio_Twiml);
                break;

            default:
                throw new \InvalidArgumentException;
                break;
        }
    }
}