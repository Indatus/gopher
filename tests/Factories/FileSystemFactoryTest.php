<?php

use Indatus\Callbot\Factories\FileSystemFactory;

class FileSystemFactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->fileSystemFactory = new FileSystemFactory;
        parent::setUp();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidFileSystemThrowsException()
    {
        $this->fileSystemFactory->make('foo');
    }

    public function testMakeS3FileSystem()
    {
        $s3 = $this->fileSystemFactory->make('s3');

        $this->assertInstanceOf('League\Flysystem\Filesystem', $s3);
    }
}