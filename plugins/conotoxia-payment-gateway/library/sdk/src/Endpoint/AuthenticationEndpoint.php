<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\Endpoint\AuthenticationEndpointException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Request\AccessTokenRequestModel;
use CKPL\Pay\Model\Response\AccessTokenResponseModel;
use function sprintf;

/**
 * Class AuthenticationEndpoint.
 *
 * @package CKPL\Pay\Endpoint
 */
class AuthenticationEndpoint implements EndpointInterface
{
    /**
     * @type string
     */
    const PARAMETER_GRANT_TYPE = 'grant_type';

    /**
     * @type string
     */
    const PARAMETER_SCOPE = 'scope';

    /**
     * @type string
     */
    const RESPONSE_ACCESS_TOKEN = 'access_token';

    /**
     * @type string
     */
    const RESPONSE_EXPIRES_IN = 'expires_in';

    /**
     * @type string
     */
    const RESPONSE_TOKEN_TYPE = 'token_type';

    /**
     * @type string
     */
    protected const ENDPOINT = 'connect/token';

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
            ->toOidc()
            ->disableJsonEncoding()
            ->expectPlainResponse()
            ->plainRequest()
            ->withCredentials();
    }

    /**
     * @param array $parameters
     *
     * @return ProcessedInputInterface|null
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface
    {
        return new AccessTokenRequestModel();
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @throws AuthenticationEndpointException
     * @throws PayloadException
     *
     * @return ProcessedOutputInterface
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        $accessTokenResponseModel = new AccessTokenResponseModel();
        $result = $rawOutput->getPayload();

        if (!$result->hasElement(static::RESPONSE_ACCESS_TOKEN) || empty($result->expectStringOrNull(static::RESPONSE_ACCESS_TOKEN))) {
            throw new AuthenticationEndpointException(
                sprintf(AuthenticationEndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_ACCESS_TOKEN)
            );
        }

        $accessTokenResponseModel->setAccessToken($result->expectStringOrNull(static::RESPONSE_ACCESS_TOKEN));

        if (!$result->hasElement(static::RESPONSE_EXPIRES_IN) || empty($result->expectIntOrNull(static::RESPONSE_EXPIRES_IN))) {
            throw new AuthenticationEndpointException(
                sprintf(AuthenticationEndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_EXPIRES_IN)
            );
        }

        $accessTokenResponseModel->setExpiresIn($result->expectIntOrNull(static::RESPONSE_EXPIRES_IN));

        if (!$result->hasElement(static::RESPONSE_TOKEN_TYPE) || empty($result->expectStringOrNull(static::RESPONSE_TOKEN_TYPE))) {
            throw new AuthenticationEndpointException(
                sprintf(AuthenticationEndpointException::MISSING_RESPONSE_PARAMETER, static::RESPONSE_TOKEN_TYPE)
            );
        }

        $accessTokenResponseModel->setTokenType($result->expectStringOrNull(static::RESPONSE_TOKEN_TYPE));

        return $accessTokenResponseModel;
    }
}
