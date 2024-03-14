<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\ContextFactory;

use WPPayVendor\JMS\Serializer\SerializationContext;
/**
 * Default Serialization Context Factory.
 */
final class DefaultSerializationContextFactory implements \WPPayVendor\JMS\Serializer\ContextFactory\SerializationContextFactoryInterface
{
    public function createSerializationContext() : \WPPayVendor\JMS\Serializer\SerializationContext
    {
        return new \WPPayVendor\JMS\Serializer\SerializationContext();
    }
}
