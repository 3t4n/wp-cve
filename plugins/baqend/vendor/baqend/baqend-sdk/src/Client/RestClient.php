<?php

namespace Baqend\SDK\Client;

use Baqend\SDK\Exception\InvalidAuthorizationException;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class RestClient created on 25.07.2017.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Client
 */
class RestClient implements RestClientInterface
{

    /**
     * @var string
     */
    private $app;

    /**
     * @var string
     */
    private $authorizationToken;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ApiEndpoint
     */
    private $endpoint;

    /**
     * @var callable|null
     */
    private $handler;

    /**
     * RestClient constructor.
     *
     * @param string|array $app The API endpoint specification to connect to.
     * @param SerializerInterface $serializer A serializer to work with.
     * @param ClientInterface $client A client to work with.
     */
    public function __construct($app, SerializerInterface $serializer, ClientInterface $client) {
        $this->app = $app;
        $this->serializer = $serializer;
        $this->client = $client;

        // Create the API endpoint
        $this->endpoint = new ApiEndpoint($app);
    }

    /**
     * @inheritDoc
     */
    public function getTransport() {
        return $this->client->getTransport();
    }

    /**
     * @inheritdoc
     */
    public function getApp() {
        return $this->app;
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint() {
        return $this->endpoint;
    }

    /**
     * @inheritdoc
     */
    public function getSerializer() {
        return $this->serializer;
    }

    /**
     * @inheritdoc
     */
    public function setSerializer(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }

    /**
     * @inheritdoc
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * @inheritdoc
     */
    public function setClient(ClientInterface $client) {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function isAuthorized() {
        return $this->authorizationToken !== null;
    }

    /**
     * @inheritdoc
     */
    public function getAuthorizationToken() {
        return $this->authorizationToken;
    }

    /**
     * @inheritdoc
     */
    public function setAuthorizationToken($authorizationToken) {
        if (substr($authorizationToken, 0, 4) === 'BAT ') {
            $authorizationToken = substr($authorizationToken, 4);
        }

        $this->authorizationToken = $authorizationToken;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setInvalidTokenHandler(callable $handler) {
        $this->handler = $handler;
    }

    /**
     * @inheritdoc
     */
    public function hasInvalidTokenHandler() {
        return is_callable($this->handler);
    }

    /**
     * @inheritdoc
     */
    public function createRequest() {
        $builder = new RequestBuilder($this->serializer);

        if ($this->isAuthorized()) {
            $builder = $builder->withAuthorization('BAT '.$this->getAuthorizationToken());
        }

        return $builder
            ->withScheme($this->endpoint->getScheme())
            ->withHost($this->endpoint->getHostname(), $this->endpoint->getPort())
            ->withBasePath($this->endpoint->getBasePath());
    }

    /**
     * @inheritdoc
     */
    public function sendSyncRequest(RequestInterface $request) {
        $response = $this->client->sendSyncRequest($request);

        // Handle invalid authorization token
        if ($response->getStatusCode() === 460) {
            // Remove invalid authorization token
            $this->setAuthorizationToken(null);
            if (!$this->hasInvalidTokenHandler()) {
                throw new InvalidAuthorizationException($response);
            }

            $newToken = call_user_func($this->handler, $this);
            if ($newToken) {
                $this->setAuthorizationToken($newToken);
            }

            // If now is authored, retry
            if ($this->isAuthorized()) {
                $request->getBody()->rewind();
                $fixedRequest = $request->withHeader('authorization', 'BAT '.$this->getAuthorizationToken());
                $response = $this->client->sendSyncRequest($fixedRequest);
                if ($response->getStatusCode() !== 460) {
                    return $response;
                }
            }

            throw new InvalidAuthorizationException($response);
        }

        // Update the authorization token
        if ($response->hasHeader(self::BAT_HEADER)) {
            $this->setAuthorizationToken($response->getHeaderLine(self::BAT_HEADER));
        }

        return $response;
    }

    /**
     * @inheritdoc
     */
    public function sendAsyncRequest(RequestInterface $request) {
        $this->client->sendAsyncRequest($request);
    }
}
