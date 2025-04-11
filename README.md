<h1 align="center"> cloud_object_storage </h1>

<p align="center"> 云对象存储集成客户端.</p>


## Installing

```shell
$ composer require qgmac/cloud_object_storage -vvv
```

## Usage
```php
$config = [
        'default' => 'cos',//默认
        'disks' => [
            'cos' => [// 腾讯云
                'driver' => 'cos',
                'secret_id' => '',
                'secret_key' => '',
                'region' => '',
                'bucket' => '',
                'url' => '',
            ],
            'obs' => [// 华为云
                'driver' => 'obs',
                'key' => '',
                'secret' => '',
                'bucket' => '',
                'url' => '',
                'endpoint' => '',
            ],
            'oss' => [// 阿里云
                'driver' => 'obs',
                'key' => '',
                'secret' => '',
                'role_arn' => '',//即需要扮演的角色ID，格式为acs:ram::$accountID:role/$roleName
                'bucket' => '',
                'url' => '',
                'endpoint' => '',
            ],
             'ftp' => [
                'driver' => 'ftp',
                'host' => '',
                'port' => 21,
                'username' => '',
                'password' => '',
                'url' => '',
                'timeout' => 5,
            ],
        ],
    ];


$store_key = "/test/test.jpg";
$manager = new Manager($config);
$default_store_url = $manager->store()->uploadFile($_file, $head_url);

$cos_url = $manager->store('cos')->uploadFile($_file, $head_url);
$obs_url = $manager->store('obs')->uploadFile($_file, $head_url);
$oss_url = $manager->store('oss')->uploadFile($_file, $head_url);
$oss_url = $manager->store('ftp')->uploadFile($_file, $head_url);
$manager->store('cos')->deleteFile($store_key) bool;
$manager->store('cos')->getFileUrl($store_key) string;
$manager->store('cos')->exist($store_key) bool;
```
ps [OSS配置参考](https://help.aliyun.com/zh/oss/developer-reference/oss-php-configure-access-credentials?spm=a2c4g.11186623.0.0.1adb5d0fKxW76R#9a510df0f0i8d):使用长期访问凭证
## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/qgmac/cloud_object_storage/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/qgmac/cloud_object_storage/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT