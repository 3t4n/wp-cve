<?php

namespace WPPayVendor\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     *
     * @return bool
     */
    public static function pending(\WPPayVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \WPPayVendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     *
     * @return bool
     */
    public static function settled(\WPPayVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() !== \WPPayVendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     *
     * @return bool
     */
    public static function fulfilled(\WPPayVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \WPPayVendor\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     *
     * @return bool
     */
    public static function rejected(\WPPayVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \WPPayVendor\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
