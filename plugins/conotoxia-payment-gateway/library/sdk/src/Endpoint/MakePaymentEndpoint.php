<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\Endpoint\MakePaymentEndpointException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Request\PaymentRequestModel;
use CKPL\Pay\Model\Request\StoreCustomerRequestModel;
use CKPL\Pay\Model\Request\TotalAmountRequestModel;
use CKPL\Pay\Model\Response\CreatedPaymentResponseModel;
use CKPL\Pay\Pay;
use function sprintf;

/**
 * Class MakePaymentEndpoint.
 *
 * @package CKPL\Pay\Endpoint
 */
class MakePaymentEndpoint implements EndpointInterface
{
    /**
     * @type string
     */
    const PARAMETER_INTERNAL_PAYMENT_ID = 'internal_payment_id';

    /**
     * @type string
     */
    const PARAMETER_POINT_OF_SALE = 'point_of_sale';

    /**
     * @type string
     */
    const PARAMETER_CURRENCY = 'currency';

    /**
     * @type string
     */
    const PARAMETER_VALUE = 'value';

    /**
     * @type string
     */
    const PARAMETER_CATEGORY = 'category';

    /**
     * @type string
     */
    const PARAMETER_DESCRIPTION = 'description';

    /**
     * @type string
     */
    const PARAMETER_NOTIFICATION_URL = 'notification_url';

    /**
     * @type string
     */
    const PARAMETER_RETURN_URL = 'return_url';

    /**
     * @type string
     */
    const PARAMETER_ERROR_URL = 'error_url';

    /**
     * @type string
     */
    const STORE_CUSTOMER = 'store_customer';

    /**
     * @type string
     */
    const PARAMETER_DISABLE_PAY_LATER = 'allow_pay_later';

    /**
     * @type string
     */
    const PARAMETER_INTEGRATION_PLATFORM = 'integrationPlatform';

    /**
     * @type string
     */
    const RESPONSE_PAYMENT_ID = 'paymentId';

    /**
     * @type string
     */
    const RESPONSE_APPROVE_URL = 'approveUrl';

    /**
     * @type string
     */
    const RESPONSE_TOKEN = 'token';

    /**
     * @type string
     */
    const PARAMETER_NOTIFICATION_URL_PARAMETERS = 'notificationUrlParameters';

    /**
     * @type string
     */
    protected const ENDPOINT = 'payments';

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * MakePaymentEndpoint constructor.
     *
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param EndpointConfigurationFactoryInterface $configurationFactory
     *
     * @return void
     */
    public function configuration(EndpointConfigurationFactoryInterface $configurationFactory): void
    {
        $configurationFactory
            ->url(static::ENDPOINT)
            ->asPost()
            ->toPayments()
            ->encodeWithJson()
            ->expectSignedResponse()
            ->signRequest()
            ->authorized();
    }

    /**
     * @param array $parameters
     *
     * @return ProcessedInputInterface|null
     * @throws MakePaymentEndpointException
     *
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface
    {
        $this->validateInput($parameters);

        $totalAmount = new TotalAmountRequestModel();
        $totalAmount->setCurrency($parameters[static::PARAMETER_CURRENCY]);
        $totalAmount->setValue($parameters[static::PARAMETER_VALUE]);

        $model = new PaymentRequestModel();
        $model->setExternalPaymentId($parameters[static::PARAMETER_INTERNAL_PAYMENT_ID]);
        $model->setPointOfSaleId($parameters[static::PARAMETER_POINT_OF_SALE]);
        $model->setCategory($parameters[static::PARAMETER_CATEGORY]);
        $model->setTotalAmount($totalAmount);
        $model->setDescription($parameters[static::PARAMETER_DESCRIPTION]);

        if (isset($parameters[static::PARAMETER_INTEGRATION_PLATFORM])) {
            $model->setIntegrationPlatform('SDK=' . Pay::getSDKVersion() . ';PHP=' . PHP_VERSION . ';' . $parameters[static::PARAMETER_INTEGRATION_PLATFORM]);
        } else {
            $model->setIntegrationPlatform('SDK=' . Pay::getSDKVersion() . ';PHP=' . PHP_VERSION);
        }

        if (isset($parameters[static::PARAMETER_DISABLE_PAY_LATER])) {
            $model->setDisablePayLater(!$parameters[static::PARAMETER_DISABLE_PAY_LATER]);
        }

        if (isset($parameters[static::PARAMETER_RETURN_URL])) {
            $model->setReturnUrl($parameters[static::PARAMETER_RETURN_URL]);
        } else {
            if (null !== $this->configuration->getReturnUrl()) {
                $model->setReturnUrl($this->configuration->getReturnUrl());
            }
        }

        if (isset($parameters[static::PARAMETER_ERROR_URL])) {
            $model->setErrorUrl($parameters[static::PARAMETER_ERROR_URL]);
        } else {
            if (null !== $this->configuration->getErrorUrl()) {
                $model->setErrorUrl($this->configuration->getErrorUrl());
            }
        }

        if (isset($parameters[static::PARAMETER_NOTIFICATION_URL])) {
            $model->setNotificationUrl($parameters[static::PARAMETER_NOTIFICATION_URL]);
        } else {
            if (null !== $this->configuration->getPaymentsNotificationUrl()) {
                $model->setNotificationUrl($this->configuration->getPaymentsNotificationUrl());
            }
        }

        if (isset($parameters[static::STORE_CUSTOMER])) {
            $storeCustomer = $parameters[static::STORE_CUSTOMER];
            $storeCustomerModel = new StoreCustomerRequestModel();
            $storeCustomerModel->setFirstName($storeCustomer->getFirstName());
            $storeCustomerModel->setLastName($storeCustomer->getLastName());
            $storeCustomerModel->setEmail($storeCustomer->getEmail());
            $model->setStoreCustomer($storeCustomerModel);
        }

        if (isset($parameters[static::PARAMETER_NOTIFICATION_URL_PARAMETERS])) {
            $model->setNotificationUrlParameters($parameters[static::PARAMETER_NOTIFICATION_URL_PARAMETERS]);
        }

        return $model;
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @return ProcessedOutputInterface
     * @throws MakePaymentEndpointException
     *
     * @throws PayloadException
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        $payment = $rawOutput->getPayload();

        if (!$payment->hasElement(static::RESPONSE_PAYMENT_ID)) {
            throw new MakePaymentEndpointException(
                sprintf(MakePaymentEndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_PAYMENT_ID)
            );
        }

        if (!$payment->hasElement(static::RESPONSE_APPROVE_URL)) {
            throw new MakePaymentEndpointException(
                sprintf(MakePaymentEndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_APPROVE_URL)
            );
        }

        if (!$payment->hasElement(static::RESPONSE_TOKEN)) {
            throw new MakePaymentEndpointException(
                sprintf(MakePaymentEndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_TOKEN)
            );
        }

        $model = new CreatedPaymentResponseModel();
        $model->setPaymentId($payment->expectStringOrNull(static::RESPONSE_PAYMENT_ID));
        $model->setApproveUrl($payment->expectStringOrNull(static::RESPONSE_APPROVE_URL));
        $model->setToken($payment->expectStringOrNull(static::RESPONSE_TOKEN));

        return $model;
    }

    /**
     * @param array $parameters
     *
     * @return void
     * @throws MakePaymentEndpointException
     *
     */
    protected function validateInput(array $parameters): void
    {
        $requiredParameters = [
            static::PARAMETER_CURRENCY, static::PARAMETER_VALUE,
            static::PARAMETER_INTERNAL_PAYMENT_ID, static::PARAMETER_POINT_OF_SALE,
            static::PARAMETER_CATEGORY, static::PARAMETER_DESCRIPTION,
        ];

        foreach ($requiredParameters as $requiredParameter) {
            if (!isset($parameters[$requiredParameter])) {
                throw new MakePaymentEndpointException(
                    sprintf(MakePaymentEndpointException::MISSING_REQUEST_PARAMETERS, $requiredParameter)
                );
            }
        }
    }
}
