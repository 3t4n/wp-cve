<?php

declare (strict_types=1);
namespace DhlVendor\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     */
    public static function pending(\DhlVendor\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() === \DhlVendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     */
    public static function settled(\DhlVendor\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() !== \DhlVendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     */
    public static function fulfilled(\DhlVendor\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() === \DhlVendor\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     */
    public static function rejected(\DhlVendor\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() === \DhlVendor\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
