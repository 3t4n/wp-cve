<?php

namespace Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Facades;

use Servebolt\Optimizer\Dependencies\GuzzleHttp\Exception\ClientException;
use Servebolt\Optimizer\Dependencies\GuzzleHttp\Client;
use Servebolt\Optimizer\Dependencies\GuzzleHttp\Psr7\Request;
use Servebolt\Optimizer\Dependencies\GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\MockInterface;
use Servebolt\Optimizer\Dependencies\Psr\Http\Message\RequestInterface;
use Servebolt\Optimizer\Dependencies\Psr\Http\Message\ResponseInterface;
use Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Exceptions\ServeboltHttpClientException;

class Http
{
    private static $service;
    private $client;
    private $mock;
    private static $shouldVerifySsl = true;
    private static $shouldThrowClientExceptions = false;

    private function isMocked() : bool
    {
        return isset($this->mock);
    }

    public static function shouldVerifySsl($bool = null)
    {
        if (is_bool($bool)) {
            self::$shouldVerifySsl = $bool;
            return;
        }
        return self::$shouldVerifySsl;
    }

    public static function shouldThrowClientExceptions() : bool
    {
        return self::$shouldThrowClientExceptions;
    }

    private function mock() : MockInterface
    {
        if (!isset($this->mock)) {
            $this->mock = Mockery::mock('Http');
        }
        return $this->mock;
    }

    private function client() : Client
    {
        if (!isset($this->client)) {
            $clientArguments = [
                'http_errors' => true,
            ];
            if (!$this->shouldVerifySsl()) {
                $clientArguments['verify'] = false;
            }
            $this->client = new Client($clientArguments);
        }
        return $this->client;
    }

    private function buildRequestOptions($requestOptions = [], $headers = [], $body = null) : array
    {
        $requestOptions['headers'] = $headers;
        if ($body) {
            $requestOptions['body'] = $body;
        }
        return $requestOptions;
    }

    public function request(string $method, string $uri, array $headers = [], $body = null) : ResponseInterface
    {
        if ($this->isMocked()) {
            return $this->mock()->request($method, $uri, $headers);
        }
        try {
            $response = $this->client()->request($method, $uri, $this->buildRequestOptions([], $headers, $body));
            return $this->buildResponseObject($response);
        } catch (ClientException $e) {
            if (self::$shouldThrowClientExceptions) {
                throw new ServeboltHttpClientException(
                    $e->getMessage(),
                    $e->getRequest(),
                    $e->getResponse(),
                    $e,
                    $e->getHandlerContext()
                ); // Throw our own exceptions for 4xx-errors
            } else {
                return $this->buildResponseObject($e->getResponse());
            }
        }
    }

    private function buildResponseObject($response) : Response
    {
        return new Response(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }

    public static function disableClientExceptions() : void
    {
        $facade = self::facade();
        $facade::$shouldThrowClientExceptions = false;
    }

    public static function enableClientExceptions() : void
    {
        $facade = self::facade();
        $facade::$shouldThrowClientExceptions = true;
    }

    private static function facade() : Http
    {
        if (!isset(self::$service)) {
            self::$service = new Http();
        }
        return self::$service;
    }

    /**
     * @return Mockery\Expectation|Mockery\ExpectationInterface|Mockery\HigherOrderMessage
     */
    public static function shouldReceive()
    {
        return self::facade()->mock()->shouldReceive(...func_get_args());
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public static function send(RequestInterface $request) : ResponseInterface
    {
        $method = $request->getMethod();
        $uri = $request->getUri();
        $headers = $request->getHeaders();
        $body = $request->getBody();
        return self::facade()->request($method, $uri, $headers, $body);
    }

    /**
     * @param string|UriInterface                   $uri     URI
     * @param array                                 $headers Request headers
     * @return Response
     */
    public static function get(string $uri, array $headers = []) : Response
    {
        return self::send(new Request('GET', $uri, $headers));
    }

    /**
     * @param string|UriInterface                   $uri     URI
     * @param array                                 $headers Request headers
     * @return Response
     */
    public static function delete(string $uri, array $headers = []) : Response
    {
        return self::send(new Request('DELETE', $uri, $headers));
    }

    /**
     * @param string|UriInterface                   $uri     URI
     * @param string|null                           $body    Request body
     * @param array                                 $headers Request headers
     * @return Response
     */
    public static function put(string $uri, $body = null, array $headers = []) : Response
    {
        return self::send(new Request('PUT', $uri, $headers, $body));
    }

    /**
     * @param string|UriInterface                   $uri     URI
     * @param string|null                           $body    Request body
     * @param array                                 $headers Request headers
     * @return Response
     */
    public static function patch(string $uri, $body = null, array $headers = []) : Response
    {
        return self::send(new Request('PATCH', $uri, $headers, $body));
    }

    /**
     * @param string|UriInterface                   $uri     URI
     * @param string|null                           $body    Request body
     * @param array                                 $headers Request headers
     * @return Response
     */
    public static function post(string $uri, $body = null, array $headers = []) : Response
    {
        return self::send(new Request('POST', $uri, $headers, $body));
    }
}
