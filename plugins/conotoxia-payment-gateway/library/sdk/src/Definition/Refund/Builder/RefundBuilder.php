<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Refund\Builder;

use CKPL\Pay\Definition\Refund\Refund;
use CKPL\Pay\Definition\Refund\RefundInterface;
use CKPL\Pay\Exception\Definition\RefundException;

/**
 * Class RefundBuilder.
 *
 * @package CKPL\Pay\Definition\Refund\Builder
 */
class RefundBuilder implements RefundBuilderInterface
{
    /**
     * @var Refund
     */
    protected $refund;

    /**
     * RefundBuilder constructor.
     */
    public function __construct()
    {
        $this->initializeRefund();
    }

    /**
     * @return void
     */
    protected function initializeRefund(): void
    {
        $this->refund = new Refund();
    }

    /**
     * Payment Service payment ID.
     *
     * Min 1 character, max 40 characters.
     *
     * This value is required!
     *
     * @param string $paymentId
     *
     * @return RefundBuilderInterface
     */
    public function setPaymentId(string $paymentId): RefundBuilderInterface
    {
        $this->refund->setPaymentId($paymentId);

        return $this;
    }

    /**
     * Refund reason.
     *
     * Min 5 character, max 512 characters.
     *
     * This value is required!
     *
     * @param string $reason
     *
     * @return RefundBuilderInterface
     */
    public function setReason(string $reason): RefundBuilderInterface
    {
        $this->refund->setReason($reason);

        return $this;
    }

    /**
     * Refund ID in merchant service.
     *
     * Min 1 character, max 36 characters.
     *
     * This value is required!
     *
     * @param string $externalRefundId
     *
     * @return RefundBuilderInterface
     */
    public function setExternalRefundId(string $externalRefundId): RefundBuilderInterface
    {
        $this->refund->setExternalRefundId($externalRefundId);

        return $this;
    }

    /**
     * Partial refund amount.
     *
     * If this value is set then partial currency is required.
     * Use `setCurrency` method to define partial refund currency.
     *
     * Max. 21 characters with support for 4 places after
     * the decimal separator (the dot is used as the decimal separator).
     *
     * @param string $value
     *
     * @return RefundBuilderInterface
     */
    public function setValue(string $value): RefundBuilderInterface
    {
        $this->refund->setValue($value);

        return $this;
    }

    /**
     * Partial refund currency code in accordance with ISO 4217.
     *
     * If this value is set then partial value is required.
     * Use `setValue` method to define partial refund value.
     *
     * @param string $currency
     *
     * @return RefundBuilderInterface
     */
    public function setCurrency(string $currency): RefundBuilderInterface
    {
        $this->refund->setCurrency($currency);

        return $this;
    }

    /**
     * Notification URL.
     *
     * Payment Service will send information about
     * the course of the refund to this URL.
     *
     * Value is not required.
     * Can be set in Merchant panel or as a global value in configuration.
     *
     * Min 1 character, max 256 characters.
     *
     * @param string $notificationUrl
     *
     * @return RefundBuilderInterface
     */
    public function setNotificationUrl(string $notificationUrl): RefundBuilderInterface
    {
        $this->refund->setNotificationUrl($notificationUrl);

        return $this;
    }

    /**
     * Sets refund integration platform.
     *
     * @param string $integrationPlatform
     *
     * @return RefundBuilderInterface
     */
    public function setIntegrationPlatform(string $integrationPlatform): RefundBuilderInterface
    {
        $this->refund->setIntegrationPlatform($integrationPlatform);

        return $this;
    }

    /**
     * Sets Accept Language.
     *
     * @param string|null $acceptLanguage
     *
     * @return RefundBuilderInterface
     */
    public function setAcceptLanguage(?string $acceptLanguage): RefundBuilderInterface
    {
        $this->refund->setAcceptLanguage($acceptLanguage);

        return $this;
    }

    /**
     * Sets Notification Url Parameters.
     *
     * @param mixed $notificationUrlParameters
     *
     * @return RefundBuilderInterface
     */
    public function setNotificationUrlParameters($notificationUrlParameters): RefundBuilderInterface
    {
        $this->refund->setNotificationUrlParameters($notificationUrlParameters);

        return $this;
    }

    /**
     * Returns Refund definition.
     *
     * @return RefundInterface
     * @throws RefundException if one of required parameters is missing
     *
     */
    public function getRefund(): RefundInterface
    {
        if (null === $this->refund->getPaymentId()) {
            throw new RefundException('Missing payment ID in refund.');
        }

        if (null === $this->refund->getReason()) {
            throw new RefundException('Missing reason in refund.');
        }

        return $this->refund;
    }


}
