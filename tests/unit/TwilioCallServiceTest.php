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

        $this->calls->shouldReceive('create')
            ->once()
            ->with('5551234567', '5551234567', 'https://s3.amazonaws.com/your-bucket-name/foo.xml', array('Method' => 'GET'))
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
            ->with(0, 50, [
                'To'=>'5551234567',
                'From'=>'5551234568',
                'Status'=>'completed',
                'StartTime'=>'2014-01-01 05:00:00'
            ])
            ->andReturn('foo');

        $this->callService->addFilter('to', '5551234567');
        $this->callService->addFilter('from', '5551234568');
        $this->callService->addFilter('status', 'completed');
        $this->callService->addFilter('on', '2014-01-01');
        $results = $this->callService->getFilteredDetails();

        $this->assertEquals('foo', $results);
    }

    public function testAddAfterFilter()
    {
        $this->twilio->shouldReceive('getSubresources')
            ->with('account')
            ->andReturn($this->account);

        $this->account->shouldReceive('getSubresources')
            ->with('calls')
            ->andReturn($this->calls);

        $this->calls->shouldReceive('getIterator')
            ->once()
            ->with(0, 50, ['StartTime>' => '2014-01-01 05:00:00'])
            ->andReturn('foo');

        $this->callService->addFilter('after', '2014-01-01 00:00:00');
        $results = $this->callService->getFilteredDetails();
        $this->assertEquals('foo', $results);
    }

    public function testAddBeforeFilter()
    {
        $this->twilio->shouldReceive('getSubresources')
            ->with('account')
            ->andReturn($this->account);

        $this->account->shouldReceive('getSubresources')
            ->with('calls')
            ->andReturn($this->calls);

        $this->calls->shouldReceive('getIterator')
            ->once()
            ->with(0, 50, ['StartTime<' => '2014-01-01 05:00:00'])
            ->andReturn('foo');

        $this->callService->addFilter('before', '2014-01-01 00:00:00');
        $results = $this->callService->getFilteredDetails();
        $this->assertEquals('foo', $results);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidFilterThrowsException()
    {
        $this->callService->addFilter('foo', 'bar');
    }
}