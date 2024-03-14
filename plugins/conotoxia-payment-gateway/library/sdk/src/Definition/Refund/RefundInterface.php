<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Refund;

/**
 * Interface RefundInterface.
 *
 * @package CKPL\Pay\Definition\Refund
 */
interface RefundInterface
{
    /**
     * @return string|null
     */
    public function getPaymentId(): ?string;

    /**
     * @return string|null
     */
    public function getReason(): ?string;

    /**
     * @return string|null
     */
    public function getExternalRefundId(): ?string;

    /**
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * @return string|null
     */
    public function getNotificationUrl(): ?string;

    /**
     * @return string|null
     */
    public function getIntegrationPlatform(): ?string;

    /**
     * @return string|null
     */
    public function getAcceptLanguage(): ?string;

    /**
     * @return mixed
     */
    public function getNotificationUrlParameters();
}
