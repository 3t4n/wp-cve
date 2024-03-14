<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common;

use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseInterface;
/**
 * Emulates an HTTP Client in an HTTP Async Client.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait HttpClientEmulator
{
    /**
     * {@inheritdoc}
     *
     * @see HttpClient::sendRequest
     */
    public function sendRequest(RequestInterface $request) : ResponseInterface
    {
        $promise = $this->sendAsyncRequest($request);
        return $promise->wait();
    }
    /**
     * {@inheritdoc}
     *
     * @see HttpAsyncClient::sendAsyncRequest
     */
    public abstract function sendAsyncRequest(RequestInterface $request);
}
