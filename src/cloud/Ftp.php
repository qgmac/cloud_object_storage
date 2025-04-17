<?php
/**
 *
 * @author zhanxianchao <qgmac@qq.com>
 * Date:   2024/7/18 下午7:58
 */

namespace Qgmac\CloudObjectStorage\cloud;

use Exception;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToCheckFileExistence;
use League\Flysystem\Filesystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;

class Ftp implements CloudInterface
{
    private array $config;

    private Filesystem $filesystem;

    public function __construct($config)
    {
        $this->config = $config;
        $ftpConnectionOptions = FtpConnectionOptions::fromArray($this->config);
        $adapter = new FtpAdapter($ftpConnectionOptions);
        $this->filesystem = new Filesystem($adapter);
    }

    /**
     * @throws Exception
     */
    public function uploadFile(string $filePath, string $key): string
    {
        try {
            $this->filesystem->createDirectory(dirname($key));
            $this->filesystem->write($key, file_get_contents($filePath));
        } catch (FilesystemException|UnableToWriteFile $e) {
            throw new Exception($e->getMessage(), 403);
        }
        return $this->getFileUrl($key);
    }

    public function deleteFile(string $key): bool
    {
        try {
            $this->filesystem->delete($key);
        } catch (FilesystemException|UnableToDeleteFile $exception) {
            return false;
        }
        return true;
    }

    public function getFileUrl(string $key): string
    {
        return $this->config['url'] . '/' . $key;
    }

    public function exist(string $key): bool
    {
        try {
            $this->filesystem->fileExists($key);
        } catch (FilesystemException|UnableToCheckFileExistence $exception) {
            return false;
        }
        return true;
    }

    public function getConfig($key)
    {
        return $this->config[$key];
    }
}