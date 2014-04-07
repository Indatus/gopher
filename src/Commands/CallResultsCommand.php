<?php namespace Indatus\Callbot\Commands;

use Indatus\Callbot\Commands\CallCommand;
use Indatus\Callbot\Factories\CallServiceFactory;
use Indatus\Callbot\Config;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CallResultsCommand extends CallCommand{

    protected function configure()
    {
        $this
            ->setName('call:results')
            ->setDescription('Get the results of outgoing calls')
            ->addOption('id', null , InputOption::VALUE_REQUIRED, 'Get the results for calls with the specified IDs')
            ->addOption('after', null , InputOption::VALUE_REQUIRED, 'Get the results for all calls placed after the provided date')
            ->addOption('before', null , InputOption::VALUE_REQUIRED, 'Get the results for all calls placed before the provided date')
            ->addOption('on', null , InputOption::VALUE_REQUIRED, 'Get the results for all calls placed on the provided date')
            ->addOption('to', null , InputOption::VALUE_REQUIRED, 'Get the results for all calls to the provided number')
            ->addOption('from', null , InputOption::VALUE_REQUIRED, 'Get the results for all calls from the provided number')
            ->addOption('status', null , InputOption::VALUE_REQUIRED, 'Get the results for all calls with the provided status');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('id')) {

            $callIds = explode(',', $input->getOption('id'));

            $results = $this->callService->getResults($callIds);

            $this->displayResults($output, $results);

        } else {

            if ($input->getOption('after')) {

                $this->callService->addFilter('after', $input->getOption('after'));

            }

            if ($input->getOption('before')) {

                $this->callService->addFilter('before', $input->getOption('before'));

            }

            if ($input->getOption('on')) {

                $this->callService->addFilter('on', $input->getOption('on'));

            }

            if ($input->getOption('to')) {

                $this->callService->addFilter('to', $input->getOption('to'));

            }

            if ($input->getOption('from')) {

                $this->callService->addFilter('from', $input->getOption('from'));

            }

            if ($input->getOption('status')) {

                $this->callService->addFilter('status', $input->getOption('status'));

            }

            $results = $this->callService->getFilteredResults();

            $this->displayResults($output, $results);
        }
    }
}
