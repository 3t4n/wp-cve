<?php

declare(strict_types=1);

namespace CKPL\Pay\Storage;

use CKPL\Pay\Storage\CallableItemsCollection\CallableItemsCollectionInterface;

/**
 * Class CallableStorage.
 *
 * @package CKPL\Pay\Storage
 */
class CallableStorage extends AbstractStorage
{
    /**
     * CallableStorage constructor.
     *
     * @param CallableItemsCollectionInterface $callableItemsCollection
     */
    public function __construct(CallableItemsCollectionInterface $callableItemsCollection)
    {
        $this->items = $callableItemsCollection;
    }

    /**
     * Checks whether the item with specified key
     * exists in storage or does not exist.
     *
     * @param string $key item key
     *
     * @return bool `true` if item exist; `false` if item does not exist
     */
    public function hasItem(string $key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * Changes value of item with specified key.
     * Remember that item value must be save as correct type.
     * If storage set `string` but value is expected to be `integer`
     * then `TypeMismatchException` will be thrown at the time when
     * library get this value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function setItem(string $key, $value): void
    {
        $this->items[$key] = $value;
    }

    /**
     * Clears entire storage.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->items->clear();
    }
}
