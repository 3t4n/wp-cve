<?php

namespace WPPayVendor\GuzzleHttp\Handler;

use WPPayVendor\Psr\Http\Message\RequestInterface;
interface CurlFactoryInterface
{
    /**
     * Creates a cURL handle resource.
     *
     * @param RequestInterface $request Request
     * @param array            $options Transfer options
     *
     * @throws \RuntimeException when an option cannot be applied
     */
    public function create(\WPPayVendor\Psr\Http\Message\RequestInterface $request, array $options) : \WPPayVendor\GuzzleHttp\Handler\EasyHandle;
    /**
     * Release an easy handle, allowing it to be reused or closed.
     *
     * This function must call unset on the easy handle's "handle" property.
     */
    public function release(\WPPayVendor\GuzzleHttp\Handler\EasyHandle $easy) : void;
}
