<?php

namespace Indatus\Callbot\Commands;

use DateTime;
use Indatus\Callbot\Config;
use Indatus\Callbot\Factories\FileStoreFactory;
use Indatus\Callbot\Factories\CallServiceFactory;
use Indatus\Callbot\Factories\ScriptGeneratorFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CallCommand extends Command
{
    protected $callService;
    protected $fileStore;
    protected $config;
    protected $outgoingCalls = array();

    public function __construct(
        CallServiceFactory $callServiceFactory,
        FileStoreFactory $fileStoreFactory,
        ScriptGeneratorFactory $scriptGeneratorFactory,
        Config $config
    ) {
        $this->callService = $callServiceFactory->make($config->get('callService.driver'));
        $this->fileStore = $fileStoreFactory->make($config->get('fileStore.driver'));
        $this->generator = $scriptGeneratorFactory->make($config->get('callService.driver'));
        $this->config = $config;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('call')
            ->setDescription('Run a batch of calls')
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

            $this->outgoingCalls[] = $call->sid;

            if (!empty($this->outgoingCalls)) {

                $this->displayResults($output);

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

    protected function setBatchesToRun($input)
    {
        $allBatches = $this->config->get('batches');

        if ($batches = $input->getOption('batches')) {

            $numbers = explode(',', $batches);

            foreach ($numbers as $number) {
                $this->batchesToRun[] = $allBatches[$number - 1];
            }

        } else {

            $this->batchesToRun = $allBatches;

        }
    }

    protected function generateScript($batch)
    {
        if (array_key_exists('message', $batch)) {

            $this->generator->parseMessage($batch['message']);
            return $this->generator->getScript();

        } elseif (array_key_exists('srcFile', $batch)) {

            return file_get_contents($batch['srcFile']);

        }
    }

    protected function uploadScript($script)
    {
        if (!is_null($script) || $script !== false) {

            $this->uploadName = time() . '.xml';

            return $this->fileStore->put($script, $this->uploadName);

        }
    }

    protected function displayResults($output)
    {
        $table = $this->getHelperSet()->get('table');

        $table->setHeaders(['Call SID', 'From', 'To', 'Status']);

        $callResults = $this->callService->getResults($this->outgoingCalls);

        $rows = array();

        if (!empty($callResults)) {

            foreach ($callResults as $call) {

                $rows[] = [$call->sid, $call->from_formatted, $call->to_formatted, $call->status];

            }

        }

        if (!empty($rows)) {

            $table->setRows($rows);

        }

        $table->render($output);
    }
}