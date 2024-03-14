<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\ContextFactory;

use WPPayVendor\JMS\Serializer\SerializationContext;
/**
 * Serialization Context Factory Interface.
 */
interface SerializationContextFactoryInterface
{
    public function createSerializationContext() : \WPPayVendor\JMS\Serializer\SerializationContext;
}
