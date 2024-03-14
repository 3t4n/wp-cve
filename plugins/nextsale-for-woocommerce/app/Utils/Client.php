<?php

namespace App\Utils;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Client as GuzzleHttpClient;

class Client
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    public function __construct($config = [])
    {
        $config = array_merge([
            'verify' => false
        ], $config);

        return $this->httpClient = new GuzzleHttpClient($config);
    }

    /**
     * Builds an url by given resource name.
     *
     * @param string $resource
     *
     * @return string
     */
    protected function buildUrl($resource)
    {
        return $resource;
    }

    /**
     * Perform a GET request.
     *
     * @param string $resource
     * @param array  $query
     *
     * @return array
     */
    public function get($resource, $query = [])
    {
        return $this->doRequest('GET', $resource, [
            'query' => $query,
        ]);
    }

    /**
     * Perform a POST request.
     *
     * @param string $resource
     * @param array  $data
     * @param array  $query
     *
     * @return array
     */
    public function post($resource, $data = [], $query = [])
    {
        return $this->doRequest('POST', $resource, [
            'query' => $query,
            'json' => $data,
        ]);
    }

    /**
     * Perform a PUT request.
     *
     * @param string $resource
     * @param array  $data
     * @param array  $query
     *
     * @return array
     */
    public function put($resource, $data = [], $query = [])
    {
        return $this->doRequest('PUT', $resource, [
            'query' => $query,
            'json' => $data,
        ]);
    }

    /**
     * Perform a DELETE request.
     *
     * @param string $resource
     * @param array  $query
     */
    public function delete($resource, $query = [])
    {
        $this->doRequest('DELETE', $resource, [
            'query' => $query
        ]);
    }

    /**
     * Do the request
     * @param  [type] $method   [description]
     * @param  [type] $resource [description]
     * @param  array  $options  [description]
     * @return [type]           [description]
     */
    protected function doRequest($method, $resource, $options = [])
    {
        $request = new Request($method, $this->buildUrl($resource));

        return $this->sendRequest($request, $options);
    }

    /**
     * Send a request.
     *
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $original_request, $options = [])
    {
        $request = $original_request;

        $response = $this->httpClient->send($request, $options);

        return $response;
    }
}
