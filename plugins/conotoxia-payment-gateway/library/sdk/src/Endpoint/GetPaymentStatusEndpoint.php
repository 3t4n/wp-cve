<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\Endpoint\AuthenticationEndpointException;
use CKPL\Pay\Exception\Endpoint\GetPaymentStatusEndpointException;
use CKPL\Pay\Exception\EndpointException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Request\GetPaymentStatusRequestModel;
use CKPL\Pay\Model\Response\PaymentStatusResponseModel;
use function sprintf;

/**
 * Class GetPaymentStatusEndpoint.
 *
 * @package CKPL\Pay\Endpoint
 */
class GetPaymentStatusEndpoint implements EndpointInterface
{
    /**
     * @type string
     */
    const ENDPOINT = 'payments/status';

    /**
     * @type string
     */
    const REQUEST_PAYMENT_ID = 'payment_id';

    /**
     * @type string
     */
    const RESPONSE_PAYMENT_ID = 'paymentId';

    /**
     * @type string
     */
    const RESPONSE_PAYMENT_STATUS = 'paymentStatus';

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * GetPaymentStatusEndpoint constructor.
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
            ->asGet()
            ->toPayments()
            ->encodeWithJson()
            ->expectSignedResponse()
            ->authorized();
    }

    /**
     * @param array $parameters
     *
     * @return ProcessedInputInterface|null
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface
    {
        $result = null;

        if (!empty($parameters)) {
            $result = new GetPaymentStatusRequestModel();

            $this->assignInput($parameters, $result);
        }

        return $result;

    }

    /**
     * @param array $parameters
     * @param GetPaymentStatusRequestModel $getPaymentStatusRequestModel
     * @return void
     */
    protected function assignInput(array $parameters, GetPaymentStatusRequestModel $getPaymentStatusRequestModel): void
    {
        if (isset($parameters[static::REQUEST_PAYMENT_ID])) {
            $getPaymentStatusRequestModel->setPaymentId($parameters[static::REQUEST_PAYMENT_ID]);
        }
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @return ProcessedOutputInterface
     * @throws GetPaymentStatusEndpointException
     * @throws AuthenticationEndpointException
     *
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        try {
            $payload = $rawOutput->getPayload();
            $paymentStatusResponse = new PaymentStatusResponseModel();

            if (!$payload->hasElement(static::RESPONSE_PAYMENT_ID) || empty($payload->expectStringOrNull(static::RESPONSE_PAYMENT_ID))) {
                throw new AuthenticationEndpointException(
                    sprintf(EndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_PAYMENT_ID)
                );
            }
            $paymentStatusResponse->setPaymentId($payload->expectStringOrNull(static::RESPONSE_PAYMENT_ID));


            if (!$payload->hasElement(static::RESPONSE_PAYMENT_STATUS) || empty($payload->expectStringOrNull(static::RESPONSE_PAYMENT_STATUS))) {
                throw new AuthenticationEndpointException(
                    sprintf(EndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_PAYMENT_STATUS)
                );
            }
            $paymentStatusResponse->setStatus($payload->expectStringOrNull(static::RESPONSE_PAYMENT_STATUS));


            return $paymentStatusResponse;
        } catch (PayloadException $e) {
            throw new GetPaymentStatusEndpointException('Unable to get payment status.', 0, $e);
        }
    }
}