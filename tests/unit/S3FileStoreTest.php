<?php

use Indatus\Callbot\S3FileStore;

class S3FileStoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->s3 = Mockery::mock('S3');
        $this->config = Mockery::mock('Indatus\Callbot\Config');

        $this->fileStore = new S3FileStore($this->s3, $this->config);

        parent::setUp();
    }

    public function tearDown()
    {
       Mockery::close();
    }

    public function testPut()
    {
        $this->config->shouldReceive('get')
            ->once()
            ->with('fileStore.uploadDir')
            ->andReturn('http://www.foo.com/bar');

        $this->s3->shouldReceive('putObject')
            ->once()
            ->with(
                '<Say>Hi</Say>',
                'bar',
                'foo.xml',
                S3::ACL_PUBLIC_READ,
                array(),
                array('Content-Type' => 'text/xml')
            )
            ->andReturn(true);

        $result = $this->fileStore->put('<Say>Hi</Say>', 'foo.xml');

        $this->assertTrue($result);
    }
}