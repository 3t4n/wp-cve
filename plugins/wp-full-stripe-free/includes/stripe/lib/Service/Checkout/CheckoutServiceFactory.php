<?php

// File generated from our OpenAPI spec

namespace StripeWPFS\Service\Checkout;

/**
 * Service factory class for API resources in the Checkout namespace.
 *
 * @property SessionService $sessions
 */
class CheckoutServiceFactory extends \StripeWPFS\Service\AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'sessions' => SessionService::class,
    ];

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}
