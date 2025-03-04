<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Prokerala\Astrology\Vendor\Symfony\Component\Cache\Traits;

use Psr\Log\LoggerAwareTrait;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\CacheItem;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
trait AbstractTrait
{
    use LoggerAwareTrait;
    private $namespace;
    private $namespaceVersion = '';
    private $versioningIsEnabled = \false;
    private $deferred = [];
    private $ids = [];
    /**
     * @var int|null The maximum length to enforce for identifiers or null when no limit applies
     */
    protected $maxIdLength;
    /**
     * Fetches several cache items.
     *
     * @param array $ids The cache identifiers to fetch
     *
     * @return array|\Traversable The corresponding values found in the cache
     */
    protected abstract function doFetch(array $ids);
    /**
     * Confirms if the cache contains specified cache item.
     *
     * @param string $id The identifier for which to check existence
     *
     * @return bool True if item exists in the cache, false otherwise
     */
    protected abstract function doHave($id);
    /**
     * Deletes all items in the pool.
     *
     * @param string $namespace The prefix used for all identifiers managed by this pool
     *
     * @return bool True if the pool was successfully cleared, false otherwise
     */
    protected abstract function doClear($namespace);
    /**
     * Removes multiple items from the pool.
     *
     * @param array $ids An array of identifiers that should be removed from the pool
     *
     * @return bool True if the items were successfully removed, false otherwise
     */
    protected abstract function doDelete(array $ids);
    /**
     * Persists several cache items immediately.
     *
     * @param array $values   The values to cache, indexed by their cache identifier
     * @param int   $lifetime The lifetime of the cached values, 0 for persisting until manual cleaning
     *
     * @return array|bool The identifiers that failed to be cached or a boolean stating if caching succeeded or not
     */
    protected abstract function doSave(array $values, int $lifetime);
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function hasItem($key)
    {
        $id = $this->getId($key);
        if (isset($this->deferred[$key])) {
            $this->commit();
        }
        try {
            return $this->doHave($id);
        } catch (\Exception $e) {
            CacheItem::log($this->logger, 'Failed to check if key "{key}" is cached: ' . $e->getMessage(), ['key' => $key, 'exception' => $e]);
            return \false;
        }
    }
    /**
     * {@inheritdoc}
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function clear()
    {
        $this->deferred = [];
        if ($cleared = $this->versioningIsEnabled) {
            if ('' === ($namespaceVersionToClear = $this->namespaceVersion)) {
                foreach ($this->doFetch([static::NS_SEPARATOR . $this->namespace]) as $v) {
                    $namespaceVersionToClear = $v;
                }
            }
            $namespaceToClear = $this->namespace . $namespaceVersionToClear;
            $namespaceVersion = self::formatNamespaceVersion(\mt_rand());
            try {
                $e = $this->doSave([static::NS_SEPARATOR . $this->namespace => $namespaceVersion], 0);
            } catch (\Exception $e) {
            }
            if (\true !== $e && [] !== $e) {
                $cleared = \false;
                $message = 'Failed to save the new namespace' . ($e instanceof \Exception ? ': ' . $e->getMessage() : '.');
                CacheItem::log($this->logger, $message, ['exception' => $e instanceof \Exception ? $e : null]);
            } else {
                $this->namespaceVersion = $namespaceVersion;
                $this->ids = [];
            }
        } else {
            $prefix = 0 < \func_num_args() ? (string) \func_get_arg(0) : '';
            $namespaceToClear = $this->namespace . $prefix;
        }
        try {
            return $this->doClear($namespaceToClear) || $cleared;
        } catch (\Exception $e) {
            CacheItem::log($this->logger, 'Failed to clear the cache: ' . $e->getMessage(), ['exception' => $e]);
            return \false;
        }
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function deleteItem($key)
    {
        return $this->deleteItems([$key]);
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function deleteItems(array $keys)
    {
        $ids = [];
        foreach ($keys as $key) {
            $ids[$key] = $this->getId($key);
            unset($this->deferred[$key]);
        }
        try {
            if ($this->doDelete($ids)) {
                return \true;
            }
        } catch (\Exception $e) {
        }
        $ok = \true;
        // When bulk-delete failed, retry each item individually
        foreach ($ids as $key => $id) {
            try {
                $e = null;
                if ($this->doDelete([$id])) {
                    continue;
                }
            } catch (\Exception $e) {
            }
            $message = 'Failed to delete key "{key}"' . ($e instanceof \Exception ? ': ' . $e->getMessage() : '.');
            CacheItem::log($this->logger, $message, ['key' => $key, 'exception' => $e]);
            $ok = \false;
        }
        return $ok;
    }
    /**
     * Enables/disables versioning of items.
     *
     * When versioning is enabled, clearing the cache is atomic and doesn't require listing existing keys to proceed,
     * but old keys may need garbage collection and extra round-trips to the back-end are required.
     *
     * Calling this method also clears the memoized namespace version and thus forces a resynchonization of it.
     *
     * @param bool $enable
     *
     * @return bool the previous state of versioning
     */
    public function enableVersioning($enable = \true)
    {
        $wasEnabled = $this->versioningIsEnabled;
        $this->versioningIsEnabled = (bool) $enable;
        $this->namespaceVersion = '';
        $this->ids = [];
        return $wasEnabled;
    }
    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        if ($this->deferred) {
            $this->commit();
        }
        $this->namespaceVersion = '';
        $this->ids = [];
    }
    /**
     * Like the native unserialize() function but throws an exception if anything goes wrong.
     *
     * @param string $value
     *
     * @return mixed
     *
     * @throws \Exception
     *
     * @deprecated since Symfony 4.2, use DefaultMarshaller instead.
     */
    protected static function unserialize($value)
    {
        @\trigger_error(\sprintf('The "%s::unserialize()" method is deprecated since Symfony 4.2, use DefaultMarshaller instead.', __CLASS__), \E_USER_DEPRECATED);
        if ('b:0;' === $value) {
            return \false;
        }
        $unserializeCallbackHandler = \ini_set('unserialize_callback_func', __CLASS__ . '::handleUnserializeCallback');
        try {
            if (\false !== ($value = \unserialize($value))) {
                return $value;
            }
            throw new \DomainException('Failed to unserialize cached value.');
        } catch (\Error $e) {
            throw new \ErrorException($e->getMessage(), $e->getCode(), \E_ERROR, $e->getFile(), $e->getLine());
        } finally {
            \ini_set('unserialize_callback_func', $unserializeCallbackHandler);
        }
    }
    private function getId($key) : string
    {
        if ($this->versioningIsEnabled && '' === $this->namespaceVersion) {
            $this->ids = [];
            $this->namespaceVersion = '1' . static::NS_SEPARATOR;
            try {
                foreach ($this->doFetch([static::NS_SEPARATOR . $this->namespace]) as $v) {
                    $this->namespaceVersion = $v;
                }
                $e = \true;
                if ('1' . static::NS_SEPARATOR === $this->namespaceVersion) {
                    $this->namespaceVersion = self::formatNamespaceVersion(\time());
                    $e = $this->doSave([static::NS_SEPARATOR . $this->namespace => $this->namespaceVersion], 0);
                }
            } catch (\Exception $e) {
            }
            if (\true !== $e && [] !== $e) {
                $message = 'Failed to save the new namespace' . ($e instanceof \Exception ? ': ' . $e->getMessage() : '.');
                CacheItem::log($this->logger, $message, ['exception' => $e instanceof \Exception ? $e : null]);
            }
        }
        if (\is_string($key) && isset($this->ids[$key])) {
            return $this->namespace . $this->namespaceVersion . $this->ids[$key];
        }
        CacheItem::validateKey($key);
        $this->ids[$key] = $key;
        if (\count($this->ids) > 1000) {
            $this->ids = \array_slice($this->ids, 500, null, \true);
            // stop memory leak if there are many keys
        }
        if (null === $this->maxIdLength) {
            return $this->namespace . $this->namespaceVersion . $key;
        }
        if (\strlen($id = $this->namespace . $this->namespaceVersion . $key) > $this->maxIdLength) {
            // Use MD5 to favor speed over security, which is not an issue here
            $this->ids[$key] = $id = \substr_replace(\base64_encode(\hash('md5', $key, \true)), static::NS_SEPARATOR, -(\strlen($this->namespaceVersion) + 2));
            $id = $this->namespace . $this->namespaceVersion . $id;
        }
        return $id;
    }
    /**
     * @internal
     */
    public static function handleUnserializeCallback($class)
    {
        throw new \DomainException('Class not found: ' . $class);
    }
    private static function formatNamespaceVersion(int $value) : string
    {
        return \strtr(\substr_replace(\base64_encode(\pack('V', $value)), static::NS_SEPARATOR, 5), '/', '_');
    }
}
