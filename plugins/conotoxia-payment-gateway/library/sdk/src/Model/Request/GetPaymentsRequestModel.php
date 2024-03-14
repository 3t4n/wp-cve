<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Endpoint\GetPaymentsEndpoint;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class GetPaymentsRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class GetPaymentsRequestModel implements RequestModelInterface
{
    /**
     * @var array|null
     */
    protected $paymentsIds;

    /**
     * @var string|null
     */
    protected $externalPaymentId;

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
     * @param array $paymentsIds
     *
     * @return GetPaymentsRequestModel
     */
    public function setPaymentsIds(array $paymentsIds): GetPaymentsRequestModel
    {
        $this->paymentsIds = $paymentsIds;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalPaymentId(): ?string
    {
        return $this->externalPaymentId;
    }

    /**
     * @param string $externalPaymentId
     *
     * @return GetPaymentsRequestModel
     */
    public function setExternalPaymentId(string $externalPaymentId): GetPaymentsRequestModel
    {
        $this->externalPaymentId = $externalPaymentId;

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
     * @return GetPaymentsRequestModel
     */
    public function setCreationDateFrom(string $creationDateFrom): GetPaymentsRequestModel
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
     * @return GetPaymentsRequestModel
     */
    public function setCreationDateTo(string $creationDateTo): GetPaymentsRequestModel
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
     * @return GetPaymentsRequestModel
     */
    public function setBookedDateFrom(string $bookedDateFrom): GetPaymentsRequestModel
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
     * @return GetPaymentsRequestModel
     */
    public function setBookedDateTo(string $bookedDateTo): GetPaymentsRequestModel
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
     * @return GetPaymentsRequestModel
     */
    public function setPageNumber(int $pageNumber): GetPaymentsRequestModel
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
     * @return GetPaymentsRequestModel
     */
    public function setPageSize(int $pageSize): GetPaymentsRequestModel
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
     * @return GetPaymentsRequestModel
     */
    public function setSort(string $sort): GetPaymentsRequestModel
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return GetPaymentsEndpoint::class;
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

        if (null !== $this->getExternalPaymentId()) {
            $result['externalPaymentId'] = $this->getExternalPaymentId();
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
