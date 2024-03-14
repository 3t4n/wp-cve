<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\GetRefundsEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class RefundResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class RefundResponseModel implements ResponseModelInterface
{
    /**
     * @var string|null
     */
    protected $refundId;

    /**
     * @var string|null
     */
    protected $externalRefundId;

    /**
     * @var string|null
     */
    protected $status;

    /**
     * @return string|null
     */
    public function getRefundId(): ?string
    {
        return $this->refundId;
    }

    /**
     * @param string $refundId
     *
     * @return RefundResponseModel
     */
    public function setRefundId(string $refundId): RefundResponseModel
    {
        $this->refundId = $refundId;

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
     * @return RefundResponseModel
     */
    public function setExternalRefundId(?string $externalRefundId): RefundResponseModel
    {
        $this->externalRefundId = $externalRefundId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return RefundResponseModel
     */
    public function setStatus(string $status): RefundResponseModel
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return GetRefundsEndpoint::class;
    }
}
