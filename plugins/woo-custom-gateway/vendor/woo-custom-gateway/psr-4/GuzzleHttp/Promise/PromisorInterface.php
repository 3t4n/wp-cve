<?php

declare(strict_types=1);

namespace RichardMuvirimi\WooCustomGateway\Vendor\GuzzleHttp\Promise;

/**
 * Interface used with classes that return a promise.
 */
interface PromisorInterface
{
    /**
     * Returns a promise.
     */
    public function promise(): PromiseInterface;
}
