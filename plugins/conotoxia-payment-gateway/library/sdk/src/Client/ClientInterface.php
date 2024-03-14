<?php

declare(strict_types=1);

namespace CKPL\Pay\Client;

use CKPL\Pay\Client\RawClient\RawClientInterface;
use CKPL\Pay\Client\Request\RequestInterface;
use CKPL\Pay\Client\Response\ResponseInterface;
use CKPL\Pay\Endpoint\EndpointInterface;

/**
 * Interface ClientInterface.
 *
 * @package CKPL\Pay\Client
 */
interface ClientInterface
{
    /**
     * @return RequestInterface
     */
    public function request(): RequestInterface;

    /**
     * @return RawClientInterface
     */
    public function raw(): RawClientInterface;

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface;

    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface;

    /**
     * @param ResponseInterface|null $response
     *
     * @return ClientInterface
     */
    public function setResponse(ResponseInterface $response): ClientInterface;
}
