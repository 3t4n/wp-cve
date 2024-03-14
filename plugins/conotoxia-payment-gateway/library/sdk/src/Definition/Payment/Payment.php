<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Payment;

use CKPL\Pay\Definition\Amount\AmountInterface;
use CKPL\Pay\Definition\StoreCustomer\StoreCustomerInterface;

/**
 * Class Payment.
 *
 * @package CKPL\Pay\Definition\Payment
 */
class Payment implements PaymentInterface
{
    /**
     * @var string|null
     */
    protected $externalPaymentId;

    /**
     * @var AmountInterface|null
     */
    protected $amount;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var bool|null
     */
    protected $allowPayLater;

    /**
     * @var string|null
     */
    protected $returnUrl;

    /**
     * @var string|null
     */
    protected $errorUrl;

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
     * @var string|null
     */
    protected $preferredUserLocale;

    /**
     * @var mixed|string|null
     */
    protected $notificationUrlParameters;

    /**
     * @var StoreCustomerInterface|null
     */
    protected $storeCustomer;

    /**
     * @return string|null
     */
    public function getExternalPaymentId(): ?string
    {
        return $this->externalPaymentId;
    }

    /**
     * @param string $externalPaymentId
     * @return void
     */
    public function setExternalPaymentId(string $externalPaymentId): void
    {
        $this->externalPaymentId = $externalPaymentId;
    }

    /**
     * @return AmountInterface|null
     */
    public function getAmount(): ?AmountInterface
    {
        return $this->amount;
    }

    /**
     * @param AmountInterface $amount
     * @return void
     */
    public function setAmount(AmountInterface $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool|null
     */
    public function getAllowPayLater(): ?bool
    {
        return $this->allowPayLater;
    }

    /**
     * @param bool $allowPayLater
     * @return void
     */
    public function setAllowPayLater(bool $allowPayLater): void
    {
        $this->allowPayLater = $allowPayLater;
    }

    /**
     * @return string|null
     */
    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    /**
     * @param string|null $returnUrl
     * @return void
     */
    public function setReturnUrl(?string $returnUrl): void
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return string|null
     */
    public function getErrorUrl(): ?string
    {
        return $this->errorUrl;
    }

    /**
     * @param string|null $errorUrl
     * @return void
     */
    public function setErrorUrl(?string $errorUrl): void
    {
        $this->errorUrl = $errorUrl;
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
     * @return void
     */
    public function setNotificationUrl(?string $notificationUrl): void
    {
        $this->notificationUrl = $notificationUrl;
    }

    /**
     * @return string|null
     */
    public function getIntegrationPlatform(): string
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
     * @return string|null
     */
    public function getPreferredUserLocale(): ?string
    {
        return $this->preferredUserLocale;
    }

    /**
     * @param string|null $preferredUserLocale
     */
    public function setPreferredUserLocale(?string $preferredUserLocale): void
    {
        $this->preferredUserLocale = $preferredUserLocale;
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

    /**
     * @return StoreCustomerInterface|null
     */
    public function getStoreCustomer(): ?StoreCustomerInterface
    {
        return $this->storeCustomer;
    }

    /**
     * @param StoreCustomerInterface $storeCustomer
     */
    public function setStoreCustomer(StoreCustomerInterface $storeCustomer): void
    {
        $this->storeCustomer = $storeCustomer;
    }
}
