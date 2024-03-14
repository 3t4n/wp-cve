<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Refund;

/**
 * Class Refund.
 *
 * @package CKPL\Pay\Definition\Refund
 */
class Refund implements RefundInterface
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
     * @var string|null
     */
    protected $externalRefundId;

    /**
     * @var string|null
     */
    protected $value;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var string|null
     */
    protected $notificationUrl;

    /**
     * @var string|null
     */
    protected $integrationPlatform;

    /**
     * @var string|null
     */
    protected $acceptLanguage;

    /**
     * @var mixed|string|null
     */
    protected $notificationUrlParameters;

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    /**
     * @param string|null $paymentId
     *
     * @return Refund
     */
    public function setPaymentId(?string $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param string|null $reason
     *
     * @return Refund
     */
    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return string|null
     */
    public function getExternalRefundId(): ?string
    {
        return $this->externalRefundId;
    }

    /**
     * @param string|null $externalRefundId
     *
     * @return Refund
     */
    public function setExternalRefundId(?string $externalRefundId): void
    {
        $this->externalRefundId = $externalRefundId;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     *
     * @return Refund
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     *
     * @return Refund
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string|null
     */
    public function getNotificationUrl(): ?string
    {
        return $this->notificationUrl;
    }

    /**
     * @param string|null $notificationUrl
     *
     * @return Refund
     */
    public function setNotificationUrl(?string $notificationUrl): void
    {
        $this->notificationUrl = $notificationUrl;
    }

    /**
     * @return string|null
     */
    public function getIntegrationPlatform(): ?string
    {
        return $this->integrationPlatform;
    }

    /**
     * @param string|null $integrationPlatform
     */
    public function setIntegrationPlatform(?string $integrationPlatform): void
    {
        $this->integrationPlatform = $integrationPlatform;
    }

    /**
     * @return string|null
     */
    public function getAcceptLanguage(): ?string
    {
        return $this->acceptLanguage;
    }

    /**
     * @param string|null $acceptLanguage
     */
    public function setAcceptLanguage(?string $acceptLanguage): void
    {
        $this->acceptLanguage = $acceptLanguage;
    }

    /**
     * @return mixed|string|null
     */
    public function getNotificationUrlParameters()
    {
        return $this->notificationUrlParameters;
    }

    /**
     * @param mixed $notificationUrlParameters
     */
    public function setNotificationUrlParameters($notificationUrlParameters): void
    {
        $this->notificationUrlParameters = $notificationUrlParameters;
    }
}
