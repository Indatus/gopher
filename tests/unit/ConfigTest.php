<?php

use Indatus\Callbot\Config;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testGetConfigFile()
    {
        $batches = Config::get('batches');

        $this->assertInternalType('array', $batches);
        $this->assertArrayHasKey('example-1', $batches);
    }

    public function testGetOneLevelDeep()
    {
        $timezone = Config::get('callservice.timezone');

        $this->assertEquals('America/New_York', $timezone);
    }

    public function testGetTwoLevelsDeep()
    {
        $connection = Config::get('filesystem.connections.s3');

        $this->assertInternalType('array', $connection);
        $this->assertCount(4, $connection);
        $this->assertEquals($connection['driver'], 's3');
        $this->assertEquals($connection['key'], 'your_access_key');
        $this->assertEquals($connection['secret'], 'your_secret_key');
        $this->assertEquals($connection['bucket'], 'your_bucket_name');
    }

    public function testGetThreeLevelsDeep()
    {
        $driver = Config::get('callservice.connections.twilio.driver');

        $this->assertEquals('twilio', $driver);
    }

    public function testGetConnectionCallService()
    {
        $connection = Config::getConnection('callservice', 'twilio');

        $this->assertInternalType('array', $connection);
        $this->assertCount(3, $connection);
        $this->assertEquals($connection['driver'], 'twilio');
        $this->assertEquals($connection['sid'], 'your_account_sid');
        $this->assertEquals($connection['token'], 'your_auth_token');
    }

    public function testGetConnectionFileSystem()
    {
        $connection = Config::getConnection('filesystem', 's3');

        $this->assertInternalType('array', $connection);
        $this->assertCount(4, $connection);
        $this->assertEquals($connection['driver'], 's3');
        $this->assertEquals($connection['key'], 'your_access_key');
        $this->assertEquals($connection['secret'], 'your_secret_key');
        $this->assertEquals($connection['bucket'], 'your_bucket_name');
    }

    public function testGetRemoteDir()
    {
        $remote = Config::getRemoteDir();
        $this->assertEquals('https://s3.amazonaws.com/your_bucket_name/', $remote);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidConfigKeyThrowsException()
    {
        Config::get('filesystem.connections.s3.driver.foo');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidFileNameThrowsException()
    {
        Config::get('foo.bar');
    }
}