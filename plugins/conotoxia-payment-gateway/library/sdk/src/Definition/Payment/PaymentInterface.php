<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Payment;

use CKPL\Pay\Definition\Amount\AmountInterface;
use CKPL\Pay\Definition\StoreCustomer\StoreCustomerInterface;

/**
 * Interface PaymentInterface.
 *
 * @package CKPL\Pay\Definition\Payment
 */
interface PaymentInterface
{
    /**
     * @return string|null
     */
    public function getExternalPaymentId(): ?string;

    /**
     * @return AmountInterface|null
     */
    public function getAmount(): ?AmountInterface;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @return bool|null
     */
    public function getAllowPayLater(): ?bool;

    /**
     * @return string|null
     */
    public function getReturnUrl(): ?string;

    /**
     * @return string|null
     */
    public function getErrorUrl(): ?string;

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
     * @return string|null
     */
    public function getPreferredUserLocale(): ?string;

    /**
     * @return mixed
     */
    public function getNotificationUrlParameters();

    /**
     * @return StoreCustomerInterface|null
     */
    public function getStoreCustomer(): ?StoreCustomerInterface;
}
