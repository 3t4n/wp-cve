<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\ContextFactory;

use WPPayVendor\JMS\Serializer\DeserializationContext;
final class CallableDeserializationContextFactory implements \WPPayVendor\JMS\Serializer\ContextFactory\DeserializationContextFactoryInterface
{
    /**
     * @var callable():DeserializationContext
     */
    private $callable;
    /**
     * @param callable():DeserializationContext $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }
    public function createDeserializationContext() : \WPPayVendor\JMS\Serializer\DeserializationContext
    {
        $callable = $this->callable;
        return $callable();
    }
}
