<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Endpoint\GetRefundsEndpoint;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class GetRefundsRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class GetRefundsRequestModel implements RequestModelInterface
{
    /**
     * @var array|null
     */
    protected $paymentsIds;

    /**
     * @var array|null
     */
    protected $refundsIds;

    /**
     * @var string|null
     */
    protected $externalRefundId;

    /**
     * @var string|null
     */
    protected $creationDateFrom;

    /**
     * @var string|null
     */
    protected $creationDateTo;

    /**
     * @var string|null
     */
    protected $bookedDateFrom;

    /**
     * @var string|null
     */
    protected $bookedDateTo;

    /**
     * @var int|null
     */
    protected $pageNumber;

    /**
     * @var int|null
     */
    protected $pageSize;

    /**
     * @var string|null
     */
    protected $sort;

    /**
     * @return array|null
     */
    public function getPaymentsIds(): ?array
    {
        return $this->paymentsIds;
    }

    /**
     * @return array|null
     */
    public function getRefundsIds(): ?array
    {
        return $this->refundsIds;
    }

    /**
     * @param array $refundsIds
     *
     * @return GetRefundsRequestModel
     */
    public function setRefundsIds(array $refundsIds): GetRefundsRequestModel
    {
        $this->refundsIds = $refundsIds;

        return $this;
    }

    /**
     * @param array $paymentsIds
     *
     * @return GetRefundsRequestModel
     */
    public function setPaymentsIds(array $paymentsIds): GetRefundsRequestModel
    {
        $this->paymentsIds = $paymentsIds;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalRefundId(): ?string
    {
        return $this->externalRefundId;
    }

    /**
     * @param string $externalRefundId
     *
     * @return GetRefundsRequestModel
     */
    public function setExternalRefundId(string $externalRefundId): GetRefundsRequestModel
    {
        $this->externalRefundId = $externalRefundId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreationDateFrom(): ?string
    {
        return $this->creationDateFrom;
    }

    /**
     * @param string $creationDateFrom
     *
     * @return GetRefundsRequestModel
     */
    public function setCreationDateFrom(string $creationDateFrom): GetRefundsRequestModel
    {
        $this->creationDateFrom = $creationDateFrom;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreationDateTo(): ?string
    {
        return $this->creationDateTo;
    }

    /**
     * @param string $creationDateTo
     *
     * @return GetRefundsRequestModel
     */
    public function setCreationDateTo(string $creationDateTo): GetRefundsRequestModel
    {
        $this->creationDateTo = $creationDateTo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBookedDateFrom(): ?string
    {
        return $this->bookedDateFrom;
    }

    /**
     * @param string $bookedDateFrom
     *
     * @return GetRefundsRequestModel
     */
    public function setBookedDateFrom(string $bookedDateFrom): GetRefundsRequestModel
    {
        $this->bookedDateFrom = $bookedDateFrom;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBookedDateTo(): ?string
    {
        return $this->bookedDateTo;
    }

    /**
     * @param string $bookedDateTo
     *
     * @return GetRefundsRequestModel
     */
    public function setBookedDateTo(string $bookedDateTo): GetRefundsRequestModel
    {
        $this->bookedDateTo = $bookedDateTo;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    /**
     * @param int $pageNumber
     *
     * @return GetRefundsRequestModel
     */
    public function setPageNumber(int $pageNumber): GetRefundsRequestModel
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     *
     * @return GetRefundsRequestModel
     */
    public function setPageSize(int $pageSize): GetRefundsRequestModel
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSort(): ?string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     *
     * @return GetRefundsRequestModel
     */
    public function setSort(string $sort): GetRefundsRequestModel
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return GetRefundsEndpoint::class;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        $result = [];

        if (null !== $this->getPaymentsIds()) {
            $result['paymentIds'] = $this->getPaymentsIds();
        }

        if (null !== $this->getRefundsIds()) {
            $result['refundIds'] = $this->getRefundsIds();
        }

        if (null !== $this->getExternalRefundId()) {
            $result['externalRefundId'] = $this->getExternalRefundId();
        }

        if (null !== $this->getCreationDateFrom()) {
            $result['creationDateFrom'] = $this->getCreationDateFrom();
        }

        if (null !== $this->getCreationDateTo()) {
            $result['creationDateTo'] = $this->getCreationDateTo();
        }

        if (null !== $this->getBookedDateFrom()) {
            $result['bookedDateFrom'] = $this->getBookedDateFrom();
        }

        if (null !== $this->getBookedDateTo()) {
            $result['bookedDateTo'] = $this->getBookedDateTo();
        }

        if (null !== $this->getPageNumber()) {
            $result['pageNumber'] = $this->getPageNumber();
        }

        if (null !== $this->getPageSize()) {
            $result['pageSize'] = $this->getPageSize();
        }

        if (null !== $this->getSort()) {
            $result['sort'] = $this->getSort();
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return RequestModelInterface::FORM;
    }
}
