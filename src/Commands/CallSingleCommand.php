<?php namespace Indatus\Callbot\Commands;

use Indatus\Callbot\Config;
use Indatus\Callbot\Commands\CallCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * This command can be used to run a single batch of calls
 * that share the same call script.
 *
 * Usage:
 *
 * $ ./callbot call:single 5551234567,5551234567,5551234567 call-scripts/script.xml
 */
class CallSingleCommand extends CallCommand
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('call:single')
            ->setDescription('Run a single batch of calls that share the same call script')
            ->addArgument('numbers', InputArgument::REQUIRED, 'Comma-separated list of phone numbers to call')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to call script')
            ->addOption('from', null, InputOption::VALUE_REQUIRED, 'Override default from phone number');
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
        $this->uploadCallScript($input, $output);

        $callIds = $this->placeCalls($input);

        $results = $this->callService->getDetails($callIds);

        if (!empty($results)) {

            $this->resultsHandler->displayTable(
                $this->getHelperSet()->get('table'),
                $results
            );

        }
    }

    /**
     * Upload the call script to a remote file store
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     *
     * @return mixed
     */
    protected function uploadCallScript(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        if (!$script = $this->getScript($path)) {

            $output->writeln("<error>$path is not a valid file</error>");
            die;

        }

        $filename = $this->getFileName($path);

        if (!$this->uploadScript($script, $filename)) {

            $output->writeln('<error>Failed to upload script.</error>');
            die;

        }
    }

    /**
     * Place the calls using the configured call service
     *
     * @param  InputInterface $input
     *
     * @return array
     */
    protected function placeCalls(InputInterface $input)
    {
        $numbers = explode(',', $input->getArgument('numbers'));

        $from = $input->getOption('from') ?: Config::get('callservice.from');

        $callIds = [];

        foreach ($numbers as $to) {

            $callIds[] = $this->callService->call(
                $from,
                $to,
                $this->uploadName
            );

        }

        return $callIds;
    }
}
