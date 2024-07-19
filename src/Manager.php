<?php

namespace Qgmac\CloudObjectStorage;

use InvalidArgumentException;
use Qgmac\CloudObjectStorage\cloud\CloudInterface;

/**
 *
 * @author zhanxianchao <qgmac@qq.com>
 * Date:   2024/7/18 下午6:16
 */
class Manager
{
    protected array $config = [];
    protected array $instance = [];

    public function __construct($config)
    {
        $this->setConfig($config);
    }

    public function store(string $name = null): CloudInterface
    {
        return $this->instance($name);
    }

    public function getConfig(string $name = '', $default = null)
    {
        if ('' === $name) {
            return $this->config;
        }

        return $this->config[$name] ?? $default;
    }


    public function setConfig($config)
    {
        $this->config = $config;
    }

    protected function instance(string $name = null)
    {
        if (empty($name)) {
            $name = $this->getConfig('default', 'cos');
        }
        if (!isset($this->instance[$name])) {
            $this->instance[$name] = $this->createStore($name);
        }

        return $this->instance[$name];
    }

    protected function createStore(string $name): CloudInterface
    {
        $config = $this->getStoreConfig($name);

        $type = !empty($config['driver']) ? $config['driver'] : 'cos';

        if (false !== strpos($type, '\\')) {
            $class = $type;
        } else {
            $class = 'Qgmac\\CloudObjectStorage\\cloud\\' . ucfirst($type);
        }

        /** @var CloudInterface $connection */
        return new $class($config);
    }

    protected function getStoreConfig(string $name): array
    {
        $disks = $this->getConfig('disks');
        if (!isset($disks[$name])) {
            throw new InvalidArgumentException('Undefined cos config:' . $name);
        }

        return $disks[$name];
    }
}