<?php

use Indatus\Callbot\TwilioCallService;

class TwilioCallServiceTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->twilio = Mockery::mock('Services_Twilio');
        $this->config = Mockery::mock('Indatus\Callbot\Config');
        $this->calls = Mockery::mock('Services_Twilio_Rest_Calls');
        $this->account = Mockery::mock('Services_Twilio_Rest_Account');

        $this->callService = new TwilioCallService($this->twilio, $this->config);

        parent::setUp();
    }

    public function tearDown()
    {
       Mockery::close();
    }

    public function testCall()
    {
        $callResult = new stdClass;
        $callResult->sid = 1;

        $this->twilio->shouldReceive('getSubresources')
            ->with('account')
            ->andReturn($this->account);

        $this->account->shouldReceive('getSubresources')
            ->with('calls')
            ->andReturn($this->calls);

        $this->config->shouldReceive('get')
            ->once()
            ->with('fileStore.uploadDir')
            ->andReturn('http://www.example.com/foo');

        $this->calls->shouldReceive('create')
            ->once()
            ->with('5551234567', '5551234567', 'http://www.example.com/foo/foo.xml', array('Method' => 'GET'))
            ->andReturn($callResult);

        $callId = $this->callService->call('5551234567', '5551234567', 'foo.xml');

        $this->assertEquals(1, $callId);
    }

    public function testGetDetails()
    {
        $this->twilio->shouldReceive('getSubresources')
            ->with('account')
            ->andReturn($this->account);

        $this->account->shouldReceive('getSubresources')
            ->with('calls')
            ->andReturn($this->calls);

        $this->calls->shouldReceive('get')
            ->once()
            ->with(1)
            ->andReturn('foo');

        $results = $this->callService->getDetails([1]);

        $this->assertEquals('foo', $results[0]);
    }

    public function testGetFilteredDetails()
    {
        $this->twilio->shouldReceive('getSubresources')
            ->with('account')
            ->andReturn($this->account);

        $this->account->shouldReceive('getSubresources')
            ->with('calls')
            ->andReturn($this->calls);

        $this->calls->shouldReceive('getIterator')
            ->once()
            ->with(0, 50, ['To' => '5551234567'])
            ->andReturn('foo');

        $this->callService->addFilter('to', '5551234567');
        $results = $this->callService->getFilteredDetails();

        $this->assertEquals('foo', $results);
    }
}