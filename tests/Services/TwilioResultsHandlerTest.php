<?php

use Indatus\Gopher\Services\ResultsHandlers\TwilioResultsHandler;

class TwilioResultsHandlerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->resultsHandler = new TwilioResultsHandler;
        $this->tableHelper = Mockery::mock(
            'Symfony\Component\Console\Helper\TableHelper'
        );
        $this->output = Mockery::mock(
            'Symfony\Component\Console\Output\OutputInterface'
        );
        parent::setUp();
    }

    public function testDisplayTable()
    {
        $this->tableHelper
            ->shouldReceive('setHeaders')
            ->with(['Start Time', 'End Time', 'From', 'To', 'Status','Call ID'])
            ->shouldReceive('setRows')
            ->with([
                [
                    '2005-08-15 11:00:00',
                    '2005-08-15 11:02:00',
                    '(555) 123-4567',
                    '(555) 123-4568',
                    'completed',
                    '1234567890'
                ]
            ])
            ->shouldReceive('render')
            ->with($this->output);

        $result = new stdClass;
        $result->start_time = 'Mon, 15 Aug 2005 15:00:00 +0000';
        $result->end_time = 'Mon, 15 Aug 2005 15:02:00 +0000';
        $result->from_formatted = '(555) 123-4567';
        $result->to_formatted = '(555) 123-4568';
        $result->status = 'completed';
        $result->sid = '1234567890';

        $results[] = $result;

        $this->resultsHandler->displayTable(
            $this->tableHelper,
            $results,
            $this->output
        );
    }
}