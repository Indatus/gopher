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

class CallSingleCommand extends CallCommand
{
    protected function configure()
    {
        $this
            ->setName('call:single')
            ->setDescription('Make a single call')
            ->addArgument('number', InputArgument::OPTIONAL, 'Phone number to call')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to call script')
            ->addOption('batches', 'b', InputOption::VALUE_REQUIRED, 'Specify which batches to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('number')) {

            $script = file_get_contents($input->getArgument('path'));

            if (!$this->uploadScript($script)) {

                $output->writeln('<error>Failed to upload script.</error>');
                die;

            }

            $call = $this->callService->call(
                $this->config->get('callService.defaultFrom'),
                $input->getArgument('number'),
                $this->uploadName
            );

            $callIds[] = $call->sid;

            if (!empty($callIds)) {

                $this->displayResults($output, $callIds);

            }

        } else {

            $this->setBatchesToRun($input);

            foreach ($this->batchesToRun as $batch) {

                $script = $this->generateScript($batch);

                if (!$this->uploadScript($script)) {

                    $output->writeln('<error>Failed to upload script.</error>');
                    die;

                }

                foreach ($batch['to'] as $toNumber) {

                    if (array_key_exists('from', $batch)) {

                        $from = $batch['from'];

                    } else {

                        $from = $this->config->get('callService.defaultFrom');

                    }

                    $call = $this->callService->call(
                        $from,
                        $toNumber,
                        $this->uploadName
                    );

                    $this->outgoingCalls[] = $call->sid;

                }

            }

            if (!empty($this->outgoingCalls)) {

                $this->displayResults($output);

            }
        }
    }

}