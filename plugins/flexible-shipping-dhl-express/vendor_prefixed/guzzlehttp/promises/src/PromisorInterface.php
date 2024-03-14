<?php

declare (strict_types=1);
namespace DhlVendor\GuzzleHttp\Promise;

/**
 * Interface used with classes that return a promise.
 */
interface PromisorInterface
{
    /**
     * Returns a promise.
     */
    public function promise() : \DhlVendor\GuzzleHttp\Promise\PromiseInterface;
}
