<?php

/**
 * Copyright 2015 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace WPCal\GoogleAPI\Google\Auth\HttpHandler;

use Exception;
use WPCal\GoogleAPI\GuzzleHttp\ClientInterface;
use WPCal\GoogleAPI\GuzzleHttp\Message\ResponseInterface as Guzzle5ResponseInterface;
use WPCal\GoogleAPI\GuzzleHttp\Promise\Promise;
use WPCal\GoogleAPI\GuzzleHttp\Promise\RejectedPromise;
use WPCal\GoogleAPI\GuzzleHttp\Psr7\Response;
use WPCal\GoogleAPI\Psr\Http\Message\RequestInterface;
use WPCal\GoogleAPI\Psr\Http\Message\ResponseInterface;
class Guzzle5HttpHandler
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @param ClientInterface $client
     */
    public function __construct(\WPCal\GoogleAPI\GuzzleHttp\ClientInterface $client)
    {
        $this->client = $client;
    }
    /**
     * Accepts a PSR-7 Request and an array of options and returns a PSR-7 response.
     *
     * @param RequestInterface $request
     * @param array $options
     *
     * @return ResponseInterface
     */
    public function __invoke(\WPCal\GoogleAPI\Psr\Http\Message\RequestInterface $request, array $options = [])
    {
        $response = $this->client->send($this->createGuzzle5Request($request, $options));
        return $this->createPsr7Response($response);
    }
    /**
     * Accepts a PSR-7 request and an array of options and returns a PromiseInterface
     *
     * @param RequestInterface $request
     * @param array $options
     *
     * @return Promise
     */
    public function async(\WPCal\GoogleAPI\Psr\Http\Message\RequestInterface $request, array $options = [])
    {
        if (!\class_exists('WPCal\\GoogleAPI\\GuzzleHttp\\Promise\\Promise')) {
            throw new \Exception('Install guzzlehttp/promises to use async with Guzzle 5');
        }
        $futureResponse = $this->client->send($this->createGuzzle5Request($request, ['future' => \true] + $options));
        $promise = new \WPCal\GoogleAPI\GuzzleHttp\Promise\Promise(function () use($futureResponse) {
            try {
                $futureResponse->wait();
            } catch (\Exception $e) {
                // The promise is already delivered when the exception is
                // thrown, so don't rethrow it.
            }
        }, [$futureResponse, 'cancel']);
        $futureResponse->then([$promise, 'resolve'], [$promise, 'reject']);
        return $promise->then(function (\WPCal\GoogleAPI\GuzzleHttp\Message\ResponseInterface $response) {
            // Adapt the Guzzle 5 Response to a PSR-7 Response.
            return $this->createPsr7Response($response);
        }, function (\Exception $e) {
            return new \WPCal\GoogleAPI\GuzzleHttp\Promise\RejectedPromise($e);
        });
    }
    private function createGuzzle5Request(\WPCal\GoogleAPI\Psr\Http\Message\RequestInterface $request, array $options)
    {
        return $this->client->createRequest($request->getMethod(), $request->getUri(), \array_merge_recursive(['headers' => $request->getHeaders(), 'body' => $request->getBody()], $options));
    }
    private function createPsr7Response(\WPCal\GoogleAPI\GuzzleHttp\Message\ResponseInterface $response)
    {
        return new \WPCal\GoogleAPI\GuzzleHttp\Psr7\Response($response->getStatusCode(), $response->getHeaders() ?: [], $response->getBody(), $response->getProtocolVersion(), $response->getReasonPhrase());
    }
}
