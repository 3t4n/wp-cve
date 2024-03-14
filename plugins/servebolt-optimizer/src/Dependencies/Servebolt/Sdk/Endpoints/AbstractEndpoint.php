<?php

namespace Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Endpoints;

use Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Response;
use Servebolt\Optimizer\Dependencies\Servebolt\Sdk\ConfigHelper;
use Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Http\Client as HttpClient;
use Servebolt\Optimizer\Dependencies\GuzzleHttp\Psr7\Response as Psr7Response;

/**
 * Class AbstractEndpoint
 * @package Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Endpoints
 */
abstract class AbstractEndpoint
{
    /**
     * The configuration helper class.
     *
     * @var ConfigHelper
     */
    protected $config;

    /**
     * Guzzle HTTP client facade.
     *
     * @var HttpClient
     */
    public $httpClient;

    /**
     * ApiEndpoint constructor.
     * @param HttpClient $httpClient
     * @param ConfigHelper $config
     * @param array $arguments
     */
    public function __construct(HttpClient $httpClient, ConfigHelper $config, $arguments = [])
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        if (method_exists($this, 'loadHierarchicalEndpoints')) {
            $this->loadHierarchicalEndpoints();
        }
        if (method_exists($this, 'loadArguments')) {
            $this->loadArguments($arguments);
        }
    }

    /**
     * Filter an array by certain keys.
     *
     * @param array $array
     * @param array $allowedKeys
     * @return array
     */
    protected function filterArrayByKeys(array $array, array $allowedKeys)
    {
        return array_intersect_key($array, array_flip($allowedKeys));
    }

    /**
     * Append common request data to request data array.
     *
     * @param array $requestData
     * @return mixed
     */
    protected function appendCommonRequestData($requestData)
    {
        if (isset($this->endpoint) && !array_key_exists('type', $requestData)) {
            $requestData['type'] = $this->endpoint;
        }
        return $requestData;
    }

    /**
     * Conditional format on HTTP response.
     *
     * @param $httpResponse
     * @return object|Response|Psr7Response
     */
    protected function response($httpResponse)
    {
        switch ($this->config->get('responseObjectType')) {
            case 'psr7':
                return $httpResponse->getResponseObject();
            case 'decodedBody':
                return $httpResponse->getDecodedBody();
            case 'customResponse':
            default:
                return new Response(
                    $httpResponse->getDecodedBody(),
                    $httpResponse->getResponseObject()->getStatusCode()
                );
        }
    }
}
