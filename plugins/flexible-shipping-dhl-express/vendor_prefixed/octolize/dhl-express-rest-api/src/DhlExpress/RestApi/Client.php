<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi;

use DhlVendor\GuzzleHttp\Client as GuzzleClient;
use DhlVendor\Ramsey\Uuid\Uuid;
use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\ClientException;
class Client
{
    protected const URI_PRODUCTION = 'https://express.api.dhl.com/mydhlapi/';
    protected const URI_MOCK = 'https://api-mock.dhl.com/mydhlapi/';
    protected const URI_TEST = 'https://express.api.dhl.com/mydhlapi/test/';
    protected string $baseUri;
    protected string $lastMessageReference;
    protected string $username;
    protected string $password;
    protected bool $testMode;
    public function __construct(string $username, string $password, bool $testMode)
    {
        $this->testMode = $testMode;
        $this->password = $password;
        $this->username = $username;
        $this->baseUri = $this->testMode ? self::URI_TEST : self::URI_PRODUCTION;
    }
    public function enableMockServer() : void
    {
        $this->baseUri = self::URI_MOCK;
    }
    public function getBaseUri() : string
    {
        return $this->baseUri;
    }
    /**
     * @throws ClientException
     */
    public function get(string $uri, array $query) : array
    {
        $httpClient = new \DhlVendor\GuzzleHttp\Client();
        $options = $this->getRequestOptions('GET', $query);
        try {
            $response = $httpClient->request('GET', $uri, $options);
        } catch (\DhlVendor\GuzzleHttp\Exception\ClientException $e) {
            $this->handleException($e);
        }
        return \json_decode((string) $response->getBody(), \true);
    }
    /**
     * @throws ClientException
     */
    public function post(string $uri, array $query) : array
    {
        $httpClient = new \DhlVendor\GuzzleHttp\Client();
        $options = $this->getRequestOptions('POST', $query);
        try {
            $response = $httpClient->request('POST', $uri, $options);
        } catch (\DhlVendor\GuzzleHttp\Exception\ClientException $e) {
            $this->handleException($e);
        }
        return \json_decode((string) $response->getBody(), \true);
    }
    private function handleException(\DhlVendor\GuzzleHttp\Exception\ClientException $e) : void
    {
        $response = \json_decode($e->getResponse()->getBody()->getContents());
        if (\is_object($response)) {
            if (isset($response->reasons)) {
                foreach ($response->reasons as $reason) {
                    throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\ClientException($reason->msg);
                }
            }
            if (isset($response->message)) {
                if (isset($response->additionalDetails)) {
                    throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\ClientException($response->message . ' ' . \implode(',', $response->additionalDetails));
                }
                if (isset($response->detail)) {
                    throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\ClientException($response->message . ' ' . $response->detail);
                }
                throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\ClientException($response->message);
            }
            if (isset($response->detail)) {
                throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\ClientException($response->detail);
            }
        }
        throw $e;
    }
    protected function generateMessageReference() : string
    {
        $this->lastMessageReference = \DhlVendor\Ramsey\Uuid\Uuid::uuid6()->toString();
        return $this->lastMessageReference;
    }
    protected function getRequestOptions(string $queryType, array $query) : array
    {
        $requestOptions = ['base_uri' => $this->baseUri, 'auth' => [$this->username, $this->password], 'headers' => ['Content-Type' => 'application/json', 'Message-Reference' => $this->generateMessageReference()]];
        if ($queryType === "GET") {
            $requestOptions['query'] = $query;
        } else {
            $requestOptions['json'] = $query;
        }
        return $requestOptions;
    }
}
