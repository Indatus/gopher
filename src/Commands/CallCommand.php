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
     * @param CallServiceFactory    $callServiceFactory
     * @param FileSystemFactory     $fileSystemFactory
     * @param ResultsHandlerFactory $resultsHandlerFactory
     */
    public function __construct(
        CallServiceFactory $callServiceFactory,
        FileSystemFactory $fileSystemFactory,
        ResultsHandlerFactory $resultsHandlerFactory
    ) {
        $callService = Config::get('callservice.default');
        $this->callService = $callServiceFactory->make($callService);
        $this->fileSystem = $fileSystemFactory->make(
            Config::get('filesystem.default')
        );
        $this->resultsHandler = $resultsHandlerFactory->make($callService);
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

        $this->uploadName = $this->getFileName($path);

        return $this->fileSystem->put(
            $this->uploadName,
            $script,
            ['visibility' => 'public']
        );
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
