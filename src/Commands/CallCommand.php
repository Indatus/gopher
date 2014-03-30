<?php

namespace Indatus\Callbot\Commands;

use Indatus\Callbot\ConfigReader;
use Indatus\Callbot\Factories\FileStoreFactory;
use Indatus\Callbot\Factories\CallServiceFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CallCommand extends Command
{
    public function __construct(
        CallServiceFactory $callServiceFactory,
        FileStoreFactory $fileStoreFactory,
        ConfigReader $config
    ) {
        $this->callServiceFactory = $callServiceFactory;
        $this->fileStoreFactory = $fileStoreFactory;
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

    }
}