<?php

declare(strict_types=1);

namespace CKPL\Pay\Client;

use CKPL\Pay\Authentication\AuthenticationManagerInterface;
use CKPL\Pay\Client\RawClient\RawClient;
use CKPL\Pay\Client\RawClient\RawClientInterface;
use CKPL\Pay\Client\Request\Request;
use CKPL\Pay\Client\Request\RequestInterface;
use CKPL\Pay\Client\Response\ResponseInterface;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Endpoint\EndpointInterface;
use CKPL\Pay\Security\SecurityManagerInterface;

/**
 * Class Client.
 *
 * @package CKPL\Pay\Client
 */
class Client implements ClientInterface
{
    /**
     * @var EndpointInterface
     */
    protected $endpoint;

    /**
     * @var ResponseInterface|null
     */
    protected $response;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var SecurityManagerInterface
     */
    protected $securityManager;

    /**
     * @var AuthenticationManagerInterface|null
     */
    protected $authenticationManager;

    /**
     * Client constructor.
     *
     * @param EndpointInterface              $endpoint
     * @param ConfigurationInterface         $configuration
     * @param SecurityManagerInterface       $securityManager
     * @param AuthenticationManagerInterface $authenticationManager
     */
    public function __construct(
        EndpointInterface $endpoint,
        ConfigurationInterface $configuration,
        SecurityManagerInterface $securityManager,
        AuthenticationManagerInterface $authenticationManager = null
    ) {
        $this->endpoint = $endpoint;
        $this->configuration = $configuration;
        $this->securityManager = $securityManager;
        $this->authenticationManager = $authenticationManager;
    }

    /**
     * @return RequestInterface
     */
    public function request(): RequestInterface
    {
        return new Request($this, $this->configuration, $this->securityManager, $this->authenticationManager);
    }

    /**
     * @return RawClientInterface
     */
    public function raw(): RawClientInterface
    {
        return new RawClient($this->securityManager, $this->configuration);
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface
    {
        return $this->endpoint;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface|null $response
     *
     * @return ClientInterface
     */
    public function setResponse(ResponseInterface $response): ClientInterface
    {
        $this->response = $response;

        return $this;
    }
}
