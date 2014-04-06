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
            ->addOption('sid', null , InputOption::VALUE_REQUIRED, 'Get the status for calls with the specified SIDs')
            ->addOption('dateRange', null , InputOption::VALUE_REQUIRED, 'Get the status for calls within the specified date range');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('sid')) {

            $callIds = explode(',', $input->getOption('sid'));

            $results = $this->callService->getResults($callIds);

            $this->displayResults($output, $results);

        } elseif ($input->getOption('dateRange')) {

            $dates = explode(',', $input->getOption('dateRange'));

            foreach ($dates as $date) {

                $formattedDates[] = (new \DateTime($date))->format('Y-m-d H:i:s');

            }

            $results = $this->callService->getRange($formattedDates);

            $this->displayResults($output, $results);
        }
    }
}