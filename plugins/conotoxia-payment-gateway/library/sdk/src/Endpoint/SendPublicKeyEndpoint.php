<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\Endpoint\SendPublicKeyEndpointException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Request\PublicKeyRequestModel;
use CKPL\Pay\Model\Response\AddedKeyResponseModel;
use function sprintf;

/**
 * Class SendPublicKeyEndpoint.
 *
 * @package CKPL\Pay\Endpoint
 */
class SendPublicKeyEndpoint implements EndpointInterface
{
    /**
     * @type string
     */
    const PARAMETER_PUBLIC_KEY = 'public_key';

    /**
     * @type string
     */
    const RESPONSE_KEY_ID = 'kid';

    /**
     * @type string
     */
    const RESPONSE_STATUS = 'status';

    /**
     * @type string
     */
    protected const ENDPOINT = 'public_keys';

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
            ->plainRequest()
            ->authorized()
        ;
    }

    /**
     * @param array $parameters
     *
     * @throws SendPublicKeyEndpointException
     *
     * @return ProcessedInputInterface|null
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface
    {
        if (!isset($parameters[static::PARAMETER_PUBLIC_KEY])) {
            throw new SendPublicKeyEndpointException(
                sprintf(
                    SendPublicKeyEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::PARAMETER_PUBLIC_KEY
                )
            );
        }

        $model = new PublicKeyRequestModel();
        $model->setPublicKey($parameters[static::PARAMETER_PUBLIC_KEY]);

        return $model;
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @throws PayloadException
     * @throws SendPublicKeyEndpointException
     *
     * @return ProcessedOutputInterface
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        if (!$rawOutput->getPayload()->hasElement(static::RESPONSE_KEY_ID)) {
            throw new SendPublicKeyEndpointException(
                sprintf(SendPublicKeyEndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_KEY_ID)
            );
        }
        if (!$rawOutput->getPayload()->hasElement(static::RESPONSE_STATUS)) {
            throw new SendPublicKeyEndpointException(
                sprintf(SendPublicKeyEndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_STATUS)
            );
        }

        $model = new AddedKeyResponseModel();
        $model->setKeyId($rawOutput->getPayload()->expectStringOrNull(static::RESPONSE_KEY_ID));
        $model->setStatus($rawOutput->getPayload()->expectStringOrNull(static::RESPONSE_STATUS));

        return $model;
    }
}
