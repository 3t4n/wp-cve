<?php

namespace Baqend\SDK\Client;

use Baqend\SDK\Exception\InvalidAuthorizationException;
use Baqend\SDK\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface ClientInterface created on 09.08.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Client
 */
interface ClientInterface
{

    const GUZZLE_TRANSPORT = 'guzzle';
    const WORD_PRESS_TRANSPORT = 'word-press';
    const ZEND_FRAMEWORK1_TRANSPORT = 'zend-framework1';
    const TIMEOUT = 30;
    const MAX_REDIRECTS = 0;

    /**
     * Returns the transport this client represents.
     *
     * @return string The transport this client represents.
     */
    public function getTransport();

    /**
     * Sends a synchronous request to the connected app.
     *
     * @param RequestInterface $request The request to send.
     * @return ResponseInterface The response received from the Baqend.
     * @throws RequestException When the request could not be sent or the server responded illegally.
     * @throws InvalidAuthorizationException When the authorization token is not valid anymore.
     */
    public function sendSyncRequest(RequestInterface $request);

    /**
     * Sends an asynchronous request to the connected app.
     *
     * @param RequestInterface $request The request to send.
     * @return void
     */
    public function sendAsyncRequest(RequestInterface $request);
}
