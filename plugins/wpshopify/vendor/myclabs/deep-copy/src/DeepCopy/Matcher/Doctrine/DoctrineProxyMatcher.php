<?php

namespace ShopWP\Vendor\DeepCopy\Matcher\Doctrine;

use ShopWP\Vendor\DeepCopy\Matcher\Matcher;
use ShopWP\Vendor\Doctrine\Common\Persistence\Proxy;

/**
 * @final
 */
class DoctrineProxyMatcher implements Matcher
{
    /**
     * Matches a Doctrine Proxy class.
     *
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $object instanceof Proxy;
    }
}
