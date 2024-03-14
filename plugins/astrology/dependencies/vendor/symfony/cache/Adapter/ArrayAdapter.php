<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Prokerala\Astrology\Vendor\Symfony\Component\Cache\Adapter;

use Psr\Cache\CacheItemInterface;
use Psr\Log\LoggerAwareInterface;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\CacheItem;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\ResettableInterface;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Traits\ArrayTrait;
use Prokerala\Astrology\Vendor\Symfony\Contracts\Cache\CacheInterface;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ArrayAdapter implements AdapterInterface, CacheInterface, LoggerAwareInterface, ResettableInterface
{
    use ArrayTrait;
    private $createCacheItem;
    private $defaultLifetime;
    /**
     * @param bool $storeSerialized Disabling serialization can lead to cache corruptions when storing mutable values but increases performance otherwise
     */
    public function __construct(int $defaultLifetime = 0, bool $storeSerialized = \true)
    {
        $this->defaultLifetime = $defaultLifetime;
        $this->storeSerialized = $storeSerialized;
        $this->createCacheItem = \Closure::bind(static function ($key, $value, $isHit) {
            $item = new CacheItem();
            $item->key = $key;
            $item->value = $value;
            $item->isHit = $isHit;
            return $item;
        }, null, CacheItem::class);
    }
    /**
     * {@inheritdoc}
     */
    public function get(string $key, callable $callback, float $beta = null, array &$metadata = null)
    {
        $item = $this->getItem($key);
        $metadata = $item->getMetadata();
        // ArrayAdapter works in memory, we don't care about stampede protection
        if (\INF === $beta || !$item->isHit()) {
            $save = \true;
            $item->set($callback($item, $save));
            if ($save) {
                $this->save($item);
            }
        }
        return $item->get();
    }
    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        if (!($isHit = $this->hasItem($key))) {
            $this->values[$key] = $value = null;
        } else {
            $value = $this->storeSerialized ? $this->unfreeze($key, $isHit) : $this->values[$key];
        }
        $f = $this->createCacheItem;
        return $f($key, $value, $isHit);
    }
    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = [])
    {
        foreach ($keys as $key) {
            if (!\is_string($key) || !isset($this->expiries[$key])) {
                CacheItem::validateKey($key);
            }
        }
        return $this->generateItems($keys, \microtime(\true), $this->createCacheItem);
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function deleteItems(array $keys)
    {
        foreach ($keys as $key) {
            $this->deleteItem($key);
        }
        return \true;
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function save(CacheItemInterface $item)
    {
        if (!$item instanceof CacheItem) {
            return \false;
        }
        $item = (array) $item;
        $key = $item["\x00*\x00key"];
        $value = $item["\x00*\x00value"];
        $expiry = $item["\x00*\x00expiry"];
        if (null !== $expiry) {
            if (!$expiry) {
                $expiry = \PHP_INT_MAX;
            } elseif ($expiry <= \microtime(\true)) {
                $this->deleteItem($key);
                return \true;
            }
        }
        if ($this->storeSerialized && null === ($value = $this->freeze($value, $key))) {
            return \false;
        }
        if (null === $expiry && 0 < $this->defaultLifetime) {
            $expiry = \microtime(\true) + $this->defaultLifetime;
        }
        $this->values[$key] = $value;
        $this->expiries[$key] = $expiry ?? \PHP_INT_MAX;
        return \true;
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        return $this->save($item);
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function commit()
    {
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    public function delete(string $key) : bool
    {
        return $this->deleteItem($key);
    }
}
