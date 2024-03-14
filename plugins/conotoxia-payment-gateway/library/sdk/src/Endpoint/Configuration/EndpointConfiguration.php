<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint\Configuration;

/**
 * Class EndpointConfiguration.
 *
 * @package CKPL\Pay\Endpoint\Configuration
 */
class EndpointConfiguration implements EndpointConfigurationInterface
{
    /**
     * @var string|null
     */
    protected $host;

    /**
     * @var string|null
     */
    protected $method;

    /**
     * @var string|null
     */
    protected $url;

    /**
     * @var array|null
     */
    protected $headers;

    /**
     * @var array|null
     */
    protected $parameters;

    /**
     * @var bool|null
     */
    protected $json;

    /**
     * @var bool|null
     */
    protected $signedResponse;

    /**
     * @var bool|null
     */
    protected $signedRequest;

    /**
     * @var bool|null
     */
    protected $authorized;

    /**
     * @var bool|null
     */
    protected $credentials;

    /**
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return EndpointConfigurationInterface
     */
    public function setHost(string $host): EndpointConfigurationInterface
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return EndpointConfigurationInterface
     */
    public function setMethod(string $method): EndpointConfigurationInterface
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return EndpointConfigurationInterface
     */
    public function setUrl(string $url): EndpointConfigurationInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getJson(): ?bool
    {
        return $this->json;
    }

    /**
     * @param bool|null $json
     *
     * @return EndpointConfigurationInterface
     */
    public function setJson(bool $json): EndpointConfigurationInterface
    {
        $this->json = $json;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSignedRequest(): ?bool
    {
        return $this->signedRequest;
    }

    /**
     * @param bool $signedRequest
     *
     * @return EndpointConfigurationInterface
     */
    public function setSignedRequest(bool $signedRequest): EndpointConfigurationInterface
    {
        $this->signedRequest = $signedRequest;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSignedResponse(): ?bool
    {
        return $this->signedResponse;
    }

    /**
     * @param bool $signedResponse
     *
     * @return EndpointConfigurationInterface
     */
    public function setSignedResponse(bool $signedResponse): EndpointConfigurationInterface
    {
        $this->signedResponse = $signedResponse;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAuthorized(): ?bool
    {
        return $this->authorized;
    }

    /**
     * @param bool|null $authorized
     *
     * @return EndpointConfigurationInterface
     */
    public function setAuthorized(bool $authorized): EndpointConfigurationInterface
    {
        $this->authorized = $authorized;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getCredentials(): ?bool
    {
        return $this->credentials;
    }

    /**
     * @param bool|null $credentials
     *
     * @return EndpointConfigurationInterface
     */
    public function setCredentials(bool $credentials): EndpointConfigurationInterface
    {
        $this->credentials = $credentials;

        return $this;
    }
}
