<?php

namespace Baqend\SDK\Resource;

use Baqend\SDK\Client\ClientInterface;
use Baqend\SDK\Exception\InvalidAuthorizationException;
use Baqend\SDK\Exception\RequestException;
use Baqend\SDK\Exception\ResponseException;

use Symfony\Component\Serializer\Serializer;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractResource created on 15.12.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Resource
 */
abstract class AbstractResource
{

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * AbstractResource constructor.
     *
     * @param ClientInterface $client
     * @param Serializer $serializer
     */
    public function __construct(ClientInterface $client, Serializer $serializer) {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @return Serializer
     */
    public function getSerializer() {
        return $this->serializer;
    }

    /**
     * Executes a request by sending it to the server and receive the response.
     *
     * @param RequestInterface $request The request to send to the server.
     * @return ResponseInterface The response received from the server.
     * @throws ResponseException When the authorization is invalid.
     */
    protected function execute(RequestInterface $request) {
        try {
            return $this->client->sendSyncRequest($request);
        } catch (RequestException $e) {
            throw $this->createRuntimeError('Cannot send request because of '.get_class($e).': '.$e->getMessage(), $e);
        } catch (InvalidAuthorizationException $e) {
            throw new ResponseException($e->getResponse());
        }
    }

    /**
     * Creates a runtime error which handles programming errors.
     *
     * @param string $message The message to give to the runtime exception.
     * @param \Exception $previous The original exception to handle.
     * @return \RuntimeException The runtime exception to use along.
     */
    protected function createRuntimeError($message, \Exception $previous) {
        return new \RuntimeException($message, $previous->getCode(), $previous);
    }
}
