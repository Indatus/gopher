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
        if (array_key_exists('message', $this->config->get('callDetails'))) {
            $this->generator->parseMessage($this->config->get('callDetails.message'));
            var_dump($this->generator->getScript());
        }
    }
}