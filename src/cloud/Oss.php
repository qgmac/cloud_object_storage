<?php
/**
 *
 * @author zhanxianchao <qgmac@qq.com>
 * Date:   2024/7/18 下午7:58
 */

namespace Qgmac\CloudObjectStorage\cloud;


use AlibabaCloudCredentialsWrapper;
use Exception;
use OSS\Core\OssException;
use OSS\Credentials\StaticCredentialsProvider;
use AlibabaCloud\Credentials\Credential;
use OSS\Credentials\CredentialsProvider;
use OSS\Http\RequestCore_Exception;
use OSS\OssClient;
use ReflectionException;

class Oss implements CloudInterface
{
    private array $cfg;
    private string $bucket;
    private string $endpoint;

    private $ossClient;

    /**
     * @throws ReflectionException
     */
    public function __construct($cfg)
    {
        $this->cfg = $cfg;
        $this->bucket = $this->cfg['bucket'];
        $this->endpoint = $this->cfg['endpoint'];

        $ramRoleArn = new Credential(array(
            // 填写Credential类型，固定值为ram_role_arn。
            'type' => 'ram_role_arn',
            // 填写RAM用户的访问密钥（AccessKey ID和AccessKey Secret）。
            'access_key_id' => $this->cfg['key'],
            'access_key_secret' => $this->cfg['secret'],
            // 填写RAM角色的RamRoleArn。即需要扮演的角色ID，格式为acs:ram::$accountID:role/$roleName。
            'role_arn' => 'OSS_STS_ROLE_ARN',
            // 自定义角色会话名称，用于区分不同的令牌。
            'role_session_name' => 'oss_role_session_' . mt_rand(1000, 9999) . time(),
            // 自定义权限策略。
            'policy' => '',
        ));

        $provider = (new AlibabaCloudCredentialsWrapper($ramRoleArn))->getCredentials();

        $config = array(
            'provider' => $provider,
            'endpoint' => $this->endpoint
        );

        $this->ossClient = new OssClient($config);
    }

    /**
     * @throws Exception
     */
    public function uploadFile(string $filePath, string $key): string
    {
        try {
            $this->ossClient->uploadFile($this->bucket, $key, $filePath);
        } catch (OssException|RequestCore_Exception $e) {
             throw new Exception(403, $e->getMessage());
        }
        return $this->getFileUrl($key);
    }

    public function deleteFile(string $key): bool
    {
        try {
            $this->ossClient->deleteObject($this->bucket, $key);
        } catch (OssException|RequestCore_Exception $e) {
            return false;
        }
        return true;
    }

    public function getFileUrl(string $key): string
    {
        return $this->cfg['url'] . '/' . $key;
    }

    public function exist(string $key): bool
    {
        try {
            $this->ossClient->getObject($this->bucket, $key);
        } catch (OssException|RequestCore_Exception $e) {
            return false;
        }
        return true;
    }
}