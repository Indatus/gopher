<?php namespace Indatus\Callbot\Commands;

use Indatus\Callbot\Commands\CallCommand;
use Indatus\Callbot\Factories\CallServiceFactory;
use Indatus\Callbot\Config;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CallStatusCommand extends CallCommand{

    protected function configure()
    {
        $this
            ->setName('call:status')
            ->setDescription('Get the status of outgoing calls')
            ->addOption('sid', null , InputOption::VALUE_REQUIRED, 'Get the status for calls with the specified SIDs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('sid')) {

            $callIds = explode(',', $input->getOption('sid'));

            $this->displayResults($output, $callIds);

        }
    }
}