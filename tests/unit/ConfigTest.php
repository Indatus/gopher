<?php

use Indatus\Callbot\Config;

class ConfigTest extends PHPUnit_Framework_TestCase
{
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
        $this->assertEquals($connection['key'], 'your-access-key');
        $this->assertEquals($connection['secret'], 'your-secret-key');
        $this->assertEquals($connection['bucket'], 'your-bucket-name');
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