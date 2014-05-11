<?php namespace Indatus\Callbot\Services\ResultsHandlers;

use Indatus\Callbot\Config;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ResultsHandler
{
    /**
     * Display the results of calls in table format
     *
     * @param  TableHelper     $table
     * @param  mixed           $results
     * @param  OutputInterface $output
     *
     * @return string
     */
    public function displayTable(TableHelper $table, $results, OutputInterface $output)
    {
        $table->setHeaders(
            ['Start Time', 'End Time', 'From', 'To', 'Status','Call ID']
        );

        $rows = $this->buildRows($results);

        if (!empty($rows)) {

            $table->setRows($rows);

        }

        $table->render($output);
    }

    /**
     * Format the date for display
     *
     * @param string $date
     *
     * @return DateTime
     */
    protected function formatDate($date)
    {
        return (new \DateTime($date))
            ->setTimezone(new \DateTimeZone(Config::get('callservice.timezone')))
            ->format('Y-m-d H:i:s');
    }
}