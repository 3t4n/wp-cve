<?php

namespace Baqend\SDK\Client;

use Baqend\SDK\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend_Http_Client as HttpClient;

/**
 * Class ZendFramework1Client created on 14.12.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Client
 */
class ZendFramework1Client implements ClientInterface
{

    /**
     * Returns the transport this client represents.
     *
     * @return string The transport this client represents.
     */
    public function getTransport() {
        return self::ZEND_FRAMEWORK1_TRANSPORT;
    }

    /**
     * @inheritdoc
     */
    public function sendSyncRequest(RequestInterface $request) {
        try {
            $client = $this->createHttpClient($request);
            $response = $client->request();

            return $this->createResponse($response);
        } catch (\Zend_Http_Client_Exception $exception) {
            throw new RequestException($request, $exception->getMessage(), $exception);
        }
    }

    /**
     * @inheritdoc
     */
    public function sendAsyncRequest(RequestInterface $request) {
        throw new \LogicException('Zend Framework 1 does not support async requests');
    }

    /**
     * @param RequestInterface $request The request to create a ZF1 HTTP client for.
     * @return HttpClient A ZF1 HTTP client.
     * @throws RequestException When Zend Framework 1 throws an error.
     */
    private function createHttpClient(RequestInterface $request) {
        try {
            $client = new HttpClient($request->getUri()->__toString(), [
                'keepalive' => true,
                'timeout' => self::TIMEOUT,
                'maxredirects' => self::MAX_REDIRECTS,
            ]);

            $client->setMethod($request->getMethod());
            $client->setHeaders(array_map(function ($line) {
                return implode(',', $line);
            }, $request->getHeaders()));
            $client->setRawData($request->getBody()->getContents());

            return $client;
        } catch (\Zend_Exception $e) {
            throw new RequestException($request, 'Zend Framework 1 threw an error', $e);
        }
    }

    /**
     * @param \Zend_Http_Response $response
     * @return ResponseInterface
     */
    private function createResponse(\Zend_Http_Response $response) {
        return new Response(
            $response->getStatus(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getVersion(),
            $response->getMessage()
        );
    }
}
