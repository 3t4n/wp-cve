<?php
/**
 * Copyright 2015 Goracash
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Goracash\Http;

use Goracash\Utils as Utils;

/**
 * HTTP Request to be executed by IO classes. Upon execution, the
 * HTTP Request to be executed by IO classes. Upon execution, the
 * responseHttpCode, responseHeaders and responseBody will be filled in.
 *
 * @author Chris Chabot <chabotc@google.com>
 * @author Chirag Shah <chirags@google.com>
 *
 */
class Request
{
    const GZIP_UA = " (gzip)";

    protected $queryParams;
    protected $requestMethod;
    protected $requestHeaders;
    protected $baseComponent = null;
    protected $path;
    protected $postBody;
    protected $userAgent;
    protected $canGzip = null;

    protected $responseHttpCode;
    protected $responseHeaders;
    protected $responseBody;

    protected $expectedClass;
    protected $expectedRaw = false;

    /**
     * @var Utils
     */
    protected $utils;

    public $accessKey;

    public function __construct(
        $url,
        $method = 'GET',
        $headers = array(),
        $postBody = null
    ) {
        $this->utils = new Utils();
        $this->setUrl($url);
        $this->setRequestMethod($method);
        $this->setRequestHeaders($headers);
        $this->setPostBody($postBody);
    }

    /**
     * Misc function that returns the base url component of the $url
     * used by the OAuth signing class to calculate the base string
     * @return string The base url component of the $url.
     */
    public function getBaseComponent()
    {
        return $this->baseComponent;
    }

    /**
     * Set the base URL that path and query parameters will be added to.
     * @param $baseComponent string
     */
    public function setBaseComponent($baseComponent)
    {
        $this->baseComponent = $baseComponent;
    }

    /**
     * Enable support for gzipped responses with this request.
     */
    public function enableGzip()
    {
        $this->setRequestHeaders(array("Accept-Encoding" => "gzip"));
        $this->canGzip = true;
        $this->setUserAgent($this->userAgent);
    }

    /**
     * Disable support for gzip responses with this request.
     */
    public function disableGzip()
    {
        if (
            isset($this->requestHeaders['accept-encoding']) &&
            $this->requestHeaders['accept-encoding'] == "gzip"
        ) {
            unset($this->requestHeaders['accept-encoding']);
        }
        $this->canGzip = false;
        $this->userAgent = str_replace(self::GZIP_UA, "", $this->userAgent);
    }

    /**
     * Can this request accept a gzip response?
     * @return bool
     */
    public function canGzip()
    {
        return $this->canGzip;
    }

    /**
     * Misc function that returns an array of the query parameters of the current
     * url used by the OAuth signing class to calculate the signature
     * @return array Query parameters in the query string.
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Set a new query parameter.
     * @param $key - string to set, does not need to be URL encoded
     * @param $value - string to set, does not need to be URL encoded
     */
    public function setQueryParam($key, $value)
    {
        $this->queryParams[$key] = $value;
    }

    /**
     * @return string HTTP Response Code.
     */
    public function getResponseHttpCode()
    {
        return (int) $this->responseHttpCode;
    }

    /**
     * @param int $responseHttpCode HTTP Response Code.
     */
    public function setResponseHttpCode($responseHttpCode)
    {
        $this->responseHttpCode = $responseHttpCode;
    }

    /**
     * @return $responseHeaders (array) HTTP Response Headers.
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * @return string HTTP Response Body
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * Set the class the response to this request should expect.
     *
     * @param $class string the class name
     */
    public function setExpectedClass($class)
    {
        $this->expectedClass = $class;
    }

    /**
     * Retrieve the expected class the response should expect.
     * @return string class name
     */
    public function getExpectedClass()
    {
        return $this->expectedClass;
    }

    /**
     * Enable expected raw response
     */
    public function enableExpectedRaw()
    {
        $this->expectedRaw = true;
    }

    /**
     * Disable expected raw response
     */
    public function disableExpectedRaw()
    {
        $this->expectedRaw = false;
    }

    /**
     * Expected raw response or not.
     * @return boolean expected raw response
     */
    public function hasExpectedRaw()
    {
        return $this->expectedRaw;
    }

    /**
     * @param string $key
     * @return array|boolean Returns the requested HTTP header or
     * false if unavailable.
     */
    public function getResponseHeader($key)
    {
        return isset($this->responseHeaders[$key])
            ? $this->responseHeaders[$key]
            : false;
    }

    /**
     * @param string $responseBody The HTTP response body.
     */
    public function setResponseBody($responseBody)
    {
        $this->responseBody = $responseBody;
    }

    /**
     * @return string $url The request URL.
     */
    public function getUrl()
    {
        return $this->baseComponent . $this->path .
        (count($this->queryParams) ?
            "?" . $this->buildQuery($this->queryParams) :
            '');
    }

    /**
     * @return string $method HTTP Request Method.
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return array $headers HTTP Request Headers.
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    /**
     * @param string $key
     * @return array|boolean Returns the requested HTTP header or
     * false if unavailable.
     */
    public function getRequestHeader($key)
    {
        return isset($this->requestHeaders[$key])
            ? $this->requestHeaders[$key]
            : false;
    }

    /**
     * @return string $postBody HTTP Request Body.
     */
    public function getPostBody()
    {
        return $this->postBody;
    }

    /**
     * @param string $url the url to set
     */
    public function setUrl($url)
    {
        $url = $this->normalizeUrl($url);
        $parts = parse_url($url);
        if (isset($parts['host'])) {
            $this->computeBaseComponent($parts);
        }
        $this->path = isset($parts['path']) ? $parts['path'] : '';
        $this->queryParams = array();
        if (isset($parts['query'])) {
            $this->queryParams = $this->parseQuery($parts['query']);
        }
    }

    /**
     * @param $url
     * @return string
     */
    protected function normalizeUrl($url)
    {
        if (substr($url, 0, 4) != 'http') {
            // Force the path become relative.
            if (substr($url, 0, 1) !== '/') {
                $url = '/' . $url;
            }
        }
        return $url;
    }

    protected function computeBaseComponent(&$parts)
    {
        $this->baseComponent = sprintf(
            "%s%s%s",
            isset($parts['scheme']) ? $parts['scheme'] . "://" : '',
            isset($parts['host']) ? $parts['host'] : '',
            isset($parts['port']) ? ":" . $parts['port'] : ''
        );
    }

    /**
     * @param string $method Set he HTTP Method and normalize
     * it to upper-case, as required by HTTP.
     *
     */
    public function setRequestMethod($method)
    {
        $this->requestMethod = strtoupper($method);
    }

    /**
     * @param array $headers The HTTP request headers
     * to be set and normalized.
     */
    public function setRequestHeaders($headers)
    {
        $headers = $this->utils->normalize($headers);
        if ($this->requestHeaders) {
            $headers = array_merge($this->requestHeaders, $headers);
        }
        $this->requestHeaders = $headers;
    }

    /**
     * @param string $postBody the postBody to set
     */
    public function setPostBody($postBody)
    {
        $this->postBody = $postBody;
    }

    /**
     * Set the User-Agent Header.
     * @param string $userAgent The User-Agent.
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        if ($this->canGzip) {
            $this->userAgent = $userAgent . self::GZIP_UA;
        }
    }

    /**
     * Our own version of parse_str that allows for multiple variables
     * with the same name.
     * @param $string - the query string to parse
     * @return string
     */
    private function parseQuery($string)
    {
        $return = array();
        $parts = explode("&", $string);
        foreach ($parts as $part) {
            list($key, $value) = explode('=', $part, 2);
            $value = urldecode($value);
            if (isset($return[$key])) {
                if (!is_array($return[$key])) {
                    $return[$key] = array($return[$key]);
                }
                $return[$key][] = $value;
                continue;
            }
            $return[$key] = $value;
        }
        return $return;
    }

    /**
     * A version of build query that allows for multiple
     * duplicate keys.
     * @param $parts array of key value pairs
     * @return string
     */
    private function buildQuery($parts)
    {
        $return = array();
        foreach ($parts as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $return[] = urlencode($key) . "=" . urlencode($v);
                }
                continue;
            }
            $return[] = urlencode($key) . "=" . urlencode($value);
        }
        return implode('&', $return);
    }

    /**
     * @return string The User-Agent.
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
}
