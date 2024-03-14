<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common;

use Dotdigital_WordPress_Vendor\Http\Client\Common\HttpClientPool\HttpClientPoolItem;
use Dotdigital_WordPress_Vendor\Http\Client\HttpAsyncClient;
use Dotdigital_WordPress_Vendor\Http\Client\HttpClient;
use Dotdigital_WordPress_Vendor\Psr\Http\Client\ClientInterface;
/**
 * A http client pool allows to send requests on a pool of different http client using a specific strategy (least used,
 * round robin, ...).
 */
interface HttpClientPool extends HttpAsyncClient, HttpClient
{
    /**
     * Add a client to the pool.
     *
     * @param ClientInterface|HttpAsyncClient|HttpClientPoolItem $client
     */
    public function addHttpClient($client) : void;
}
