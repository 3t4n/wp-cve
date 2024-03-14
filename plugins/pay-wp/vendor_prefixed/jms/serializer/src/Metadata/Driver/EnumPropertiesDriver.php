<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata as SerializerClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\StaticPropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\VirtualPropertyMetadata;
use WPPayVendor\Metadata\ClassMetadata;
use WPPayVendor\Metadata\Driver\DriverInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
class EnumPropertiesDriver implements \WPPayVendor\Metadata\Driver\DriverInterface
{
    /**
     * @var DriverInterface
     */
    protected $delegate;
    public function __construct(\WPPayVendor\Metadata\Driver\DriverInterface $delegate)
    {
        $this->delegate = $delegate;
    }
    public function loadMetadataForClass(\ReflectionClass $class) : ?\WPPayVendor\Metadata\ClassMetadata
    {
        $classMetadata = $this->delegate->loadMetadataForClass($class);
        if (null === $classMetadata) {
            return null;
        }
        \assert($classMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata);
        // We base our scan on the internal driver's property list so that we
        // respect any internal allow/blocklist like in the AnnotationDriver
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            // If the inner driver provides a type, don't guess anymore.
            if ($propertyMetadata->type || $this->isVirtualProperty($propertyMetadata)) {
                continue;
            }
            try {
                $propertyReflection = $this->getReflection($propertyMetadata);
                if ($enum = $this->getEnumReflection($propertyReflection)) {
                    $serializerType = ['name' => 'enum', 'params' => [$enum->getName(), $enum->isBacked() ? 'value' : 'name']];
                    $propertyMetadata->setType($serializerType);
                }
            } catch (\ReflectionException $e) {
                continue;
            }
        }
        return $classMetadata;
    }
    private function isVirtualProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : bool
    {
        return $propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\VirtualPropertyMetadata || $propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\StaticPropertyMetadata || $propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata;
    }
    private function getReflection(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : \ReflectionProperty
    {
        return new \ReflectionProperty($propertyMetadata->class, $propertyMetadata->name);
    }
    private function getEnumReflection(\ReflectionProperty $propertyReflection) : ?\ReflectionEnum
    {
        $reflectionType = $propertyReflection->getType();
        if (!$reflectionType instanceof \ReflectionNamedType) {
            return null;
        }
        return \enum_exists($reflectionType->getName()) ? new \ReflectionEnum($reflectionType->getName()) : null;
    }
}
