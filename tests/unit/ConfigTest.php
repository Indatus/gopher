<?php

use Indatus\Callbot\Config;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $timezone = Config::get('callservice.timezone');

        $this->assertEquals('America/New_York', $timezone);
    }

    public function testGetConnectionCallService()
    {
        $connection = Config::getConnection('callservice', 'twilio');

        $this->assertInternalType('array', $connection);
        $this->assertCount(3, $connection);
        $this->assertEquals($connection['driver'], 'twilio');
        $this->assertEquals($connection['sid'], 'your-account-sid');
        $this->assertEquals($connection['token'], 'your-auth-token');
    }

    public function testGetConnectionFileSystem()
    {
        $connection = Config::getConnection('filesystem', 's3');

        $this->assertInternalType('array', $connection);
        $this->assertCount(4, $connection);
        $this->assertEquals($connection['driver'], 's3');
        $this->assertEquals($connection['key'], 'your-access-key');
        $this->assertEquals($connection['secret'], 'your-secret-key');
        $this->assertEquals($connection['bucket'], 'your-bucket-name');
    }

    public function testGetRemoteDir()
    {
        $remote = Config::getRemoteDir();
        $this->assertEquals('https://s3.amazonaws.com/your-bucket-name/', $remote);
    }
}