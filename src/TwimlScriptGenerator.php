<?php

namespace Indatus\Callbot;

use Services_Twilio_Twiml;
use Indatus\Callbot\Contracts\ScriptGeneratorInterface;

class TwimlScriptGenerator implements ScriptGeneratorInterface
{
    public function __construct(Services_Twilio_Twiml $twiml)
    {
        $this->twiml = $twiml;
    }

    public function say($text, array $options = array())
    {
        $this->twiml->say($text, $options);
    }

    public function pause(array $options)
    {
        $this->twiml->pause($options);
    }

    public function parseMessage(array $message)
    {
        foreach ($message as $content) {

            $options = array();

            if (array_key_exists('options', $content)) {
                $options = $content['options'];
            }

            if (array_key_exists('text', $content)) {
                $this->$content['verb']($content['text'], $options);
            } else {
                $this->$content['verb']($options);
            }


        }
    }

    public function getScript()
    {
        return sprintf('%s', $this->twiml);
    }
}