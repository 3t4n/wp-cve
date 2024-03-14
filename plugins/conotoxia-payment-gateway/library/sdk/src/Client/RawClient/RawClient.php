<?php

declare(strict_types=1);

namespace CKPL\Pay\Client\RawClient;

use CKPL\Pay\Client\RawOutput\RawOutput;
use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Definition\Payload\Payload;
use CKPL\Pay\Definition\Payload\PayloadInterface;
use CKPL\Pay\Endpoint\Configuration\EndpointConfigurationInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\ClientException;
use CKPL\Pay\Exception\Http\Factory\HttpExceptionFactory;
use CKPL\Pay\Exception\Http\HttpException;
use CKPL\Pay\Exception\IncompatibilityException;
use CKPL\Pay\Exception\InvalidClientException;
use CKPL\Pay\Exception\JsonFunctionException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Exception\RawClientException;
use CKPL\Pay\Model\RequestModelInterface;
use CKPL\Pay\Security\JWT\Collection\DecodedCollectionInterface;
use CKPL\Pay\Security\SecurityManagerInterface;
use function CKPL\Pay\json_decode_array;
use function CKPL\Pay\json_encode_array;
use function count;
use function in_array;
use function is_array;
use function substr;
use function trim;

/**
 * Class RawClient.
 *
 * @package CKPL\Pay\Client\RawClient
 */
class RawClient implements RawClientInterface
{
    /**
     * @type int
     */
    protected const RESPONSE_STATUS = 2;
    /**
     * @var SecurityManagerInterface
     */
    protected $securityManager;
    /**
     * @var string|null
     */
    protected $url;
    /**
     * @var array
     */
    protected $parameters;
    /**
     * @var int
     */
    protected $requestType;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var array
     */
    protected $headers;
    /**
     * @var string|null
     */
    protected $username;
    /**
     * @var string|null
     */
    protected $password;
    /**
     * @var bool
     */
    protected $signedRequest;
    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * RawClient constructor.
     *
     * @param SecurityManagerInterface $securityManager
     * @param ConfigurationInterface $configuration
     */
    public function __construct(SecurityManagerInterface $securityManager, ConfigurationInterface $configuration)
    {
        $this->securityManager = $securityManager;
        $this->configuration = $configuration;

        $this->method = EndpointConfigurationFactoryInterface::METHOD_GET;
        $this->parameters = [];
        $this->headers = [];

        $this->requestType = RequestModelInterface::FORM;
    }

    /**
     * @param string $url
     *
     * @return RawClientInterface
     */
    public function setUrl(string $url): RawClientInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param array $parameters
     * @param RequestModelInterface|null $requestModel
     * @param EndpointConfigurationInterface $endpointConfiguration
     *
     * @return RawClientInterface
     */
    public function prepare(array $parameters, ?RequestModelInterface $requestModel, EndpointConfigurationInterface $endpointConfiguration): RawClientInterface
    {
        if ($requestModel && EndpointConfigurationFactoryInterface::METHOD_POST === $endpointConfiguration->getMethod()) {
            $this->preparePost($parameters, $requestModel->getType(), $endpointConfiguration->getSignedRequest());
        } else {
            $this->prepareGet($parameters, $requestModel);
        }

        return $this;
    }

    /**
     * @param array $parameters
     * @param int $type
     * @param bool $signed
     *
     * @return RawClientInterface
     */
    protected function preparePost(array $parameters, int $type = RequestModelInterface::FORM, bool $signed = false): RawClientInterface
    {
        $this->parameters = $parameters;
        $this->method = EndpointConfigurationFactoryInterface::METHOD_POST;
        $this->requestType = $type;
        $this->signedRequest = $signed;

        return $this;
    }

    /**
     * @param array $parameters
     * @param RequestModelInterface|null $requestModel
     * @return RawClient
     */
    protected function prepareGet(array $parameters, ?RequestModelInterface $requestModel): RawClient
    {
        if ($requestModel == null) {
            $this->requestType = RequestModelInterface::FORM;
        } else {
            $this->requestType = $requestModel->getType();
        }
        $this->parameters = $parameters;
        $this->method = EndpointConfigurationFactoryInterface::METHOD_GET;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return RawClientInterface
     */
    public function addHeader(string $key, string $value): RawClientInterface
    {
        $this->headers[] = $key . ': ' . $value;

        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return RawClientInterface
     */
    public function setUser(string $username, string $password): RawClientInterface
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * @param bool $signedOutput
     *
     * @return RawOutputInterface
     * @throws JsonFunctionException
     * @throws IncompatibilityException
     * @throws PayloadException
     * @throws HttpException
     *
     * @throws ClientException
     */
    public function execute(bool $signedOutput = false): RawOutputInterface
    {
        if (empty($this->url)) {
            throw new RawClientException('URL is required for raw client.');
        }

        $curl = curl_init();

        $this->setDefaultUserAgent();
        $this->setMediaTypeHeaders();
        $this->configureClient($curl);

        $result = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        if (false === $result) {
            throw new RawClientException(curl_error($curl), curl_errno($curl));
        }

        if ($signedOutput && null !== json_decode_array($result)) {
            $signedOutput = false;
        }

        $this->failOnInvalidClient($result);

        if ($signedOutput) {
            $result = $this->securityManager->decodeResponse($result);
        } else {
            $result = json_decode_array($result);

            if (null === $result) {
                throw new RawClientException('Unable to get output from response.');
            }

            $result = new Payload($result);
        }

        $this->statusException(($result instanceof DecodedCollectionInterface ? $result->getPayload() : $result), $statusCode);

        return $result instanceof DecodedCollectionInterface ? new RawOutput($statusCode, $result->getPayload(), $result->getHeader(), $result->getSignature()) : new RawOutput($statusCode, $result);
    }

    /**
     * @return void
     */
    protected function setMediaTypeHeaders(): void
    {
        if (in_array($this->requestType, [RequestModelInterface::JSON_ARRAY, RequestModelInterface::JSON_OBJECT])) {
            if ($this->signedRequest) {
                $this->headers[] = 'Content-Type: application/jose+json';
            } else {
                $this->headers[] = 'Content-Type: application/json';
            }
        } else {
            $this->headers[] = 'Accept: application/json';
            $this->headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }
    }

    /**
     * @return void
     */
    private function setDefaultUserAgent(): void
    {
        if (self::containsHeader($this->headers, 'User-Agent')) {
            $this->headers[] = 'X-Http-User-Agent: ' . self::getDefaultUserAgent();
        } else {
            $this->headers[] = 'User-Agent: ' . self::getDefaultUserAgent();
        }
    }

    /**
     * @param $headers
     * @param $headerName
     * @return bool
     */
    private static function containsHeader($headers, $headerName): bool
    {
        foreach ($headers as $header) {
            if (strpos($header, $headerName) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    private static function getDefaultUserAgent(): string
    {
        static $defaultAgent = '';
        if (!$defaultAgent) {
            if (extension_loaded('curl')) {
                $defaultAgent .= 'curl/' . curl_version()['version'] . ' ';
            }
            $defaultAgent .= 'PHP/' . PHP_VERSION;
        }
        return $defaultAgent;
    }

    /***
     * @throws IncompatibilityException
     * @throws RawClientException
     * possible params resource|bool|CurlHandle $curl
     */
    protected function configureClient($curl): void
    {
        if ($curl === false) {
            throw new RawClientException('Unable to configure cURL for raw client.');
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 25);
        curl_setopt($curl, CURLOPT_TIMEOUT, 61);

        if (EndpointConfigurationFactoryInterface::METHOD_POST === $this->method) {
            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, (RequestModelInterface::FORM !== $this->requestType ? $this->jsonParameters() : $this->queryParameters()));
        } else {
            curl_setopt($curl, CURLOPT_URL, $this->url . '?' . $this->queryParameters());
        }

        if (count($this->headers) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        }

        if (null !== $this->username && null !== $this->password) {
            curl_setopt($curl, CURLOPT_USERPWD, $this->username . ':' . $this->password);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        $this->applyCustomConfiguration($curl);
    }

    /**
     * @return string
     * @throws IncompatibilityException
     *
     */
    protected function jsonParameters(): string
    {
        return $this->signedRequest ? $this->securityManager->encodeRequest($this->parameters) : json_encode_array($this->parameters, false, (RequestModelInterface::JSON_OBJECT === $this->requestType));
    }

    /**
     * @return string
     */
    protected function queryParameters(): string
    {
        $result = '';

        foreach ($this->parameters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $deepValue) {
                    $result .= $key . '=' . $deepValue . '&';
                }
            } else {
                $result .= $key . '=' . $value . '&';
            }
        }

        return trim($result, '&');
    }

    /**
     * @param resource $curl
     *
     * @return void
     */
    protected function applyCustomConfiguration($curl): void
    {
        foreach ($this->configuration->getCurlOptions() as $option => $value) {
            curl_setopt($curl, $option, $value);
        }
    }

    /**
     * @param string $response
     *
     * @return void
     * @throws JsonFunctionException
     * @throws InvalidClientException
     *
     * @throws IncompatibilityException
     */
    protected function failOnInvalidClient(string $response): void
    {
        $response = json_decode_array($response);

        if (null !== $response && isset($response['error']) && 'invalid_client' == $response['error']) {
            throw new InvalidClientException('API Client identifier is invalid');
        }
    }

    /**
     * @param PayloadInterface $payload
     * @param int $statusCode
     *
     * @throws PayloadException
     * @throws HttpException
     */
    protected function statusException(PayloadInterface $payload, int $statusCode): void
    {
        if ($this->isErrorOutput($statusCode)) {
            /** @var HttpException $exception */
            $exception = (new HttpExceptionFactory())->getExceptionForResponse($payload, $statusCode);

            throw $exception;
        }
    }

    /**
     * @param int $statusCode
     *
     * @return bool
     */
    protected function isErrorOutput(int $statusCode): bool
    {
        return static::RESPONSE_STATUS !== (int)substr((string)$statusCode, 0, 1);
    }
}
