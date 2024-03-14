<?php

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\Endpoint\AuthenticationEndpointException;
use CKPL\Pay\Exception\Endpoint\ConfirmPaymentEndpointException;
use CKPL\Pay\Exception\EndpointException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Request\ConfirmPaymentRequestModel;
use CKPL\Pay\Model\Response\ConfirmPaymentResponseModel;

/**
 * Class ConfirmPaymentEndpoint.php.
 *
 * @package CKPL\Pay\Endpoint
 */
class ConfirmPaymentEndpoint implements EndpointInterface
{

    /**
     * @type string
     */
    const RESPONSE_PAYMENT_STATUS = 'paymentStatus';

    /**
     * @type string
     */
    const REQUEST_BLIK_CODE = 'blikCode';

    /**
     * @type string
     */
    const REQUEST_TYPE = 'type';

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var string
     */
    private $token;

    /**
     * ConfirmPaymentEndpoint.php constructor.
     *
     * @param ConfigurationInterface $configuration
     * @param string $token
     */
    public function __construct(ConfigurationInterface $configuration, string $token)
    {
        $this->configuration = $configuration;
        $this->token = $token;
    }

    /**
     * @param EndpointConfigurationFactoryInterface $configurationFactory
     *
     * @return void
     */
    public function configuration(EndpointConfigurationFactoryInterface $configurationFactory): void
    {
        $configurationFactory->url($this->getUrl())->asPost()->toPayments()->signRequest()->encodeWithJson()->expectSignedResponse()->authorized();
    }

    /**
     * @return string
     */
    private function getUrl(): string
    {
        return 'payments/token/' . $this->token . '/confirmations';
    }

    /**
     * @param array $parameters
     *
     * @return ProcessedInputInterface|null
     * @throws ConfirmPaymentEndpointException
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface
    {
        $result = null;

        if (!empty($parameters)) {
            $result = $this->assignInput($parameters);
        }

        return $result;
    }

    /**
     * @param array $parameters
     * @return ProcessedInputInterface|null
     * @throws ConfirmPaymentEndpointException
     */
    protected function assignInput(array $parameters): ?ProcessedInputInterface
    {
        $result = new ConfirmPaymentRequestModel();

        if (isset($parameters[static::REQUEST_BLIK_CODE])) {
            $result->setBlikCode($parameters[static::REQUEST_BLIK_CODE]);
        }

        if (isset($parameters[static::REQUEST_TYPE])) {
            $result->setPaymentMethodType($parameters[static::REQUEST_TYPE]);
        } else {
            throw new ConfirmPaymentEndpointException('Payment method type is required', 0);
        }

        if (isset($parameters["additionalData"]->email)) {
            $result->setEmail($parameters["additionalData"]->email);
        }

        if (isset($parameters["additionalData"]->firstName)) {
            $result->setFirstName($parameters["additionalData"]->firstName);
        }

        if (isset($parameters["additionalData"]->lastName)) {
            $result->setLastName($parameters["additionalData"]->lastName);
        }

        return $result;
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @return ProcessedOutputInterface
     * @throws AuthenticationEndpointException
     * @throws ConfirmPaymentEndpointException
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        try {
            $payload = $rawOutput->getPayload();
            $confirmPaymentResponseModel = new ConfirmPaymentResponseModel();

            if (!$payload->hasElement(static::RESPONSE_PAYMENT_STATUS) || empty($payload->expectStringOrNull(static::RESPONSE_PAYMENT_STATUS))) {
                throw new AuthenticationEndpointException(sprintf(EndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_PAYMENT_STATUS));
            }
            $confirmPaymentResponseModel->setPaymentStatus($payload->expectStringOrNull(static::RESPONSE_PAYMENT_STATUS));

            return $confirmPaymentResponseModel;
        } catch (PayloadException $e) {
            throw new ConfirmPaymentEndpointException('Unable to confirm payment.', 0, $e);
        }
    }
}
