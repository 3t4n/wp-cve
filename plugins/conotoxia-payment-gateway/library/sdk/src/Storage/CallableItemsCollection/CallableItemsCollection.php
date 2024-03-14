<?php

declare(strict_types=1);

namespace CKPL\Pay\Storage\CallableItemsCollection;

use function call_user_func;

/**
 * Class CallableItemsCollection.
 *
 * Callable items collection allows to create custom implementation
 * for storing items in easy and fast way using CallableStorage.
 *
 * @package CKPL\Pay\Storage\CallableItemsCollection
 */
class CallableItemsCollection implements CallableItemsCollectionInterface
{
    /**
     * @var callable
     */
    protected $hasItem;

    /**
     * @var callable
     */
    protected $getItem;

    /**
     * @var callable
     */
    protected $setItem;

    /**
     * @var callable
     */
    protected $clear;

    /**
     * CallableItemsCollection constructor.
     *
     * Every parameter in constructor is callable
     * which can be implemented in two ways:
     *
     * Example 1:
     *     function (string $key) {
     *         // some logic
     *     }
     *
     * Example 2:
     *     [$object, 'methodName']
     *
     * @param callable $hasItem Callable that verifies if item with given key exists. Item key is passed as the first
     *                          parameter to callable.
     * @param callable $getItem Callable that returns value of item with given key. Item key is passed as the first
     *                          parameter to callable.
     * @param callable $setItem Callable that set value of item with given key. First parameter passed to callable is
     *                          item key and second is value.
     * @param callable $clear callable that clears entire storage
     */
    public function __construct(callable $hasItem, callable $getItem, callable $setItem, callable $clear)
    {
        $this->hasItem = $hasItem;
        $this->getItem = $getItem;
        $this->setItem = $setItem;
        $this->clear = $clear;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return call_user_func($this->hasItem, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        return call_user_func($this->getItem, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        call_user_func($this->setItem, $offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
    }

    /**
     * Executes callable that clears entire storage.
     *
     * @return void
     */
    public function clear(): void
    {
        call_user_func($this->clear);
    }
}
