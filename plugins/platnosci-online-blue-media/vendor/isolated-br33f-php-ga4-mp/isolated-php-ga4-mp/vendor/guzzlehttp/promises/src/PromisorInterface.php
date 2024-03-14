<?php

declare (strict_types=1);
namespace Isolated\Blue_Media\Isolated_Php_ga4_mp\GuzzleHttp\Promise;

/**
 * Interface used with classes that return a promise.
 */
interface PromisorInterface
{
    /**
     * Returns a promise.
     */
    public function promise() : PromiseInterface;
}
