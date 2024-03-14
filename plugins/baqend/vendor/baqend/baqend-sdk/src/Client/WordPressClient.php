<?php

namespace Baqend\SDK\Client;

use Baqend\SDK\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use WP_HTTP_Requests_Response as WordPressResponse;

/**
 * Class WordPressClient created on 24.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Client
 */
class WordPressClient implements ClientInterface
{

    /**
     * @var \WP_Http
     */
    private $http;

    /**
     * WordPressClient constructor.
     *
     * @param \WP_Http|null $http
     */
    public function __construct(\WP_Http $http = null) {
        if (null === $http) {
            $http = \_wp_http_get_object();
        }
        $this->http = $http;
    }

    /**
     * Returns the transport this client represents.
     *
     * @return string The transport this client represents.
     */
    public function getTransport() {
        return self::WORD_PRESS_TRANSPORT;
    }

    /**
     * @inheritdoc
     */
    public function sendSyncRequest(RequestInterface $request) {
        $args = $this->createRequestArgs($request, true);

        try {
            $responseOrError = $this->http->request($request->getUri()->__toString(), $args);
        } catch (\Error $error) {
            throw new RequestException($request, $error->getMessage());
        }

        if (is_wp_error($responseOrError)) {
            throw new RequestException($request, $responseOrError->get_error_message());
        }

        $response = $responseOrError['http_response'];

        return $this->createResponse($response);
    }

    /**
     * @inheritdoc
     */
    public function sendAsyncRequest(RequestInterface $request) {
        $args = $this->createRequestArgs($request, false);

        $this->http->request($request->getUri()->__toString(), $args);
    }

    /**
     * @param RequestInterface $request
     * @param boolean $blocking
     * @return array
     */
    private function createRequestArgs(RequestInterface $request, $blocking) {
        $headers = array_map(
            function ($values) {
                return implode(', ', $values);
            },
            $request->getHeaders()
        );
        $args = [
            'method' => $request->getMethod(),
            'headers' => $headers,
            'body' => $request->getBody()->getContents(),
            'blocking' => $blocking,
            'timeout' => self::TIMEOUT,
            'redirection' => self::MAX_REDIRECTS,
        ];

        return $args;
    }

    /**
     * @param WordPressResponse $source
     * @return Response
     */
    private function createResponse(WordPressResponse $source) {
        return new Response($source->get_status(), $source->get_headers()->getAll(), $source->get_data());
    }
}
