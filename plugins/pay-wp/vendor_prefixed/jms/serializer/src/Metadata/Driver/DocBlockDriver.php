<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata as SerializerClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\Driver\DocBlockDriver\DocBlockTypeResolver;
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
use ReflectionProperty;
class DocBlockDriver implements \WPPayVendor\Metadata\Driver\DriverInterface
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
     * @var DocBlockTypeResolver
     */
    private $docBlockTypeResolver;
    public function __construct(\WPPayVendor\Metadata\Driver\DriverInterface $delegate, ?\WPPayVendor\JMS\Serializer\Type\ParserInterface $typeParser = null)
    {
        $this->delegate = $delegate;
        $this->typeParser = $typeParser ?: new \WPPayVendor\JMS\Serializer\Type\Parser();
        $this->docBlockTypeResolver = new \WPPayVendor\JMS\Serializer\Metadata\Driver\DocBlockDriver\DocBlockTypeResolver();
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
        foreach ($classMetadata->propertyMetadata as $key => $propertyMetadata) {
            // If the inner driver provides a type, don't guess anymore.
            if ($propertyMetadata->type) {
                continue;
            }
            if ($this->isNotSupportedVirtualProperty($propertyMetadata)) {
                continue;
            }
            try {
                if ($propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\VirtualPropertyMetadata) {
                    $type = $this->docBlockTypeResolver->getMethodDocblockTypeHint(new \ReflectionMethod($propertyMetadata->class, $propertyMetadata->getter));
                } else {
                    $type = $this->docBlockTypeResolver->getPropertyDocblockTypeHint(new \ReflectionProperty($propertyMetadata->class, $propertyMetadata->name));
                }
                if ($type) {
                    $propertyMetadata->setType($this->typeParser->parse($type));
                }
            } catch (\ReflectionException $e) {
                continue;
            }
        }
        return $classMetadata;
    }
    private function isNotSupportedVirtualProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : bool
    {
        return $propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\StaticPropertyMetadata || $propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata;
    }
}
