<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\Endpoint\JwksEndpointException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\Collection\PaymentServiceKeyResponseModelCollection;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\RequestModelInterface;
use CKPL\Pay\Model\Response\PaymentServiceKeyResponseModel;
use CKPL\Pay\Model\ResponseModelInterface;
use function sprintf;

/**
 * Class JwksEndpoint.
 *
 * @package CKPL\Pay\Endpoint
 */
class JwksEndpoint implements EndpointInterface
{
    /**
     * @type string
     */
    const RESPONSE_KEYS = 'keys';

    /**
     * @type string
     */
    const RESPONSE_KEYS_KEY_TYPE = 'kty';

    /**
     * @type string
     */
    const RESPONSE_KEYS_KEY_ID = 'kid';

    /**
     * @type string
     */
    const RESPONSE_KEYS_USE = 'use';

    /**
     * @type string
     */
    const RESPONSE_KEYS_MODULUS = 'n';

    /**
     * @type string
     */
    const RESPONSE_KEYS_EXPONENT = 'e';

    /**
     * @type string
     */
    protected const ENDPOINT = 'jwks';

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
            ->expectPlainResponse()
            ->plainRequest()
            ->authorized();
    }

    /**
     * @param array $parameters
     *
     * @return RequestModelInterface|null
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface
    {
        return null;
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @throws PayloadException
     * @throws JwksEndpointException
     *
     * @return ResponseModelInterface
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        $payload = $rawOutput->getPayload();
        $keyCollection = new PaymentServiceKeyResponseModelCollection();

        if (!$payload->hasElement(static::RESPONSE_KEYS)) {
            throw new JwksEndpointException(
                sprintf(JwksEndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_KEYS)
            );
        }

        foreach (($payload->expectArrayOrNull(static::RESPONSE_KEYS) ?? []) as $key) {
            $keyResponseModel = new PaymentServiceKeyResponseModel();

            $keyResponseModel->setKeyId($key[static::RESPONSE_KEYS_KEY_ID]);
            $keyResponseModel->setKeyType($key[static::RESPONSE_KEYS_KEY_TYPE]);
            $keyResponseModel->setUsage($key[static::RESPONSE_KEYS_USE]);
            $keyResponseModel->setModulus($key[static::RESPONSE_KEYS_MODULUS]);
            $keyResponseModel->setExponent($key[static::RESPONSE_KEYS_EXPONENT]);

            $keyCollection->add($keyResponseModel);
        }

        return $keyCollection;
    }
}
