<?php
/**
 *
 * @author zhanxianchao <qgmac@qq.com>
 * Date:   2024/7/18 下午7:53
 */

namespace Qgmac\CloudObjectStorage\cloud;

interface CloudInterface
{
    public function uploadFile(string $filePath, string $key): string;

    public function deleteFile(string $key): bool;

    public function getFileUrl(string $key): string;

    public function exist(string $key): bool;
}