<?php

use Symfony\Component\Console\Application;
use Indatus\Callbot\Commands\CallDetailsCommand;
use Symfony\Component\Console\Tester\CommandTester;

class CallDetailsCommandTest extends PHPUnit_Framework_TestCase
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

    public function testExecuteWithIdOption()
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

        $this->callService
            ->shouldReceive('getDetails')
            ->with(['foo'])
            ->andReturn(['bar']);

        $this->resultsHandler
            ->shouldReceive('displayTable')
            ->andReturn(true);

        $application = new Application();
        $application->add(new CallDetailsCommand(
            $this->callServiceFactory,
            $this->fileSystemFactory,
            $this->resultsHandlerFactory
        ));

        $command = $application->find('call:details');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            '--id' => 'foo'
        ]);
    }

    public function testExecuteWithMultipleFilters()
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

        $this->callService
            ->shouldReceive('addFilter')
            ->with('on', 'foo')
            ->shouldReceive('addFilter')
            ->with('to', 'foo')
            ->shouldReceive('addFilter')
            ->with('from', 'foo')
            ->shouldReceive('addFilter')
            ->with('status', 'foo')
            ->shouldReceive('addFilter')
            ->with('after', 'foo')
            ->shouldReceive('addFilter')
            ->with('before', 'foo')
            ->shouldReceive('getFilteredDetails')
            ->andReturn('bar');

        $this->resultsHandler
            ->shouldReceive('displayTable')
            ->andReturn(true);

        $application = new Application();
        $application->add(new CallDetailsCommand(
            $this->callServiceFactory,
            $this->fileSystemFactory,
            $this->resultsHandlerFactory
        ));

        $command = $application->find('call:details');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            '--on' => 'foo',
            '--to' => 'foo',
            '--from' => 'foo',
            '--status' => 'foo',
            '--after' => 'foo',
            '--before' => 'foo'
        ]);
    }
}