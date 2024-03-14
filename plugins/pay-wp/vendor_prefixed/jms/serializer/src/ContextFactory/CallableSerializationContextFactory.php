<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\ContextFactory;

use WPPayVendor\JMS\Serializer\SerializationContext;
/**
 * Serialization Context Factory using a callable.
 */
final class CallableSerializationContextFactory implements \WPPayVendor\JMS\Serializer\ContextFactory\SerializationContextFactoryInterface
{
    /**
     * @var callable():SerializationContext
     */
    private $callable;
    /**
     * @param callable():SerializationContext $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }
    public function createSerializationContext() : \WPPayVendor\JMS\Serializer\SerializationContext
    {
        $callable = $this->callable;
        return $callable();
    }
}
