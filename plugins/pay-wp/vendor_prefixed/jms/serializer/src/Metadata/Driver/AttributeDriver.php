<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute;
class AttributeDriver extends \WPPayVendor\JMS\Serializer\Metadata\Driver\AnnotationOrAttributeDriver
{
    /**
     * @return list<SerializerAttribute>
     */
    protected function getClassAnnotations(\ReflectionClass $class) : array
    {
        return \array_map(static fn(\ReflectionAttribute $attribute): object => $attribute->newInstance(), $class->getAttributes(\WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute::class, \ReflectionAttribute::IS_INSTANCEOF));
    }
    /**
     * @return list<SerializerAttribute>
     */
    protected function getMethodAnnotations(\ReflectionMethod $method) : array
    {
        return \array_map(static fn(\ReflectionAttribute $attribute): object => $attribute->newInstance(), $method->getAttributes(\WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute::class, \ReflectionAttribute::IS_INSTANCEOF));
    }
    /**
     * @return list<SerializerAttribute>
     */
    protected function getPropertyAnnotations(\ReflectionProperty $property) : array
    {
        return \array_map(static fn(\ReflectionAttribute $attribute): object => $attribute->newInstance(), $property->getAttributes(\WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute::class, \ReflectionAttribute::IS_INSTANCEOF));
    }
}
