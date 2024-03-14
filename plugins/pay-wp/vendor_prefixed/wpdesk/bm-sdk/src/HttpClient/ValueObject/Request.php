<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\HttpClient\ValueObject;

final class Request
{
    /**
     * @var string
     */
    private $requestUrl;
    /**
     * @var array
     */
    private $requestHeaders;
    public function __construct(string $requestUrl, array $requestHeaders = [])
    {
        if (empty($requestUrl)) {
            throw new \InvalidArgumentException('Request URL cannot be empty.');
        }
        $this->requestUrl = $requestUrl;
        $this->requestHeaders = $requestHeaders;
    }
    /**
     * @return string
     */
    public function getRequestUrl() : string
    {
        return $this->requestUrl;
    }
    /**
     * @return array
     */
    public function getRequestHeaders() : array
    {
        return $this->requestHeaders;
    }
}
