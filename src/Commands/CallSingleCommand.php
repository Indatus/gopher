<?php

namespace Indatus\Callbot\Commands;

use DateTime;
use Indatus\Callbot\Config;
use Indatus\Callbot\Factories\FileStoreFactory;
use Indatus\Callbot\Factories\CallServiceFactory;
use Indatus\Callbot\Factories\ScriptGeneratorFactory;
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
        $path = $input->getArgument('path');

        $script = file_get_contents($path);

        $filename = $this->getFileName($path);

        if (!$this->uploadScript($script, $filename)) {

            $output->writeln('<error>Failed to upload script.</error>');
            die;

        }

        $numbers = explode(',', $input->getArgument('numbers'));

        $from = $this->getFrom($input->getOption('from'));

        foreach ($numbers as $to) {

            $callIds[] = $this->callService->call(
                $from,
                $to,
                $this->uploadName
            );

        }

        if (!empty($callIds)) {

            $results = $this->callService->getDetails($callIds);

            if (!empty($results)) {

                $table = $this->buildDetailsTable($results);

                $table->render($output);

            }

        }
    }

    /**
     * Get the from phone number for the call
     *
     * @param string $overrideFrom From phone number passed in
     *
     * @return string
     */
    protected function getFrom($overrideFrom)
    {
        if ($overrideFrom) {
            return $overrideFrom;
        }

        return $this->config->get('callService.defaultFrom');
    }
}
