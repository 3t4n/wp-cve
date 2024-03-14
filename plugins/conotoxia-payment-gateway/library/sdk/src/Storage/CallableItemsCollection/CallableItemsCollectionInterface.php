<?php

declare(strict_types=1);

namespace CKPL\Pay\Storage\CallableItemsCollection;

use ArrayAccess;

/**
 * Interface CallableItemsCollectionInterface.
 *
 * Callable items collection allows to create custom implementation
 * for storing items in easy and fast way using CallableStorage.
 *
 * @package CKPL\Pay\Storage\CallableItemsCollection
 */
interface CallableItemsCollectionInterface extends ArrayAccess
{
    /**
     * Executes callable that clears entire storage.
     *
     * @return void
     */
    public function clear(): void;
}
