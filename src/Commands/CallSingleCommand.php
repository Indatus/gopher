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
            ->addArgument('numbers', InputArgument::REQUIRED, 'Comma separated list of phone numbers to call')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to call script')
            ->addOption('from', null, InputOption::VALUE_REQUIRED, 'Override default from phone number');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $script = file_get_contents($input->getArgument('path'));

        if (!$this->uploadScript($script)) {

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

            $results = $this->callService->getResults($callIds);

            $this->displayResults($output, $results);

        }
    }

    protected function getFrom($overrideFrom)
    {
        if ($overrideFrom) {
            return $overrideFrom;
        }

        return $this->config->get('callService.defaultFrom');
    }
}
