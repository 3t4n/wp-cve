<?php

namespace Baqend\SDK\Client;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface RestClientInterface created on 24.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Client
 */
interface RestClientInterface extends ClientInterface
{

    const BAT_HEADER = 'baqend-authorization-token';

    /**
     * The client with a given app's Baqend.
     *
     * @return string
     */
    public function getApp();

    /**
     * The endpoint pointing to the given Baqend app.
     *
     * @return ApiEndpoint
     */
    public function getEndpoint();

    /**
     * Sets the authorization token which should be used when executing requests.
     *
     * @param string $token
     * @return $this
     */
    public function setAuthorizationToken($token);

    /**
     * Sets the handler which will be called if the authorization token used is invalid.
     *
     * @param callable $handler
     * @return $this
     */
    public function setInvalidTokenHandler(callable $handler);

    /**
     * Determines whether this client is authorized.
     *
     * @return bool
     */
    public function isAuthorized();

    /**
     * Gets the authorization token used by this client.
     *
     * @return string
     */
    public function getAuthorizationToken();

    /**
     * Returns whether a handler is set which will be called if the authorization token used is invalid.
     *
     * @return boolean
     */
    public function hasInvalidTokenHandler();

    /**
     * Creates a request builder to the connected app.
     *
     * @return RequestBuilder
     */
    public function createRequest();

    /**
     * Returns the serializer to use.
     *
     * @return SerializerInterface
     */
    public function getSerializer();

    /**
     * Changes the serializer to use.
     *
     * @param SerializerInterface $serializer The serializer to use.
     */
    public function setSerializer(SerializerInterface $serializer);

    /**
     * Returns the client to use.
     *
     * @return ClientInterface
     */
    public function getClient();

    /**
     * Changes the client to use.
     *
     * @param ClientInterface $client The client to use.
     */
    public function setClient(ClientInterface $client);
}
