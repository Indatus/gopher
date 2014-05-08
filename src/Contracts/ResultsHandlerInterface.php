<?php namespace Indatus\Callbot\Contracts;

use Symfony\Component\Console\Helper\TableHelper;

interface ResultsHandlerInterface
{
    public function buildRows($results);
}