<?php

declare(strict_types=1);

namespace CKPL\Pay\Client\Request;

use CKPL\Pay\Authentication\AuthenticationManagerInterface;
use CKPL\Pay\Client\ClientInterface;
use CKPL\Pay\Client\Response\Response;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactory;
use CKPL\Pay\Exception\ClientException;
use CKPL\Pay\Exception\EndpointConfigurationException;
use CKPL\Pay\Exception\RequestException;
use CKPL\Pay\Model\RequestModelInterface;
use CKPL\Pay\Security\SecurityManagerInterface;

/**
 * Class Request.
 *
 * @package CKPL\Pay\Client\Request
 */
class Request implements RequestInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $headers;

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
     * Request constructor.
     *
     * @param ClientInterface                     $client
     * @param ConfigurationInterface              $configuration
     * @param SecurityManagerInterface            $securityManager
     * @param AuthenticationManagerInterface|null $authenticationManager
     */
    public function __construct(
        ClientInterface $client,
        ConfigurationInterface $configuration,
        SecurityManagerInterface $securityManager,
        AuthenticationManagerInterface $authenticationManager = null
    ) {
        $this->client = $client;
        $this->configuration = $configuration;
        $this->securityManager = $securityManager;
        $this->authenticationManager = $authenticationManager;

        $this->headers = [];
        $this->parameters = [];
    }

    /**
     * @param array $parameters
     *
     * @return RequestInterface
     */
    public function parameters(array $parameters): RequestInterface
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return RequestInterface
     */
    public function headers(array $headers): RequestInterface
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @throws ClientException
     * @throws EndpointConfigurationException
     *
     * @return void
     */
    public function send(): void
    {
        $endpoint = $this->client->getEndpoint();
        $rawClient = $this->client->raw();

        $configurationFactory = new EndpointConfigurationFactory($this->configuration);
        $endpoint->configuration($configurationFactory);

        $configuration = $configurationFactory->build();
        $rawClient->setUrl($configuration->getHost().$configuration->getUrl());

        /** @var RequestModelInterface $requestModel */
        $requestModel = $endpoint->processRawInput($this->parameters);

        $parameters = $requestModel ? $requestModel->raw() : [];
        $rawClient->prepare($parameters, $requestModel, $configuration);

        foreach ($this->headers as $header) {
            list($key, $value) = $header;

            $rawClient->addHeader($key, $value);
        }

        if ($configuration->getAuthorized()) {
            if (null === $this->authenticationManager) {
                throw new RequestException('Authentication manager is required for authorization process.');
            }

            $token = !$this->authenticationManager->isAuthenticated()
                ? $this->authenticationManager->authenticate(true)
                : $this->securityManager->getToken();

            $rawClient->addHeader('Authorization', $token->getType().' '.$token->getToken());
        }

        if ($configuration->getCredentials()) {
            $rawClient->setUser($this->configuration->getClientId(), $this->configuration->getClientSecret());
        }

        $this->client->setResponse(new Response(
            $requestModel,
            $endpoint->processRawOutput($rawClient->execute($configuration->getSignedResponse()))
        ));
    }
}
