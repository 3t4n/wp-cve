<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Endpoint\MakePaymentEndpoint;
use CKPL\Pay\Model\DisallowHTMLTagsTrimmer;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class PaymentRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class PaymentRequestModel implements RequestModelInterface
{
    /**
     * @var string|null
     */
    protected $externalPaymentId;

    /**
     * @var string|null
     */
    protected $pointOfSaleId;

    /**
     * @var string|null
     */
    protected $category;

    /**
     * @var TotalAmountRequestModel|null
     */
    protected $totalAmount;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var mixed|string|null
     */
    protected $notificationUrlParameters;

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
    protected $integrationPlatform;

    /**
     * @var string|null
     */
    protected $notificationUrl;

    /**
     * @var StoreCustomerRequestModel|null
     */
    protected $storeCustomer;

    /**
     * @var bool|null
     */
    protected $disablePayLater;

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
     * @return PaymentRequestModel
     */
    public function setExternalPaymentId(string $externalPaymentId): PaymentRequestModel
    {
        $this->externalPaymentId = $externalPaymentId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPointOfSaleId(): ?string
    {
        return $this->pointOfSaleId;
    }

    /**
     * @param string $pointOfSaleId
     *
     * @return PaymentRequestModel
     */
    public function setPointOfSaleId(string $pointOfSaleId): PaymentRequestModel
    {
        $this->pointOfSaleId = $pointOfSaleId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     *
     * @return PaymentRequestModel
     */
    public function setCategory(string $category): PaymentRequestModel
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return TotalAmountRequestModel|null
     */
    public function getTotalAmount(): ?TotalAmountRequestModel
    {
        return $this->totalAmount;
    }

    /**
     * @param TotalAmountRequestModel|null $totalAmount
     *
     * @return PaymentRequestModel
     */
    public function setTotalAmount(TotalAmountRequestModel $totalAmount): PaymentRequestModel
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return DisallowHTMLTagsTrimmer::trim($this->description);
    }

    /**
     * @param string $description
     *
     * @return PaymentRequestModel
     */
    public function setDescription(string $description): PaymentRequestModel
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     *
     * @return PaymentRequestModel
     */
    public function setReturnUrl(string $returnUrl): PaymentRequestModel
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrorUrl(): ?string
    {
        return $this->errorUrl;
    }

    /**
     * @param string $errorUrl
     *
     * @return PaymentRequestModel
     */
    public function setErrorUrl(string $errorUrl): PaymentRequestModel
    {
        $this->errorUrl = $errorUrl;

        return $this;
    }

    /**
     * @param string|null $integrationPlatform
     *
     * @return PaymentRequestModel
     */
    public function setIntegrationPlatform(string $integrationPlatform): PaymentRequestModel
    {
        $this->integrationPlatform = $integrationPlatform;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIntegrationPlatform(): string
    {
        return $this->integrationPlatform;
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
     * @return PaymentRequestModel
     */
    public function setNotificationUrl(string $notificationUrl): PaymentRequestModel
    {
        $this->notificationUrl = $notificationUrl;

        return $this;
    }

    /**
     * @return StoreCustomerRequestModel|null
     */
    public function getStoreCustomer(): ?StoreCustomerRequestModel
    {
        return $this->storeCustomer;
    }

    /**
     * @param StoreCustomerRequestModel $storeCustomer
     *
     * @return PaymentRequestModel
     */
    public function setStoreCustomer(StoreCustomerRequestModel $storeCustomer): PaymentRequestModel
    {
        $this->storeCustomer = $storeCustomer;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getDisablePayLater(): ?bool
    {
        return $this->disablePayLater;
    }

    /**
     * @param bool $disablePayLater
     *
     * @return PaymentRequestModel
     */
    public function setDisablePayLater(bool $disablePayLater): PaymentRequestModel
    {
        $this->disablePayLater = $disablePayLater;

        return $this;
    }

    /**
     * @param mixed $notificationUrlParameters
     *
     * @return PaymentRequestModel
     */
    public function setNotificationUrlParameters($notificationUrlParameters): PaymentRequestModel
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
     * @return string
     */
    public function getEndpoint(): string
    {
        return MakePaymentEndpoint::class;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        $result = [
            'externalPaymentId' => $this->getExternalPaymentId(),
            'pointOfSaleId' => $this->getPointOfSaleId(),
            'category' => $this->getCategory(),
            'totalAmount' => ($this->getTotalAmount() ? $this->getTotalAmount()->raw() : null),
            'description' => $this->getDescription(),
            'integrationPlatform' => $this->getIntegrationPlatform(),
            'notificationUrlParameters' => $this->getNotificationUrlParameters()
        ];

        if (null !== $this->getReturnUrl()) {
            $result['returnUrl'] = $this->getReturnUrl();
        }

        if (null !== $this->getErrorUrl()) {
            $result['errorUrl'] = $this->getErrorUrl();
        }

        if (null !== $this->getNotificationUrl()) {
            $result['notificationUrl'] = $this->getNotificationUrl();
        }

        if (null !== $this->getStoreCustomer()) {
            $result['storeCustomer'] = $this->getStoreCustomer()->raw();
        }

        if (null !== $this->getDisablePayLater()) {
            $result['disablePayLater'] = $this->getDisablePayLater();
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return RequestModelInterface::JSON_OBJECT;
    }
}
