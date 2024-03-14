<?php

namespace Baqend\SDK\Client;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class RequestBuilder created on 09.08.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Client
 */
class RequestBuilder
{

    const JSON_TYPE = 'application/json';

    /**
     * @var string
     */
    private $method;

    /**
     * @var Uri
     */
    private $uri;

    /**
     * @var string[][]
     */
    private $headers;

    /**
     * @var string|null|StreamInterface
     */
    private $body;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * RequestBuilder constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer) {
        $this->method = 'GET';
        $this->uri = new Uri();
        $this->headers = [];
        $this->body = null;
        $this->basePath = '';
        $this->serializer = $serializer;
    }

    /**
     * Changes the request method.
     *
     * @param string $method The new request method.
     * @return static This is a chained method.
     */
    public function withMethod($method) {
        $this->method = $method;

        return $this;
    }

    /**
     * Builds the request.
     *
     * @return RequestInterface The built request.
     */
    public function build() {
        $uri = $this->uri->withPath($this->basePath.$this->uri->getPath());

        return new Request($this->method, $uri, $this->headers, $this->body);
    }

    /**
     * Changes the request method to GET.
     *
     * @return static This is a chained method.
     */
    public function asGet() {
        return $this->withMethod('GET');
    }

    /**
     * Changes the request method to POST.
     *
     * @return static This is a chained method.
     */
    public function asPost() {
        return $this->withMethod('POST');
    }

    /**
     * Changes the request method to PUT.
     *
     * @return static This is a chained method.
     */
    public function asPut() {
        return $this->withMethod('PUT');
    }

    /**
     * Changes the request method to DELETE.
     *
     * @return static This is a chained method.
     */
    public function asDelete() {
        return $this->withMethod('DELETE');
    }

    /**
     * Changes the request method to HEAD.
     *
     * @return static This is a chained method.
     */
    public function asHead() {
        return $this->withMethod('HEAD');
    }

    /**
     * Sets an HTTP request header.
     *
     * @param string $name The name of the header to set.
     * @param string|null $value The header's value or null, if the header should not be set.
     * @return static This is a chained method.
     */
    public function withHeader($name, $value) {
        $name = strtolower($name);
        if ($value === null) {
            if (isset($this->headers[$name])) {
                unset($this->headers[$name]);
            }
        } else {
            $this->headers[$name] = $value;
        }

        return $this;
    }

    /**
     * Sets an authorization for the built request.
     *
     * @param string|null $authorization The authorization to set or null, if it should be cleared.
     * @return static This is a chained method.
     */
    public function withAuthorization($authorization) {
        return $this->withHeader('authorization', $authorization);
    }

    /**
     * Sets a content type for the built request.
     *
     * @param string|null $contentType The content type to set or null, if it should be cleared.
     * @return static This is a chained method.
     */
    public function withContentType($contentType) {
        return $this->withHeader('content-type', $contentType);
    }

    /**
     * Sets an entity tag which should be matched.
     *
     * @param string|null $eTag The entity tag which should be matched or null, if it should be cleared.
     * @param bool $weak Whether to use the weak comparison algorithm.
     * @return static This is a chained method.
     */
    public function withIfMatch($eTag, $weak = false) {
        return $this->withHeader('if-match', $this->wrapEntityTag($eTag, $weak));
    }

    /**
     * Sets an entity tag which should not be matched.
     *
     * @param string|null $eTag The entity tag which should not be matched or null, if it should be cleared.
     * @param bool $weak Whether to use the weak comparison algorithm.
     * @return static This is a chained method.
     */
    public function withIfNoneMatch($eTag, $weak = false) {
        return $this->withHeader('if-none-match', $this->wrapEntityTag($eTag, $weak));
    }

    /**
     * Appends a query to the request to build.
     *
     * @param array|\JsonSerializable $query The query to send.
     * @return static This is a chained method.
     */
    public function withQuery($query = []) {
        $queryString = $this->serializer->serialize($query, 'query');

        $this->uri = $this->uri->withQuery($queryString);

        return $this;
    }

    /**
     * @param string $basePath
     * @return static This is a chained method.
     */
    public function withBasePath($basePath) {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * @param string $path
     * @return static This is a chained method.
     */
    public function withPath($path) {
        $this->uri = $this->uri->withPath($path);

        return $this;
    }

    /**
     * @param string $scheme
     * @return static This is a chained method.
     */
    public function withScheme($scheme) {
        $this->uri = $this->uri->withScheme($scheme);

        return $this;
    }

    /**
     * @param string $hostname The host's name.
     * @param int|null $port The port.
     * @return static This is a chained method.
     */
    public function withHost($hostname, $port = null) {
        $this->uri = $this->uri->withHost($hostname);
        if ($port !== null) {
            $this->uri = $this->uri->withPort($port);
        }

        return $this;
    }

    /**
     * Sends JSON-encoded data in the body.
     *
     * @param mixed $requestData The data to send as JSON within the body.
     * @param array $context     Some serialization context.
     * @return static            This is a chained method.
     */
    public function withJsonBody($requestData = null, array $context = []) {
        if ($requestData !== null) {
            $this->body = $this->serializer->serialize($requestData, 'json', $context);

            return $this->withContentType(self::JSON_TYPE);
        }

        return $this;
    }

    /**
     * Sends a data stream in the body.
     *
     * @param StreamInterface $stream The stream to send as body data.
     * @return static This is a chained method.
     */
    public function withStreamBody(StreamInterface $stream) {
        $this->body = $stream;

        return $this;
    }

    /**
     * Sends a data string in the body.
     *
     * @param string $string The string to send as body data.
     * @return static This is a chained method.
     */
    public function withStringBody($string) {
        $this->body = $string;

        return $this;
    }

    /**
     * Wraps a given string with entity tag quotes if necessary.
     *
     * @param string|null $eTag The string to wrap. Null will be passed through.
     * @param bool $weak Whether to use weak matching.
     * @return string|null The string wrapped with double quotes or null, if given.
     */
    private function wrapEntityTag($eTag, $weak = false) {
        if ($eTag === null) {
            return null;
        }

        if (preg_match('/^(W\/)?"[^"]*"$/i', $eTag) === 1) {
            return $eTag;
        }

        $weak = $weak ? 'W/' : '';

        return "$weak\"$eTag\"";
    }
}
