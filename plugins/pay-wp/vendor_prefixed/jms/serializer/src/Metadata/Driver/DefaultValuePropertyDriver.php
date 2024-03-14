<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata as SerializerClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\Metadata\ClassMetadata;
use WPPayVendor\Metadata\Driver\DriverInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
class DefaultValuePropertyDriver implements \WPPayVendor\Metadata\Driver\DriverInterface
{
    /**
     * @var DriverInterface
     */
    protected $delegate;
    public function __construct(\WPPayVendor\Metadata\Driver\DriverInterface $delegate)
    {
        $this->delegate = $delegate;
    }
    /**
     * @return SerializerClassMetadata|null
     */
    public function loadMetadataForClass(\ReflectionClass $class) : ?\WPPayVendor\Metadata\ClassMetadata
    {
        $classMetadata = $this->delegate->loadMetadataForClass($class);
        if (null === $classMetadata) {
            return null;
        }
        \assert($classMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata);
        foreach ($classMetadata->propertyMetadata as $key => $propertyMetadata) {
            \assert($propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata);
            if (null !== $propertyMetadata->hasDefault) {
                continue;
            }
            try {
                $propertyReflection = $this->getPropertyReflection($propertyMetadata);
                $propertyMetadata->hasDefault = \false;
                if ($propertyReflection->hasDefaultValue() && $propertyReflection->hasType()) {
                    $propertyMetadata->hasDefault = \true;
                    $propertyMetadata->defaultValue = $propertyReflection->getDefaultValue();
                } elseif ($propertyReflection->isPromoted()) {
                    // need to get the parameter in the constructor to check for default values
                    $classReflection = $this->getClassReflection($propertyMetadata);
                    $params = $classReflection->getConstructor()->getParameters();
                    foreach ($params as $parameter) {
                        if ($parameter->getName() === $propertyMetadata->name) {
                            if ($parameter->isDefaultValueAvailable()) {
                                $propertyMetadata->hasDefault = \true;
                                $propertyMetadata->defaultValue = $parameter->getDefaultValue();
                            }
                            break;
                        }
                    }
                }
            } catch (\ReflectionException $e) {
                continue;
            }
        }
        return $classMetadata;
    }
    private function getPropertyReflection(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : \ReflectionProperty
    {
        return new \ReflectionProperty($propertyMetadata->class, $propertyMetadata->name);
    }
    private function getClassReflection(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : \ReflectionClass
    {
        return new \ReflectionClass($propertyMetadata->class);
    }
}
