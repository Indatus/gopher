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
            ->addOption('batches', 'b', InputOption::VALUE_REQUIRED, 'Specify which batches to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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

        foreach ($this->batchesToRun as $batch) {

            $script = $this->generateScript($batch);

            if (!$this->uploadScript($script, $batch)) {

                $output->writeln('<error>Failed to upload script.</error>');
                die;

            }

            foreach ($batch['to'] as $toNumber) {

                $call = $this->callService->call(
                    $batch['from'],
                    $toNumber,
                    $batch['callbackUrl']
                );

                $this->outgoingCalls[] = $call->sid;
            }
        }

        if (!empty($this->outgoingCalls)) {

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

    protected function generateScript($batch)
    {
        if (array_key_exists('message', $batch)) {

            $this->generator->parseMessage($batch['message']);
            return $this->generator->getScript();

        } elseif (array_key_exists('srcFile', $batch)) {

            return file_get_contents($batch['srcFile']);

        }
    }

    protected function uploadScript($script, $batch)
    {
        if (!is_null($script) || $script !== false) {

            $parts = explode('/', $batch['callbackUrl']);

            $fileName = end($parts);

            return $this->fileStore->put($script, $fileName);

        }
    }
}