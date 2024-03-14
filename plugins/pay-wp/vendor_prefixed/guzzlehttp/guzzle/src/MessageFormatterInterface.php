<?php

namespace WPPayVendor\GuzzleHttp;

use WPPayVendor\Psr\Http\Message\RequestInterface;
use WPPayVendor\Psr\Http\Message\ResponseInterface;
interface MessageFormatterInterface
{
    /**
     * Returns a formatted message string.
     *
     * @param RequestInterface       $request  Request that was sent
     * @param ResponseInterface|null $response Response that was received
     * @param \Throwable|null        $error    Exception that was received
     */
    public function format(\WPPayVendor\Psr\Http\Message\RequestInterface $request, ?\WPPayVendor\Psr\Http\Message\ResponseInterface $response = null, ?\Throwable $error = null) : string;
}
