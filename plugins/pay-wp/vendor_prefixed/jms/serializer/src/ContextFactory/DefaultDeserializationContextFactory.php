<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\ContextFactory;

use WPPayVendor\JMS\Serializer\DeserializationContext;
/**
 * Default Deserialization Context Factory.
 */
final class DefaultDeserializationContextFactory implements \WPPayVendor\JMS\Serializer\ContextFactory\DeserializationContextFactoryInterface
{
    public function createDeserializationContext() : \WPPayVendor\JMS\Serializer\DeserializationContext
    {
        return new \WPPayVendor\JMS\Serializer\DeserializationContext();
    }
}
