<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use WPPayVendor\Metadata\ClassMetadata as BaseClassMetadata;
use WPPayVendor\Metadata\Driver\DriverInterface;
class NullDriver implements \WPPayVendor\Metadata\Driver\DriverInterface
{
    /**
     * @var PropertyNamingStrategyInterface
     */
    private $namingStrategy;
    public function __construct(\WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface $namingStrategy)
    {
        $this->namingStrategy = $namingStrategy;
    }
    public function loadMetadataForClass(\ReflectionClass $class) : ?\WPPayVendor\Metadata\ClassMetadata
    {
        $classMetadata = new \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata($name = $class->name);
        $fileResource = $class->getFilename();
        if (\false !== $fileResource) {
            $classMetadata->fileResources[] = $fileResource;
        }
        foreach ($class->getProperties() as $property) {
            if ($property->class !== $name || isset($property->info) && $property->info['class'] !== $name) {
                continue;
            }
            $propertyMetadata = new \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata($name, $property->getName());
            if (!$propertyMetadata->serializedName) {
                $propertyMetadata->serializedName = $this->namingStrategy->translateName($propertyMetadata);
            }
            $classMetadata->addPropertyMetadata($propertyMetadata);
        }
        return $classMetadata;
    }
}
