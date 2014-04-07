<?php

namespace Indatus\Callbot\Commands;

use DateTime;
use Indatus\Callbot\Config;
use Indatus\Callbot\Factories\FileStoreFactory;
use Indatus\Callbot\Factories\CallServiceFactory;
use Symfony\Component\Console\Command\Command;

/**
 * The CallCommand class is the parent class that all of the other call
 * commands extend. Shared methods and properties are stored here.
 */
class CallCommand extends Command
{
    /**
     * CallServiceInterface implementation
     *
     * @var CallService
     */
    protected $callService;

    /**
     * FileStoreInterface implementation
     *
     * @var FileStore
     */
    protected $fileStore;

    /**
     * Config instance
     *
     * @var Indatus\Callbot\Config
     */
    protected $config;

    /**
     * Constructor injects factories and creates dependancies
     *
     * @param CallServiceFactory $callServiceFactory CallServiceFactory instance
     * @param FileStoreFactory   $fileStoreFactory   FileStoreFactory instance
     * @param Config             $config             Config instance
     */
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

    /**
     * Upload a script to a remote file store
     *
     * @param string $script Content to upload
     *
     * @return boolean
     */
    protected function uploadScript($script)
    {
        if (!is_null($script) || $script !== false) {

            $this->uploadName = time() . '.xml';

            return $this->fileStore->put($script, $this->uploadName);

        }
    }

    /**
     * Display the results of multiple calls
     *
     * @param array $results Array of call result objects
     *
     * @return Symfony\Component\Console\Output\OutputInterface
     */
    protected function buildResultsTable($results)
    {
        $table = $this->getHelperSet()->get('table');

        $table->setHeaders(['Date\Time', 'Call ID', 'From', 'To', 'Status']);

        $rows = array();

        foreach ($results as $call) {

            $dateTime = $this->formatDate($call->start_time);

            $rows[] = [$dateTime, $call->sid, $call->from_formatted, $call->to_formatted, $call->status];

        }

        if (!empty($rows)) {

            $table->setRows($rows);

        }

        return $table;
    }

    /**
     * Format the date for display
     *
     * @param string $date Date string to format
     *
     * @return DateTime
     */
    protected function formatDate($date)
    {
        return (new \DateTime($date))->format('Y-m-d H:i:s');
    }
}
