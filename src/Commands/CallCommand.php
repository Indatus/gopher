<?php namespace Indatus\Callbot\Commands;

use Indatus\Callbot\Config;
use Indatus\Callbot\Factories\FileSystemFactory;
use Indatus\Callbot\Factories\CallServiceFactory;
use Indatus\Callbot\Factories\ResultsHandlerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The CallCommand class is the parent class that all of the other call
 * commands extend. Shared methods and properties are stored here.
 */
class CallCommand extends Command
{
    /**
     * CallServiceInterface implementation
     *
     * @var CallServiceInterface
     */
    protected $callService;

    /**
     * FileSystem instance
     *
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * ResultsHandlerInterface implementation
     *
     * @var ResultsHandlerInterface
     */
    protected $resultsHandler;

    /**
     * Constructor injects factories and creates dependancies
     *
     * @param CallServiceFactory $callServiceFactory
     * @param FileSystemFactory  $fileSystemFactory
     */
    public function __construct(
        CallServiceFactory $callServiceFactory,
        FileSystemFactory $fileSystemFactory,
        ResultsHandlerFactory $resultsHandlerFactory
    ) {
        $this->callService = $callServiceFactory->make(Config::get('callservice.default'));
        $this->fileSystem = $fileSystemFactory->make(Config::get('filesystem.default'));
        $this->resultsHandler = $resultsHandlerFactory->make(Config::get('callservice.default'));
        parent::__construct();
    }
    /**
     * Upload the call script to a remote file store
     *
     * @param string $path
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function uploadCallScript($path, OutputInterface $output)
    {
        if (!$script = $this->getScript($path)) {

            $output->writeln("<error>$path is not a valid file</error>");
            return false;

        }

        if (!$this->uploadScript($script, $this->getFileName($path))) {

            $output->writeln('<error>Failed to upload script.</error>');
            return false;

        }
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
     * Place the calls using the configured call service
     *
     * @param array  $numbers
     * @param string $from
     *
     * @return array
     */
    protected function placeCalls(array $numbers, $from)
    {
        $callIds = [];

        foreach ($numbers as $to) {

            $callIds[] = $this->callService->call(
                $from,
                $to,
                $this->uploadName
            );

        }

        return $callIds;
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

    /**
     * Get the contents of a call script file
     * only if the file exists
     *
     * @param string $path
     *
     * @return mixed
     */
    protected function getScript($path)
    {
        if (file_exists($path)) {
            return file_get_contents($path);
        }

        return false;
    }
}
