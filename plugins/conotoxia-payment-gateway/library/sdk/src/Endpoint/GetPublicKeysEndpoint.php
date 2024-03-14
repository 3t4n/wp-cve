<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\Collection\PublicKeyResponseModelCollection;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Response\PublicKeyResponseModel;

/**
 * Class GetPublicKeysEndpoint.
 *
 * @package CKPL\Pay\Endpoint
 */
class GetPublicKeysEndpoint implements EndpointInterface
{
    /**
     * @type string
     */
    const RESPONSE_PUBLIC_KEYS = 'publicKeys';

    /**
     * @type string
     */
    const RESPONSE_PUBLIC_KEYS_KEY_ID = 'kid';

    /**
     * @type string
     */
    const RESPONSE_PUBLIC_KEYS_PEM = 'pem';

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
            ->asGet()
            ->toPayments()
            ->encodeWithJson()
            ->expectSignedResponse()
            ->plainRequest()
            ->authorized();
    }

    /**
     * @param array $parameters
     *
     * @return ProcessedInputInterface|null
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface
    {
        return null;
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @throws PayloadException
     *
     * @return ProcessedOutputInterface
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        $payload = $rawOutput->getPayload();
        $keyCollection = new PublicKeyResponseModelCollection();

        if ($payload->hasElement(static::RESPONSE_PUBLIC_KEYS)) {
            foreach (($payload->expectArrayOrNull(static::RESPONSE_PUBLIC_KEYS) ?? []) as $key) {
                $publicKey = new PublicKeyResponseModel();

                $publicKey->setKeyId($key[static::RESPONSE_PUBLIC_KEYS_KEY_ID]);
                $publicKey->setKey($key[static::RESPONSE_PUBLIC_KEYS_PEM]);

                $keyCollection->add($publicKey);
            }
        }

        return $keyCollection;
    }
}
