<?php

use Indatus\Callbot\Factories\CallServiceFactory;

class CallServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->callServiceFactory = new CallServiceFactory;
        parent::setUp();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCallServiceThrowsException()
    {
        $this->callServiceFactory->make('foo');
    }

    public function testMakeTwilioCallService()
    {
        $twilio = $this->callServiceFactory->make('twilio');

        $this->assertInstanceOf('Indatus\Callbot\TwilioCallService', $twilio);
    }
}