<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use Psr\SimpleCache\CacheInterface;
use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Manager\CacheManager;

class CacheService implements CacheInterface
{
    private const DAY_TO_SECONDS = 86400;

    /** @var CacheService */
    public static $instance;

    /** @var CacheManager */
    private $cacheManager;

    public function __construct()
    {
        $this->cacheManager = new CacheManager();
    }

    /**
     * @return CacheService
     */
    public static function getInstance(): CacheService
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $key
     * @param null $default
     * @return false|mixed|null
     */
    public function get($key, $default = null)
    {
        $result =  wp_cache_get($key, MipWcConnector::MODULE_NAME, false, $found);

        if ($found === true) {
            return $result;
        }

        return $default;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @param int $expire
     * @return bool
     */
    public function set($key, $data, $expire = self::DAY_TO_SECONDS): bool
    {
        return wp_cache_set($key, $data, MipWcConnector::MODULE_NAME, $expire);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key): bool
    {
        wp_cache_get($key, MipWcConnector::MODULE_NAME, false, $found);

        return (bool)$found;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key): bool
    {
        return wp_cache_delete($key, MipWcConnector::MODULE_NAME);
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        return wp_cache_flush();
    }

    /**
     * @param iterable $keys
     * @param null $default
     * @return array
     */
    public function getMultiple($keys, $default = null): array
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $default;
            if ($this->has($key)) {
                $result[$key] = wp_cache_get($key, MipWcConnector::MODULE_NAME, false);
            }
        }

        return $result;
    }

    /**
     * @param array $values
     * @param null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool
    {
        $result = true;

        foreach ($values as $key => $value){
            $result = wp_cache_set($key, $value, MipWcConnector::MODULE_NAME, $ttl);
        }

        return $result;
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool
    {
        $result = true;

        foreach ($keys as $key){
            $result = $result && wp_cache_delete($key, MipWcConnector::MODULE_NAME);
        }

        return $result;
    }

    /**
     * Generates the cache key from $className, $methodName and parameters array.
     *
     * @param string $methodName , if not set, will detect callee method from caller trace
     * @param array $parameters
     * @param string $prefix
     *
     * @return string
     */
    public static function generateCacheKey(string $methodName = '', $parameters = [], string $prefix = ''): string
    {
        if (empty($methodName)) {
            $traceData = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $methodName = $traceData[1]['class'] . '::' . $traceData[1]['function'];
        }

        try {
            $realCacheKey = $methodName . serialize($parameters);
        } catch (\Exception $exception) {
            $exportedParameters = var_export($parameters, true);
            $realCacheKey = $methodName . serialize($exportedParameters);
        }

        return $prefix . '_' . sha1($realCacheKey);
    }

    public function save(string $itemId, string $itemData, string $namespace = 'main'): void
    {
        if (empty($itemData)) {
            return;
        }

        $this->cacheManager->set($itemId, $itemData, $namespace);
    }

    public function findOneById(string $itemId): ?string
    {
        return $this->cacheManager->findOneById($itemId);
    }

    public function deleteRegisters(int $limit = 1000): void
    {
        $this->cacheManager->prune($limit);
    }
}