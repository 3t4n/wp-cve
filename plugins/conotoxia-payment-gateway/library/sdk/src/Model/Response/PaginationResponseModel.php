<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\EndpointInterface;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class PaginationResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class PaginationResponseModel implements ResponseModelInterface
{
    /**
     * @var bool|null
     */
    protected $first;

    /**
     * @var bool|null
     */
    protected $last;

    /**
     * @var int|null
     */
    protected $currentPageNumber;

    /**
     * @var int|null
     */
    protected $currentPageElementsCount;

    /**
     * @var int|null
     */
    protected $pageSize;

    /**
     * @var int|null
     */
    protected $totalPages;

    /**
     * @var int|null
     */
    protected $totalElements;

    /**
     * @var bool|null
     */
    protected $pageLimitExceeded;

    /**
     * @return bool|null
     */
    public function getFirst(): ?bool
    {
        return $this->first;
    }

    /**
     * @param bool $first
     *
     * @return PaginationResponseModel
     */
    public function setFirst(bool $first): PaginationResponseModel
    {
        $this->first = $first;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getLast(): ?bool
    {
        return $this->last;
    }

    /**
     * @param bool $last
     *
     * @return PaginationResponseModel
     */
    public function setLast(bool $last): PaginationResponseModel
    {
        $this->last = $last;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCurrentPageNumber(): ?int
    {
        return $this->currentPageNumber;
    }

    /**
     * @param int|null $currentPageNumber
     *
     * @return PaginationResponseModel
     */
    public function setCurrentPageNumber(int $currentPageNumber): PaginationResponseModel
    {
        $this->currentPageNumber = $currentPageNumber;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCurrentPageElementsCount(): ?int
    {
        return $this->currentPageElementsCount;
    }

    /**
     * @param int $currentPageElementsCount
     *
     * @return PaginationResponseModel
     */
    public function setCurrentPageElementsCount(int $currentPageElementsCount): PaginationResponseModel
    {
        $this->currentPageElementsCount = $currentPageElementsCount;

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
     * @return PaginationResponseModel
     */
    public function setPageSize(int $pageSize): PaginationResponseModel
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTotalPages(): ?int
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     *
     * @return PaginationResponseModel
     */
    public function setTotalPages(int $totalPages): PaginationResponseModel
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTotalElements(): ?int
    {
        return $this->totalElements;
    }

    /**
     * @param int $totalElements
     *
     * @return PaginationResponseModel
     */
    public function setTotalElements(int $totalElements): PaginationResponseModel
    {
        $this->totalElements = $totalElements;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPageLimitExceeded(): ?bool
    {
        return $this->pageLimitExceeded;
    }

    /**
     * @param bool $pageLimitExceeded
     *
     * @return PaginationResponseModel
     */
    public function setPageLimitExceeded(bool $pageLimitExceeded): PaginationResponseModel
    {
        $this->pageLimitExceeded = $pageLimitExceeded;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return EndpointInterface::class;
    }
}
