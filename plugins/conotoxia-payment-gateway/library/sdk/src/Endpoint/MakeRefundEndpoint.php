<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\Endpoint\MakeRefundEndpointException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Request\RefundRequestModel;
use CKPL\Pay\Model\Request\TotalAmountRequestModel;
use CKPL\Pay\Model\Response\CreatedRefundResponseModel;
use CKPL\Pay\Pay;
use function sprintf;

/**
 * Class MakeRefundEndpoint.
 *
 * @package CKPL\Pay\Endpoint
 */
class MakeRefundEndpoint implements EndpointInterface
{
    /**
     * @type string
     */
    const PARAMETER_PAYMENT_ID = 'payment_id';

    /**
     * @type string
     */
    const PARAMETER_REASON = 'reason';

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
    const PARAMETER_EXTERNAL_REFUND_ID = 'external_refund_id';

    /**
     * @type string
     */
    const PARAMETER_NOTIFICATION_URL = 'notification_url';

    /**
     * @type string
     */
    const PARAMETER_INTEGRATION_PLATFORM = 'integrationPlatform';

    /**
     * @type string
     */
    const PARAMETER_NOTIFICATION_URL_PARAMETERS = 'notificationUrlParameters';

    /**
     * @type string
     */
    protected const ENDPOINT = 'refunds';

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * MakeRefundEndpoint constructor.
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
     * @throws MakeRefundEndpointException
     *
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface
    {
        $model = new RefundRequestModel();

        $this->resolveRequestModel($parameters, $model);

        return $model;
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @return ProcessedOutputInterface
     * @throws MakeRefundEndpointException
     *
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        try {
            $payload = $rawOutput->getPayload();
            $model = new CreatedRefundResponseModel();

            $model->setId($payload->expectStringOrNull('id'));
        } catch (PayloadException $e) {
            throw new MakeRefundEndpointException(
                sprintf(MakeRefundEndpointException::MISSING_RESPONSE_PARAMETER, 'id')
            );
        }

        return $model;
    }

    /**
     * @param array $parameters
     * @param RefundRequestModel $refundRequestModel
     *
     * @return void
     * @throws MakeRefundEndpointException
     *
     */
    protected function resolveRequestModel(array $parameters, RefundRequestModel $refundRequestModel): void
    {
        foreach ([static::PARAMETER_PAYMENT_ID, static::PARAMETER_REASON] as $parameter) {
            if (!isset($parameters[$parameter])) {
                throw new MakeRefundEndpointException(
                    sprintf(MakeRefundEndpointException::MISSING_REQUEST_PARAMETER, $parameter)
                );
            }
        }

        $refundRequestModel->setPaymentId($parameters[static::PARAMETER_PAYMENT_ID]);
        $refundRequestModel->setReason($parameters[static::PARAMETER_REASON]);

        if (isset($parameters[static::PARAMETER_CURRENCY], $parameters[static::PARAMETER_VALUE])) {
            $refundRequestModel->setAmount(
                (new TotalAmountRequestModel())
                    ->setCurrency($parameters[static::PARAMETER_CURRENCY])
                    ->setValue($parameters[static::PARAMETER_VALUE])
            );
        }

        if (isset($parameters[static::PARAMETER_EXTERNAL_REFUND_ID])) {
            $refundRequestModel->setExternalRefundId($parameters[static::PARAMETER_EXTERNAL_REFUND_ID]);
        }

        if (isset($parameters[static::PARAMETER_NOTIFICATION_URL])) {
            $refundRequestModel->setNotificationUrl($parameters[static::PARAMETER_NOTIFICATION_URL]);
        } else {
            if (null !== $this->configuration->getRefundsNotificationUrl()) {
                $refundRequestModel->setNotificationUrl($this->configuration->getRefundsNotificationUrl());
            }
        }

        if (isset($parameters[static::PARAMETER_INTEGRATION_PLATFORM])) {
            $refundRequestModel->setIntegrationPlatform('SDK=' . Pay::getSDKVersion() . ';PHP=' . PHP_VERSION . ';' . $parameters[static::PARAMETER_INTEGRATION_PLATFORM]);
        } else {
            $refundRequestModel->setIntegrationPlatform('SDK=' . Pay::getSDKVersion() . ';PHP=' . PHP_VERSION);
        }

        if (isset($parameters[static::PARAMETER_NOTIFICATION_URL_PARAMETERS])) {
            $refundRequestModel->setNotificationUrlParameters($parameters[static::PARAMETER_NOTIFICATION_URL_PARAMETERS]);
        }
    }
}
