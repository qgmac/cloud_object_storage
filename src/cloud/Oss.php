<?php
/**
 *
 * @author zhanxianchao <qgmac@qq.com>
 * Date:   2024/7/18 下午7:58
 */

namespace Qgmac\CloudObjectStorage\cloud;



class Oss implements CloudInterface
{
    private array $cfg;
    private string $bucket;


    public function __construct($cfg)
    {
        $this->cfg = $cfg;
        $this->bucket = $this->cfg['bucket'];
    }
    public function uploadFile(string $filePath, string $key): string
    {
        // TODO: Implement uploadFile() method.
        return $this->getFileUrl($key);
    }

    public function deleteFile(string $key): bool
    {
        // TODO: Implement deleteFile() method.
        return true;
    }

    public function getFileUrl(string $key): string
    {
        // TODO: Implement getFileUrl() method.
        return $this->cfg['url'] . '/' . $key;
    }

    public function exist(string $key): bool
    {
        // TODO: Implement exist() method.
        return true;
    }
}