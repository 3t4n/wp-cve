<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\HttpClient;

use WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
use WPPayVendor\Psr\Http\Message\ResponseInterface;
interface HttpClientInterface
{
    /**
     * Perform POST request.
     *
     * @param AbstractDto $requestDto
     *
     * @return ResponseInterface
     */
    public function post(\WPPayVendor\BlueMedia\Common\Dto\AbstractDto $requestDto) : \WPPayVendor\Psr\Http\Message\ResponseInterface;
}
