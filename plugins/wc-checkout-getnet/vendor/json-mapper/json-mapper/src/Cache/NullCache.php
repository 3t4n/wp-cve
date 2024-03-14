<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\JsonMapper\Cache;

use CoffeeCode\Psr\SimpleCache\CacheInterface;
use CoffeeCode\Symfony\Component\Cache\Adapter\NullAdapter;
use CoffeeCode\Symfony\Component\Cache\Psr16Cache;

class NullCache extends Psr16Cache implements CacheInterface
{

    public function __construct()
    {
        parent::__construct(new NullAdapter());
    }
}