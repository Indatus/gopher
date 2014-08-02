<?php

use Indatus\Gopher\Factories\ResultsHandlerFactory;

class ResultsHandlerFactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->resultsHandlerFactory = new ResultsHandlerFactory;
        parent::setUp();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidResultsHandlerThrowsException()
    {
        $this->resultsHandlerFactory->make('foo');
    }

    public function testMakeTwilioResultsHandler()
    {
        $twilio = $this->resultsHandlerFactory->make('twilio');

        $this->assertInstanceOf(
            'Indatus\Gopher\Services\ResultsHandlers\TwilioResultsHandler',
            $twilio
        );
    }
}