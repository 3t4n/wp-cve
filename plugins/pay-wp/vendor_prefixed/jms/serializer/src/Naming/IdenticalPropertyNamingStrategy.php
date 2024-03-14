<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Naming;

use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
final class IdenticalPropertyNamingStrategy implements \WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface
{
    public function translateName(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $property) : string
    {
        return $property->name;
    }
}
