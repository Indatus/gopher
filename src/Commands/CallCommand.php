<?php

namespace Indatus\Callbot\Commands;

use DateTime;
use Indatus\Callbot\Config;
use Indatus\Callbot\Factories\FileStoreFactory;
use Indatus\Callbot\Factories\CallServiceFactory;
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

    public function __construct(
        CallServiceFactory $callServiceFactory,
        FileStoreFactory $fileStoreFactory,
        Config $config
    ) {
        $this->callService = $callServiceFactory->make($config->get('callService.driver'));
        $this->fileStore = $fileStoreFactory->make($config->get('fileStore.driver'));
        $this->config = $config;
        parent::__construct();
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

    protected function displayResults($output, $results)
    {
        $table = $this->getHelperSet()->get('table');

        $table->setHeaders(['Date\Time', 'Call SID', 'From', 'To', 'Status']);

        $rows = array();

        if (!empty($results)) {

            foreach ($results as $call) {

                $dateTime = $this->formatDate($call->start_time);

                $rows[] = [$dateTime, $call->sid, $call->from_formatted, $call->to_formatted, $call->status];

            }

        }

        if (!empty($rows)) {

            $table->setRows($rows);

        }

        $table->render($output);
    }

    protected function formatDate($date)
    {
        return (new \DateTime($date))->format('Y-m-d H:i:s');
    }
}
