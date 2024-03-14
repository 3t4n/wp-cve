<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 22-September-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\React\Promise;

interface PromisorInterface
{
    /**
     * Returns the promise of the deferred.
     *
     * @return PromiseInterface
     */
    public function promise();
}
