<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Endpoint\MakeRefundEndpoint;
use CKPL\Pay\Model\DisallowHTMLTagsTrimmer;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class RefundRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class RefundRequestModel implements RequestModelInterface
{
    /**
     * @var string|null
     */
    protected $paymentId;

    /**
     * @var string|null
     */
    protected $reason;

    /**
     * @var TotalAmountRequestModel|null
     */
    protected $amount;

    /**
     * @var string|null
     */
    protected $externalRefundId;

    /**
     * @var string|null
     */
    protected $integrationPlatform;

    /**
     * @var string|null
     */
    protected $notificationUrl;

    /**
     * @var mixed|string|null
     */
    protected $notificationUrlParameters;

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return MakeRefundEndpoint::class;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        $result = [
            'paymentId' => $this->getPaymentId(),
            'reason' => $this->getReason(),
            'integrationPlatform' => $this->getIntegrationPlatform()
        ];

        if (null !== $this->getAmount()) {
            $result['amount'] = $this->getAmount()->raw();
        }

        if (null !== $this->getExternalRefundId()) {
            $result['externalRefundId'] = $this->getExternalRefundId();
        }

        if (null !== $this->getNotificationUrl()) {
            $result['notificationUrl'] = $this->getNotificationUrl();
        }

        if (null !== $this->getNotificationUrlParameters()) {
            $result['notificationUrlParameters'] = $this->getNotificationUrlParameters();
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    /**
     * @param string $paymentId
     *
     * @return RefundRequestModel
     */
    public function setPaymentId(string $paymentId): RefundRequestModel
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return DisallowHTMLTagsTrimmer::trim($this->reason);
    }

    /**
     * @param string $reason
     *
     * @return RefundRequestModel
     */
    public function setReason(string $reason): RefundRequestModel
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIntegrationPlatform()
    {
        return $this->integrationPlatform;
    }

    /**
     * @param string|null $integrationPlatform
     */
    public function setIntegrationPlatform(?string $integrationPlatform)
    {
        $this->integrationPlatform = $integrationPlatform;
    }

    /**
     * @return TotalAmountRequestModel|null
     */
    public function getAmount(): ?TotalAmountRequestModel
    {
        return $this->amount;
    }

    /**
     * @param TotalAmountRequestModel $amount
     *
     * @return RefundRequestModel
     */
    public function setAmount(TotalAmountRequestModel $amount): RefundRequestModel
    {
        $this->amount = $amount;

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
     * @return RefundRequestModel
     */
    public function setExternalRefundId(string $externalRefundId): RefundRequestModel
    {
        $this->externalRefundId = $externalRefundId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotificationUrl(): ?string
    {
        return $this->notificationUrl;
    }

    /**
     * @param string $notificationUrl
     *
     * @return RefundRequestModel
     */
    public function setNotificationUrl(string $notificationUrl): RefundRequestModel
    {
        $this->notificationUrl = $notificationUrl;

        return $this;
    }

    /**
     * @param mixed $notificationUrlParameters
     *
     * @return RefundRequestModel
     */
    public function setNotificationUrlParameters($notificationUrlParameters): RefundRequestModel
    {
        $this->notificationUrlParameters = $notificationUrlParameters;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotificationUrlParameters()
    {
        return $this->notificationUrlParameters;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return RequestModelInterface::JSON_OBJECT;
    }
}
