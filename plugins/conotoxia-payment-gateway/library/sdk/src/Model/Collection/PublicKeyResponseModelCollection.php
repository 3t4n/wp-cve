<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Collection;

use ArrayIterator;
use CKPL\Pay\Model\CollectionInterface;
use CKPL\Pay\Model\ModelInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Response\PublicKeyResponseModel;

/**
 * Class PublicKeyResponseModelCollection.
 *
 * @package CKPL\Pay\Model\Collection
 */
class PublicKeyResponseModelCollection implements CollectionInterface, ProcessedOutputInterface
{
    /**
     * @var array
     */
    protected $keys = [];

    /**
     * @param ModelInterface $model
     *
     * @return void
     */
    public function add(ModelInterface $model): void
    {
        $this->keys[] = $model;
    }

    /**
     * @return array|PublicKeyResponseModel[]
     */
    public function all(): array
    {
        return $this->keys;
    }

    /**
     * @return ArrayIterator|PublicKeyResponseModel[]
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }
}
