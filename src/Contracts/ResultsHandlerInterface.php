<?php namespace Indatus\Callbot\Contracts;

use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Output\OutputInterface;

interface ResultsHandlerInterface
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
    public function displayTable(TableHelper $table, $results, OutputInterface $output);
}