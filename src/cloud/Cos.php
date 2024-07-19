<?php
/**
 *
 * @author zhanxianchao <qgmac@qq.com>
 * Date:   2024/7/18 下午8:01
 */

namespace Qgmac\CloudObjectStorage\cloud;

use \Qcloud\Cos\Client;

class Cos implements CloudInterface
{
    private Client $client;
    private array $cfg;
    private string $bucket;

    public function __construct($cfg)
    {
        $this->cfg = $cfg;
        $this->bucket = $this->cfg['bucket'];
        $this->client = new Client([
                'region' => $this->cfg['region'],
                'scheme' => 'https',
                'credentials' => [
                    'secretId' => $this->cfg['secret_id'],
                    'secretKey' => $this->cfg['secret_key']
                ]]
        );
    }

    public function uploadFile(string $filePath, string $key): string
    {
        $file = fopen($filePath, "rb");
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'Body' => $file
        ]);
        return $this->cfg['url'] . '/' . $key;
    }

    public function deleteFile(string $key): bool
    {
        $this->client->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);
        return true;
    }

    public function getFileUrl(string $key): string
    {
        return $this->cfg['url'] . '/' . $key;
    }

    public function exist(string $key): bool
    {
        return $this->client->doesObjectExist(
            $this->bucket,
            $key
        );
    }
}