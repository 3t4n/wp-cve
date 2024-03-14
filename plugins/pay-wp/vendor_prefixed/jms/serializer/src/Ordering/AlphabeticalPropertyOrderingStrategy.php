<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Ordering;

use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
final class AlphabeticalPropertyOrderingStrategy implements \WPPayVendor\JMS\Serializer\Ordering\PropertyOrderingInterface
{
    /**
     * {@inheritdoc}
     */
    public function order(array $properties) : array
    {
        \uasort($properties, static fn(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $a, \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $b): int => \strcmp($a->name, $b->name));
        return $properties;
    }
}
