<?php

declare(strict_types=1);

namespace CKPL\Pay\Storage\KeyCollector;

use CKPL\Pay\Exception\KeyCollectorException;
use CKPL\Pay\Exception\StorageException;
use CKPL\Pay\Storage\StorageInterface;
use function count;
use function sprintf;

/**
 * Class KeyCollector.
 *
 * @package CKPL\Pay\Storage\KeyCollector
 */
class KeyCollector implements KeyCollectorInterface
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var string
     */
    protected $storageKey;

    /**
     * @var array
     */
    protected $keys;

    /**
     * KeyCollector constructor.
     *
     * @param StorageInterface $storage
     * @param string           $storageKey
     *
     * @throws StorageException
     */
    public function __construct(StorageInterface $storage, string $storageKey)
    {
        $this->storage = $storage;
        $this->storageKey = $storageKey;

        $this->keys = $storage->hasItem($this->storageKey)
            ? ($storage->expectArrayOrNull($this->storageKey) ?? [])
            : [];
    }

    /**
     * @return bool
     */
    public function hasAny(): bool
    {
        return count($this->keys) > 0;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasKey(string $id): bool
    {
        return isset($this->keys[$id]);
    }

    /**
     * @param string $id
     *
     * @throws KeyCollectorException
     *
     * @return string
     */
    public function getKey(string $id): string
    {
        if (!$this->hasKey($id)) {
            throw new KeyCollectorException(
                sprintf('Key with ID [%s] does not exist.', $id)
            );
        }

        return $this->keys[$id];
    }

    /**
     * @param string $id
     * @param string $key
     *
     * @return void
     */
    public function addKey(string $id, string $key): void
    {
        $this->keys[$id] = $key;

        $this->storage->setItem($this->storageKey, $this->keys);
    }
}
