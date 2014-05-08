<?php namespace Indatus\Callbot\Commands;

use Indatus\Callbot\Commands\CallCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command can be used to fetch and display the
 * results of outgoing calls.
 *
 * Usage:
 *
 * $ ./callbot call:results --id="UNIQUE_ID_1, UNIQUE_ID_2, UNIQUE_ID_3"
 *
 * $. /callbot call:results --after="2014-04-01" --before="2014-04-05" status="completed"
 */
class CallDetailsCommand extends CallCommand{

    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('call:details')
            ->setDescription('Display details of outgoing calls')
            ->addOption('id', null , InputOption::VALUE_REQUIRED, 'Get the details for calls with the specified IDs')
            ->addOption('after', null , InputOption::VALUE_REQUIRED, 'Get the details for all calls placed after the provided date')
            ->addOption('before', null , InputOption::VALUE_REQUIRED, 'Get the details for all calls placed before the provided date')
            ->addOption('on', null , InputOption::VALUE_REQUIRED, 'Get the details for all calls placed on the provided date')
            ->addOption('to', null , InputOption::VALUE_REQUIRED, 'Get the details for all calls to the provided number')
            ->addOption('from', null , InputOption::VALUE_REQUIRED, 'Get the details for all calls from the provided number')
            ->addOption('status', null , InputOption::VALUE_REQUIRED, 'Get the details for all calls with the provided status');
    }

    /**
     * Execute the command
     *
     * @param InputInterface  $input  InputInterface instance
     * @param OutputInterface $output OutputInterface instance
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('id')) {

            $callIds = explode(',', $input->getOption('id'));

            $results = $this->callService->getDetails($callIds);

            if (!empty($results)) {

                $this->resultsHandler->displayTable(
                    $this->getHelperSet()->get('table'),
                    $results
                );

            }

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

            $results = $this->callService->getFilteredDetails();

            $table = $this->buildDetailsTable($results);

            $table->render($output);
        }
    }
}
