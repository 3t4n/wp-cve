<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Collection;

use ArrayIterator;
use CKPL\Pay\Model\CollectionInterface;
use CKPL\Pay\Model\ModelInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Response\PaginationResponseModel;
use CKPL\Pay\Model\Response\PaymentResponseModel;

/**
 * Class PaymentResponseModelCollection.
 *
 * @package CKPL\Pay\Model\Collection
 */
class PaymentResponseModelCollection implements CollectionInterface, ProcessedOutputInterface
{
    /**
     * @var array|PaymentResponseModel[]
     */
    protected $payments;

    /**
     * @var PaginationResponseModel|null
     */
    protected $pagination;

    /**
     * @param ModelInterface $model
     *
     * @return void
     */
    public function add(ModelInterface $model): void
    {
        $this->payments[] = $model;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->payments;
    }

    /**
     * @return PaginationResponseModel|null
     */
    public function getPagination(): ?PaginationResponseModel
    {
        return $this->pagination;
    }

    /**
     * @param PaginationResponseModel $pagination
     *
     * @return void
     */
    public function setPagination(PaginationResponseModel $pagination): void
    {
        $this->pagination = $pagination;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->payments);
    }
}
