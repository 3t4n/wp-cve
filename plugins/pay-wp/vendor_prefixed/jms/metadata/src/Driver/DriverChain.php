<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata\Driver;

use WPPayVendor\Metadata\ClassMetadata;
final class DriverChain implements \WPPayVendor\Metadata\Driver\AdvancedDriverInterface
{
    /**
     * @var DriverInterface[]
     */
    private $drivers;
    /**
     * @param DriverInterface[] $drivers
     */
    public function __construct(array $drivers = [])
    {
        $this->drivers = $drivers;
    }
    public function addDriver(\WPPayVendor\Metadata\Driver\DriverInterface $driver) : void
    {
        $this->drivers[] = $driver;
    }
    public function loadMetadataForClass(\ReflectionClass $class) : ?\WPPayVendor\Metadata\ClassMetadata
    {
        foreach ($this->drivers as $driver) {
            if (null !== ($metadata = $driver->loadMetadataForClass($class))) {
                return $metadata;
            }
        }
        return null;
    }
    /**
     * {@inheritDoc}
     */
    public function getAllClassNames() : array
    {
        $classes = [];
        foreach ($this->drivers as $driver) {
            if (!$driver instanceof \WPPayVendor\Metadata\Driver\AdvancedDriverInterface) {
                throw new \RuntimeException(\sprintf('Driver "%s" must be an instance of "AdvancedDriverInterface" to use ' . '"DriverChain::getAllClassNames()".', \get_class($driver)));
            }
            $driverClasses = $driver->getAllClassNames();
            if (!empty($driverClasses)) {
                $classes = \array_merge($classes, $driverClasses);
            }
        }
        return $classes;
    }
}
