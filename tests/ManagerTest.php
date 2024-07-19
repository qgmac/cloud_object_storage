<?php
/**
 *
 * @author zhanxianchao <qgmac@qq.com>
 * Date:   2024/7/19 上午11:42
 */

use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    private array $cfg = [
        'default' => 'cos',
        'disks' => [
            'cos' => [
                'driver' => 'cos',
                'secret_id' => '',
                'secret_key' => '',
                'region' => '',
                'bucket' => '',
                'url' => '',
            ],
            'obs' => [
                'driver' => 'obs',
                'key' => '',
                'secret' => '',
                'bucket' => '',
                'url' => '',
                'endpoint' => '',
            ],
        ],
    ];

    public function testSetConfig()
    {
        $manager = new Qgmac\CloudObjectStorage\Manager;
        $manager->setConfig($this->cfg);
        $this->assertEquals($this->cfg, $manager->getConfig());
    }

    public function testStore()
    {
        $manager = new Qgmac\CloudObjectStorage\Manager;
        $manager->setConfig($this->cfg);
        $store = $manager->store('cos');
        $key = "vip_avatar/2.txt"; //此处的 key 为对象键，对象键是对象在存储桶中的唯一标识
        $srcPath = "d:/2.txt";//本地文件绝对路径
        $url = $store->uploadFile($srcPath, $key);
        $this->assertEquals(file_get_contents($srcPath), file_get_contents($url));
    }

    public function testDelete()
    {
        $manager = new Qgmac\CloudObjectStorage\Manager;
        $manager->setConfig($this->cfg);
        $store = $manager->store('obs');

        $key = "vip_avatar/2.txt"; //此处的 key 为对象键，对象键是对象在存储桶中的唯一标识
        $srcPath = "d:/2.txt";//本地文件绝对路径
        $store->uploadFile($srcPath, $key);;
        $this->assertTrue($store->exist($key));
        $store->deleteFile($key);
        $this->assertFalse($store->exist($key));
    }
}