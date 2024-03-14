<?php

namespace UpsFreeVendor\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     *
     * @return bool
     */
    public static function pending(\UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     *
     * @return bool
     */
    public static function settled(\UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() !== \UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     *
     * @return bool
     */
    public static function fulfilled(\UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     *
     * @return bool
     */
    public static function rejected(\UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
