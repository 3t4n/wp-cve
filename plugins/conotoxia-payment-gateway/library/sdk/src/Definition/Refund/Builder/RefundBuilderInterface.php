<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Refund\Builder;

use CKPL\Pay\Definition\Refund\RefundInterface;

/**
 * Interface RefundBuilderInterface.
 *
 * @package CKPL\Pay\Definition\Refund\Builder
 */
interface RefundBuilderInterface
{
    /**
     * @param string $paymentId
     *
     * @return RefundBuilderInterface
     */
    public function setPaymentId(string $paymentId): RefundBuilderInterface;

    /**
     * @param string $reason
     *
     * @return RefundBuilderInterface
     */
    public function setReason(string $reason): RefundBuilderInterface;

    /**
     * @param string $externalRefundId
     *
     * @return RefundBuilderInterface
     */
    public function setExternalRefundId(string $externalRefundId): RefundBuilderInterface;

    /**
     * @param string $value
     *
     * @return RefundBuilderInterface
     */
    public function setValue(string $value): RefundBuilderInterface;

    /**
     * @param string $currency
     *
     * @return RefundBuilderInterface
     */
    public function setCurrency(string $currency): RefundBuilderInterface;

    /**
     * @param string $notificationUrl
     *
     * @return RefundBuilderInterface
     */
    public function setNotificationUrl(string $notificationUrl): RefundBuilderInterface;

    /**
     * @param string $integrationPlatform
     *
     * @return RefundBuilderInterface
     */
    public function setIntegrationPlatform(string $integrationPlatform): RefundBuilderInterface;

    /**
     * Sets Accept Language.
     *
     * @param string $acceptLanguage
     *
     * @return RefundBuilderInterface
     */
    public function setAcceptLanguage(string $acceptLanguage): RefundBuilderInterface;

    /**
     * Sets Notification Url Parameters.
     *
     * @param mixed $notificationUrlParameters
     *
     * @return RefundBuilderInterface
     */
    public function setNotificationUrlParameters($notificationUrlParameters): RefundBuilderInterface;

    /**
     * @return RefundInterface
     */
    public function getRefund(): RefundInterface;
}
