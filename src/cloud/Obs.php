<?php
/**
 *
 * @author zhanxianchao <qgmac@qq.com>
 * Date:   2024/7/18 下午8:01
 */

namespace Qgmac\CloudObjectStorage\cloud;


use Obs\ObsClient;

class Obs implements CloudInterface
{

    private array $cfg;
    private string $bucket;
    private ObsClient $client;

    public function __construct($cfg)
    {
        $this->cfg = $cfg;
        $this->bucket = $this->cfg['bucket'];
        $this->client = new ObsClient([
            'key' => $cfg['key'],
            'secret' => $cfg['secret'],
            'endpoint' => $cfg['endpoint']
        ]);
    }

    public function uploadFile(string $filePath, string $key): string
    {
        $fInfo = finfo_open(FILEINFO_MIME_TYPE); // 返回 MIME 类型
        $mimetype = finfo_file($fInfo, $filePath);
        finfo_close($fInfo);
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'SourceFile' => $filePath,
            'ContentType' => $mimetype
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
        $resp = $this->client->listObjects([
            'Bucket' => $this->bucket,
            'MaxKeys' => 1,
            'Prefix' => $key
        ]);
        return count($resp['Contents']) > 0;
    }
}