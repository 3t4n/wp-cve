<?php

declare(strict_types=1);

namespace CKPL\Pay\Model;

use IteratorAggregate;

/**
 * Interface CollectionInterface.
 *
 * @package CKPL\Pay\Model
 */
interface CollectionInterface extends IteratorAggregate
{
    /**
     * @param ModelInterface $model
     *
     * @return void
     */
    public function add(ModelInterface $model): void;

    /**
     * @return array
     */
    public function all(): array;
}
