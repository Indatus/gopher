<?php namespace Indatus\Callbot\Contracts;

use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Output\OutputInterface;

interface ResultsHandlerInterface
{
    public function displayTable(TableHelper $table, $results, OutputInterface $output);
}