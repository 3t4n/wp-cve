<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint\ConfigurationFactory;

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Endpoint\Configuration\EndpointConfiguration;
use CKPL\Pay\Endpoint\Configuration\EndpointConfigurationInterface;
use CKPL\Pay\Exception\EndpointConfigurationException;

/**
 * Class EndpointConfigurationFactory.
 *
 * @package CKPL\Pay\Endpoint\ConfigurationFactory
 */
class EndpointConfigurationFactory implements EndpointConfigurationFactoryInterface
{
    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var bool
     */
    protected $json;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var bool
     */
    protected $signedRequest;

    /**
     * @var bool
     */
    protected $signedResponse;

    /**
     * @var bool
     */
    protected $authorized;

    /**
     * @var bool
     */
    protected $credentials;

    /**
     * EndpointConfigurationFactory constructor.
     *
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;

        $this->url = '';
        $this->host = $this->configuration->getHost();
        $this->method = static::METHOD_GET;
        $this->json = false;
        $this->headers = [];
        $this->parameters = [];
        $this->signedRequest = false;
        $this->signedResponse = false;
        $this->authorized = false;
        $this->credentials = false;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function toOidc(): EndpointConfigurationFactoryInterface
    {
        $this->host = $this->configuration->getOidc();

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function toPayments(): EndpointConfigurationFactoryInterface
    {
        $this->host = $this->configuration->getHost();

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function asGet(): EndpointConfigurationFactoryInterface
    {
        $this->method = static::METHOD_GET;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function asPost(): EndpointConfigurationFactoryInterface
    {
        $this->method = static::METHOD_POST;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return EndpointConfigurationFactory
     */
    public function url(string $url): EndpointConfigurationFactory
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function encodeWithJson(): EndpointConfigurationFactoryInterface
    {
        $this->json = true;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function disableJsonEncoding(): EndpointConfigurationFactoryInterface
    {
        $this->json = false;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function expectSignedResponse(): EndpointConfigurationFactoryInterface
    {
        $this->signedResponse = true;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function expectPlainResponse(): EndpointConfigurationFactoryInterface
    {
        $this->signedResponse = false;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function signRequest(): EndpointConfigurationFactoryInterface
    {
        $this->signedRequest = true;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function plainRequest(): EndpointConfigurationFactoryInterface
    {
        $this->signedRequest = false;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function authorized(): EndpointConfigurationFactoryInterface
    {
        $this->authorized = true;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function notAuthorized(): EndpointConfigurationFactoryInterface
    {
        $this->authorized = false;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function withCredentials(): EndpointConfigurationFactoryInterface
    {
        $this->credentials = true;

        return $this;
    }

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function withoutCredentials(): EndpointConfigurationFactoryInterface
    {
        $this->credentials = false;

        return $this;
    }

    /**
     * @throws EndpointConfigurationException
     *
     * @return EndpointConfigurationInterface
     */
    public function build(): EndpointConfigurationInterface
    {
        if (empty($this->url)) {
            throw new EndpointConfigurationException('Missing URL in endpoint configuration.');
        }

        $configuration = new EndpointConfiguration();

        $configuration->setUrl($this->url);
        $configuration->setHost($this->host);
        $configuration->setMethod($this->method);
        $configuration->setJson($this->json);
        $configuration->setSignedRequest($this->signedRequest);
        $configuration->setSignedResponse($this->signedResponse);
        $configuration->setAuthorized($this->authorized);
        $configuration->setCredentials($this->credentials);

        return $configuration;
    }
}
