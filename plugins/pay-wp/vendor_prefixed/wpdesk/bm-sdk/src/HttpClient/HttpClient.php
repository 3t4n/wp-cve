<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\HttpClient;

use WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
use WPPayVendor\GuzzleHttp\Client;
use WPPayVendor\GuzzleHttp\RequestOptions;
use WPPayVendor\Psr\Http\Message\ResponseInterface;
class HttpClient implements \WPPayVendor\BlueMedia\HttpClient\HttpClientInterface
{
    private $client;
    public function __construct()
    {
        $this->client = new \WPPayVendor\GuzzleHttp\Client([\WPPayVendor\GuzzleHttp\RequestOptions::ALLOW_REDIRECTS => \false, \WPPayVendor\GuzzleHttp\RequestOptions::HTTP_ERRORS => \false, \WPPayVendor\GuzzleHttp\RequestOptions::VERIFY => \true]);
    }
    /**
     * Perform POST request.
     *
     * @param AbstractDto $requestDto
     *
     * @return ResponseInterface
     */
    public function post(\WPPayVendor\BlueMedia\Common\Dto\AbstractDto $requestDto) : \WPPayVendor\Psr\Http\Message\ResponseInterface
    {
        $options = [\WPPayVendor\GuzzleHttp\RequestOptions::HEADERS => $requestDto->getRequest()->getRequestHeaders(), \WPPayVendor\GuzzleHttp\RequestOptions::FORM_PARAMS => $requestDto->getRequestData()->capitalizedArray()];
        return $this->client->post($requestDto->getRequest()->getRequestUrl(), $options);
    }
}
