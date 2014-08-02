<?php

use Symfony\Component\Console\Application;
use Indatus\Gopher\Commands\CallSingleCommand;
use Symfony\Component\Console\Tester\CommandTester;

class CallSingleCommandTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->callServiceFactory = Mockery::mock('Indatus\Gopher\Factories\CallServiceFactory');
        $this->fileSystemFactory = Mockery::mock('Indatus\Gopher\Factories\FileSystemFactory');
        $this->resultsHandlerFactory = Mockery::mock('Indatus\Gopher\Factories\ResultsHandlerFactory');
        $this->callService = Mockery::mock('Indatus\Gopher\Contracts\CallServiceInterface');
        $this->fileSystem = Mockery::mock('League\Flysystem\Filesystem');
        $this->resultsHandler = Mockery::mock('Indatus\Gopher\Contracts\ResultsHandlerInterface');
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
            ->with(['foo'])
            ->andReturn(['bar']);

        $this->resultsHandler
            ->shouldReceive('displayTable')
            ->andReturn(true);

        $application = new Application();
        $application->add(new CallSingleCommand(
            $this->callServiceFactory,
            $this->fileSystemFactory,
            $this->resultsHandlerFactory
        ));

        $command = $application->find('call:single');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'numbers' => '5551234567',
            'path' => 'call-scripts/test-script.xml'
        ]);
    }

    public function testExecuteWithInvalidFilePath()
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

        $application = new Application();
        $application->add(new CallSingleCommand(
            $this->callServiceFactory,
            $this->fileSystemFactory,
            $this->resultsHandlerFactory
        ));

        $command = $application->find('call:single');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'numbers' => '5551234567',
            'path' => 'call-scripts/foo.xml'
        ]);

        $this->assertRegExp(
            '/call-scripts\/foo.xml is not a valid file/',
            $commandTester->getDisplay()
        );
    }
}