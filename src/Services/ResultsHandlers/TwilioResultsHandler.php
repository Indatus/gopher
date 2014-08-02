<?php namespace Indatus\Gopher\Services\ResultsHandlers;

use Indatus\Gopher\Contracts\ResultsHandlerInterface;

class TwilioResultsHandler extends ResultsHandler implements ResultsHandlerInterface
{
    /**
     * Build the rows of data to be displayed in table format
     *
     * @param  array $results
     *
     * @return array
     */
    protected function buildRows($results)
    {
        $rows = array();

        foreach ($results as $call) {

            $startTime = $this->formatDate($call->start_time);

            $endTime = $this->formatDate($call->end_time);

            $rows[] = [
                $startTime,
                $endTime,
                $call->from_formatted,
                $call->to_formatted,
                $call->status,
                $call->sid
            ];

        }

        return $rows;
    }
}