<?php

namespace Baqend\SDK\Resource;

use Baqend\SDK\Client\RestClientInterface;
use Baqend\SDK\Serializer\FileNormalizer;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class AbstractRestResource created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Resource
 */
abstract class AbstractRestResource extends AbstractResource implements ResourceInterface
{

    /**
     * @var RestClientInterface
     */
    protected $client;

    /**
     * @return RestClientInterface
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * Returns whether this resource's client is authorized.
     *
     * @return bool True, if client is authorized.
     */
    protected function isAuthorized() {
        return $this->client->getAuthorizationToken() !== null;
    }

    /**
     * Creates a JSON-containing request to send.
     *
     * @param string $method The HTTP method to use.
     * @param string $path The path to send the request to.
     * @param mixed $requestData The data to send with the request or null, if none should be sent.
     * @param array $context Some serialization context.
     * @return RequestInterface  The request created.
     */
    protected function sendJson($method, $path, $requestData = null, array $context = []) {
        try {
            return $this->client->createRequest()
                ->withMethod($method)
                ->withPath($path)
                ->withJsonBody($requestData, $context)
                ->build();
        } catch (UnexpectedValueException $exception) {
            throw $this->createRuntimeError('Cannot not serialize request data as JSON', $exception);
        }
    }

    /**
     * @param string $method The HTTP method to use.
     * @param string $path The path to send the request to.
     * @param array|\JsonSerializable $query The data to send as request query with or empty, if none should be sent.
     * @return RequestInterface
     */
    protected function sendQuery($method, $path, $query = []) {
        return $this->client->createRequest()
            ->withMethod($method)
            ->withPath($path)
            ->withQuery($query)
            ->build();
    }

    /**
     * @param string $method          The HTTP method to use.
     * @param string $path            The path to send the request to.
     * @param StreamInterface $stream The data to send as request body.
     * @return RequestInterface
     */
    protected function sendStream($method, $path, StreamInterface $stream) {
        return $this->client->createRequest()
            ->withMethod($method)
            ->withPath($path)
            ->withStreamBody($stream)
            ->build();
    }

    /**
     * @param string $method          The HTTP method to use.
     * @param string $path            The path to send the request to.
     * @param string $string          The data to send as request body.
     * @return RequestInterface
     */
    protected function sendString($method, $path, $string) {
        return $this->client->createRequest()
            ->withMethod($method)
            ->withPath($path)
            ->withStringBody($string)
            ->build();
    }

    /**
     * @param ResponseInterface $response The response to receive JSON content of.
     * @param
     *
     * @return mixed The received data from the JSON.
     */
    protected function receiveJson(ResponseInterface $response, $class = null) {
        try {
            $json = $response->getBody()->getContents();
            $context = [FileNormalizer::ENDPOINT => $this->getClient()->getEndpoint()->__toString()];

            if ($class === null) {
                return $this->serializer->decode($json, 'json', $context);
            }

            if (!is_string($class)) {
                $object = $class;
                $class = get_class($object);

                $context[ObjectNormalizer::OBJECT_TO_POPULATE] = $object;

                return $this->serializer->deserialize($json, $class, 'json', $context);
            }

            return $this->serializer->deserialize($json, $class, 'json', $context);
        } catch (UnexpectedValueException $exception) {
            $message = 'Cannot unserialize JSON, "'.get_class($exception).'" thrown: '.$exception->getMessage();
            throw $this->createRuntimeError($message, $exception);
        }
    }
}
