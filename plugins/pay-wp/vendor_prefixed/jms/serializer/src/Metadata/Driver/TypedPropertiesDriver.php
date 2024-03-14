<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata as SerializerClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\StaticPropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\VirtualPropertyMetadata;
use WPPayVendor\JMS\Serializer\Type\Parser;
use WPPayVendor\JMS\Serializer\Type\ParserInterface;
use WPPayVendor\Metadata\ClassMetadata;
use WPPayVendor\Metadata\Driver\DriverInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;
class TypedPropertiesDriver implements \WPPayVendor\Metadata\Driver\DriverInterface
{
    /**
     * @var DriverInterface
     */
    protected $delegate;
    /**
     * @var ParserInterface
     */
    protected $typeParser;
    /**
     * @var string[]
     */
    private $allowList;
    /**
     * @param string[] $allowList
     */
    public function __construct(\WPPayVendor\Metadata\Driver\DriverInterface $delegate, ?\WPPayVendor\JMS\Serializer\Type\ParserInterface $typeParser = null, array $allowList = [])
    {
        $this->delegate = $delegate;
        $this->typeParser = $typeParser ?: new \WPPayVendor\JMS\Serializer\Type\Parser();
        $this->allowList = \array_merge($allowList, $this->getDefaultWhiteList());
    }
    private function getDefaultWhiteList() : array
    {
        return ['int', 'float', 'bool', 'boolean', 'string', 'double', 'iterable', 'resource'];
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
        // We base our scan on the internal driver's property list so that we
        // respect any internal allow/blocklist like in the AnnotationDriver
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            // If the inner driver provides a type, don't guess anymore.
            if ($propertyMetadata->type) {
                continue;
            }
            try {
                $reflectionType = $this->getReflectionType($propertyMetadata);
                if ($this->shouldTypeHint($reflectionType)) {
                    $type = $reflectionType->getName();
                    $propertyMetadata->setType($this->typeParser->parse($type));
                }
            } catch (\ReflectionException $e) {
                continue;
            }
        }
        return $classMetadata;
    }
    private function getReflectionType(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : ?\ReflectionType
    {
        if ($this->isNotSupportedVirtualProperty($propertyMetadata)) {
            return null;
        }
        if ($propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\VirtualPropertyMetadata) {
            return (new \ReflectionMethod($propertyMetadata->class, $propertyMetadata->getter))->getReturnType();
        }
        return (new \ReflectionProperty($propertyMetadata->class, $propertyMetadata->name))->getType();
    }
    private function isNotSupportedVirtualProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : bool
    {
        return $propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\StaticPropertyMetadata || $propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata;
    }
    /**
     * @phpstan-assert-if-true \ReflectionNamedType $reflectionType
     */
    private function shouldTypeHint(?\ReflectionType $reflectionType) : bool
    {
        if (!$reflectionType instanceof \ReflectionNamedType) {
            return \false;
        }
        if (\in_array($reflectionType->getName(), $this->allowList, \true)) {
            return \true;
        }
        return \class_exists($reflectionType->getName()) || \interface_exists($reflectionType->getName());
    }
}
