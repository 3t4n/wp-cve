<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common;

use Dotdigital_WordPress_Vendor\Http\Client\HttpAsyncClient;
use Dotdigital_WordPress_Vendor\Http\Client\HttpClient;
use Dotdigital_WordPress_Vendor\Psr\Http\Client\ClientInterface;
/**
 * Emulates an async HTTP client with the help of a synchronous client.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class EmulatedHttpAsyncClient implements HttpClient, HttpAsyncClient
{
    use HttpAsyncClientEmulator;
    use HttpClientDecorator;
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
}
