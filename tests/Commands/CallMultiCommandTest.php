<?php

use Symfony\Component\Console\Application;
use Indatus\Callbot\Commands\CallMultiCommand;
use Symfony\Component\Console\Tester\CommandTester;

class CallMultiCommandTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->callServiceFactory = Mockery::mock('Indatus\Callbot\Factories\CallServiceFactory');
        $this->fileSystemFactory = Mockery::mock('Indatus\Callbot\Factories\FileSystemFactory');
        $this->resultsHandlerFactory = Mockery::mock('Indatus\Callbot\Factories\ResultsHandlerFactory');
        $this->callService = Mockery::mock('Indatus\Callbot\Contracts\CallServiceInterface');
        $this->fileSystem = Mockery::mock('League\Flysystem\Filesystem');
        $this->resultsHandler = Mockery::mock('Indatus\Callbot\Contracts\ResultsHandlerInterface');
        parent::setUp();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testExecuteSuccess()
    {
        $this->callServiceFactory
            ->shouldReceive('make')
            ->andReturn($this->callService);

        $this->fileSystemFactory
            ->shouldReceive('make')
            ->andReturn($this->fileSystem);

        $this->resultsHandlerFactory
            ->shouldReceive('make')
            ->andReturn($this->resultsHandler);

        $this->fileSystem
            ->shouldReceive('put')
            ->andReturn(true);

        $this->callService
            ->shouldReceive('call')
            ->with('5551234567', '5551234567', 'test-script.xml')
            ->andReturn('foo')
            ->shouldReceive('getDetails')
            ->with(['foo', 'foo', 'foo'])
            ->andReturn(['bar']);

        $this->resultsHandler
            ->shouldReceive('displayTable')
            ->andReturn(true);

        $application = new Application();
        $application->add(new CallMultiCommand(
            $this->callServiceFactory,
            $this->fileSystemFactory,
            $this->resultsHandlerFactory
        ));

        $command = $application->find('call:multi');
        $commandTester = new CommandTester($command);

        $commandTester->execute(['command' => $command->getName()]);
    }

    public function testExecuteWithBatchesOption()
    {
        $this->callServiceFactory
            ->shouldReceive('make')
            ->andReturn($this->callService);

        $this->fileSystemFactory
            ->shouldReceive('make')
            ->andReturn($this->fileSystem);

        $this->resultsHandlerFactory
            ->shouldReceive('make')
            ->andReturn($this->resultsHandler);

        $this->fileSystem
            ->shouldReceive('put')
            ->andReturn(true);

        $this->callService
            ->shouldReceive('call')
            ->with('5551234567', '5551234567', 'test-script.xml')
            ->andReturn('foo')
            ->shouldReceive('getDetails')
            ->with(['foo', 'foo', 'foo'])
            ->andReturn(['bar']);

        $this->resultsHandler
            ->shouldReceive('displayTable')
            ->andReturn(true);

        $application = new Application();
        $application->add(new CallMultiCommand(
            $this->callServiceFactory,
            $this->fileSystemFactory,
            $this->resultsHandlerFactory
        ));

        $command = $application->find('call:multi');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'batches' => 'example-1'
        ]);
    }
}