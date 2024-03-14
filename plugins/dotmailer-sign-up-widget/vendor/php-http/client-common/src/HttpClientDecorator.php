<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common;

use Dotdigital_WordPress_Vendor\Psr\Http\Client\ClientInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseInterface;
/**
 * Decorates an HTTP Client.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait HttpClientDecorator
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;
    /**
     * {@inheritdoc}
     *
     * @see ClientInterface::sendRequest
     */
    public function sendRequest(RequestInterface $request) : ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }
}
