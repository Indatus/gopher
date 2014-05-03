<?php namespace Indatus\Callbot\Commands;

use Indatus\Callbot\Config;
use Indatus\Callbot\Factories\FileSystemFactory;
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
     * FileSystem instance
     *
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * Constructor injects factories and creates dependancies
     *
     * @param CallServiceFactory $callServiceFactory
     * @param FileSystemFactory  $fileSystemFactory
     */
    public function __construct(
        CallServiceFactory $callServiceFactory,
        FileSystemFactory $fileSystemFactory
    ) {
        $this->callService = $callServiceFactory->make(Config::get('callService.driver'));
        $this->fileSystem = $fileSystemFactory->make(Config::get('fileSystem.driver'));
        parent::__construct();
    }

    /**
     * Upload a script to a remote file store
     *
     * @param string $script Content to upload
     *
     * @return boolean
     */
    protected function uploadScript($script, $filename)
    {
        if (!is_null($script) || $script !== false) {

            $this->uploadName = $filename;

            return $this->fileSystem->put(
                $this->uploadName,
                $script,
                ['visibility' => 'public']
            );

        }
    }

    /**
     * Display the details of multiple calls
     *
     * @param array $calls Array of call details objects
     *
     * @return Symfony\Component\Console\Output\OutputInterface
     */
    protected function buildDetailsTable($calls)
    {
        $table = $this->getHelperSet()->get('table');

        $table->setHeaders(
            ['Start Time', 'End Time', 'From', 'To', 'Status','Call ID']
        );

        $rows = array();

        foreach ($calls as $call) {

            $startTime = $this->formatDate($call->start_time);

            $endTime = $this->formatDate($call->end_time);

            $rows[] = [
                $startTime,
                $endTime,
                $call->from_formatted,
                $call->to_formatted,
                $call->status,
                $call->sid];

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
        return (new \DateTime($date))
            ->setTimezone(new \DateTimeZone(Config::get('timezone')))
            ->format('Y-m-d H:i:s');
    }

    /**
     * Get a file name from a path
     *
     * @param string $path Path to a file
     *
     * @return string
     */
    protected function getFileName($path)
    {
        $parts = explode('/', $path);

        return end($parts);
    }
}
