<?php

namespace Indatus\Callbot\Commands;

use Indatus\Callbot\Config;
use Indatus\Callbot\Factories\FileStoreFactory;
use Indatus\Callbot\Factories\CallServiceFactory;
use Indatus\Callbot\Factories\ScriptGeneratorFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CallCommand extends Command
{
    protected $callService;
    protected $fileStore;
    protected $config;
    protected $script;

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
            ->setDescription('Call someone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->config->get('batches') as $batch) {

            $script = $this->generateScript($batch);

            if (!$this->uploadScript($script, $batch)) {

                $output->writeln('<error>Failed to upload script.</error>');
                die;

            }

            foreach ($batch['to'] as $toNumber) {

                $this->callService->call(
                    $batch['from'],
                    $toNumber,
                    $batch['callbackUrl']
                );

            }

            $output->writeln('<info>Batch complete.</info>');
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