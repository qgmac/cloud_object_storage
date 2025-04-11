<?php

namespace Qgmac\CloudObjectStorage\Tests;

use PHPUnit\Framework\TestCase;
use Qgmac\CloudObjectStorage\Manager;
use Qgmac\CloudObjectStorage\cloud\Ftp;
use Qgmac\CloudObjectStorage\cloud\CloudInterface;

class ManagerTest extends TestCase
{
    public function testDefaultStore()
    {
        $config = [
            'default' => 'cos',
            'disks' => [
                'cos' => [
                    'driver' => 'cos',
                    'secret_id' => 'test_secret_id',
                    'secret_key' => 'test_secret_key',
                    'region' => 'test_region',
                    'bucket' => 'test_bucket',
                    'url' => 'test_url',
                ],
                'ftp' => [
                    'driver' => 'ftp',
                    'host' => 'test_host',
                    'port' => 21,
                    'username' => 'test_username',
                    'password' => 'test_password',
                    'timeout' => 10,
                ],
            ],
        ];

        $manager = new Manager($config);
        $defaultStore = $manager->store();

        $this->assertInstanceOf(CloudInterface::class, $defaultStore);
        $this->assertEquals('cos', $manager->getConfig('default'));
    }

    public function testSpecificStore()
    {
        $config = [
            'default' => 'ftp',
            'disks' => [
                'cos' => [
                    'driver' => 'cos',
                    'secret_id' => 'test_secret_id',
                    'secret_key' => 'test_secret_key',
                    'region' => 'test_region',
                    'bucket' => 'test_bucket',
                    'url' => 'test_url',
                ],
                'ftp' => [
                    'driver' => 'ftp',
                    'host' => 'test_host',
                    'port' => 21,
                    'username' => 'test_username',
                    'password' => 'test_password',
                    'timeout' => 10,
                ],
            ],
        ];

        $manager = new Manager($config);
        $ftpStore = $manager->store('ftp');
        $this->assertInstanceOf(Ftp::class, $ftpStore);
        $this->assertEquals('ftp', $ftpStore->getConfig('driver'));
    }

    public function testUndefinedStore()
    {
        $config = [
            'default' => 'cos',
            'disks' => [
                'cos' => [
                    'driver' => 'cos',
                    'secret_id' => 'test_secret_id',
                    'secret_key' => 'test_secret_key',
                    'region' => 'test_region',
                    'bucket' => 'test_bucket',
                    'url' => 'test_url',
                ],
            ],
        ];

        $manager = new Manager($config);

        $this->expectException(\InvalidArgumentException::class);
        $manager->store('undefined');
    }

    public function testFtp()
    {
        $config = [
            'default' => 'ftp',
            'disks' => [
                'ftp' => [
                    'driver' => 'ftp',
                    'root' => '/',
                    'host' => '127.0.0.1',
                    'port' => 21,
                    'username' => 'admin',
                    'password' => '123456',
                    'timeout' => 1,
                    'url' => 'https://www.baidu.com/',
                ],
            ],
        ];

        $manager = new Manager($config);
        $defaultStore = $manager->store();
        $this->assertInstanceOf(CloudInterface::class, $defaultStore);
        $this->assertEquals('ftp', $manager->getConfig('default'));
    }
}