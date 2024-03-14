<?php

namespace DhlVendor\GuzzleHttp\Handler;

use DhlVendor\Psr\Http\Message\RequestInterface;
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
    public function create(\DhlVendor\Psr\Http\Message\RequestInterface $request, array $options) : \DhlVendor\GuzzleHttp\Handler\EasyHandle;
    /**
     * Release an easy handle, allowing it to be reused or closed.
     *
     * This function must call unset on the easy handle's "handle" property.
     */
    public function release(\DhlVendor\GuzzleHttp\Handler\EasyHandle $easy) : void;
}
