<?php

namespace Dotdigital_WordPress_Vendor\Http\Discovery;

use Dotdigital_WordPress_Vendor\Psr\Http\Client\ClientInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ServerRequestFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\StreamFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\UploadedFileFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\UriFactoryInterface;
/**
 * A generic PSR-18 and PSR-17 implementation.
 *
 * You can create this class with concrete client and factory instances
 * or let it use discovery to find suitable implementations as needed.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class Psr18Client extends Psr17Factory implements ClientInterface
{
    private $client;
    public function __construct(ClientInterface $client = null, RequestFactoryInterface $requestFactory = null, ResponseFactoryInterface $responseFactory = null, ServerRequestFactoryInterface $serverRequestFactory = null, StreamFactoryInterface $streamFactory = null, UploadedFileFactoryInterface $uploadedFileFactory = null, UriFactoryInterface $uriFactory = null)
    {
        parent::__construct($requestFactory, $responseFactory, $serverRequestFactory, $streamFactory, $uploadedFileFactory, $uriFactory);
        $this->client = $client ?? Psr18ClientDiscovery::find();
    }
    public function sendRequest(RequestInterface $request) : ResponseInterface
    {
        return $this->client->sendRequest($request);
    }
}
