<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata\Cache;

use WPPayVendor\Metadata\ClassMetadata;
interface CacheInterface
{
    /**
     * Loads a class metadata instance from the cache
     */
    public function load(string $class) : ?\WPPayVendor\Metadata\ClassMetadata;
    /**
     * Puts a class metadata instance into the cache
     */
    public function put(\WPPayVendor\Metadata\ClassMetadata $metadata) : void;
    /**
     * Evicts the class metadata for the given class from the cache.
     */
    public function evict(string $class) : void;
}
