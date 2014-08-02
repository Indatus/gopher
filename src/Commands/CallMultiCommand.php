<?php namespace Indatus\Gopher\Commands;

use Indatus\Gopher\Config;
use Indatus\Gopher\Commands\CallCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * This command can be used to run multiple batches of calls,
 * each with it's own call script. Configure your batches
 * in config.php prior to running this command.
 *
 * Usage:
 *
 * $ ./callbot call:multi
 */
class CallMultiCommand extends CallCommand
{
    /**
     * Array of completed call ids
     *
     * @var array
     */
    protected $callIds = array();

    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('call:multi')
            ->setDescription(
                "Run multiple batches of calls, each batch having it's own call script"
            )
            ->addArgument(
                'batches',
                InputArgument::OPTIONAL,
                'Comma-separated list of batch names to run'
            );
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
        $batches = $this->getBatchesToRun($input->getArgument('batches'));

        foreach ($batches as $batch) {

            if (!$this->uploadCallScript($batch['script'], $output)) continue;

            $callIds = $this->placeCalls(
                $batch['to'],
                array_key_exists('from', $batch) ?
                    $batch['from'] :
                    Config::get('callservice.from')
            );

            foreach ($callIds as $call) {
                $this->callIds[] = $call;
            }

        }

        $results = $this->callService->getDetails($this->callIds);

        if (!empty($results)) {

            $this->resultsHandler->displayTable(
                $this->getHelperSet()->get('table'),
                $results,
                $output
            );

        }

    }

    /**
     * Sets the batches to be ran based on what is
     * present in batches.php and what the user supplies
     * in the batches argument
     *
     * @param string $batches Comma separated list of batch names
     *
     * @return array
     */
    protected function getBatchesToRun($batches)
    {
        $allBatches = Config::get('batches');

        if ($batches) {

            $batchNames = explode(',', $batches);

            $batchesToRun = array();

            foreach ($batchNames as $name) {

                $batchesToRun[] = $allBatches[$name];

            }

            return $batchesToRun;

        } else {

            return $allBatches;

        }
    }
}
