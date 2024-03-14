<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata\Driver;

use WPPayVendor\Metadata\ClassMetadata;
use WPPayVendor\Psr\Container\ContainerInterface as PsrContainerInterface;
use WPPayVendor\Symfony\Component\DependencyInjection\ContainerInterface;
class LazyLoadingDriver implements \WPPayVendor\Metadata\Driver\DriverInterface
{
    /**
     * @var ContainerInterface|PsrContainerInterface
     */
    private $container;
    /**
     * @var string
     */
    private $realDriverId;
    /**
     * @param ContainerInterface|PsrContainerInterface $container
     */
    public function __construct($container, string $realDriverId)
    {
        if (!$container instanceof \WPPayVendor\Psr\Container\ContainerInterface && !$container instanceof \WPPayVendor\Symfony\Component\DependencyInjection\ContainerInterface) {
            throw new \InvalidArgumentException(\sprintf('The container must be an instance of %s or %s (%s given).', \WPPayVendor\Psr\Container\ContainerInterface::class, \WPPayVendor\Symfony\Component\DependencyInjection\ContainerInterface::class, \is_object($container) ? \get_class($container) : \gettype($container)));
        }
        $this->container = $container;
        $this->realDriverId = $realDriverId;
    }
    public function loadMetadataForClass(\ReflectionClass $class) : ?\WPPayVendor\Metadata\ClassMetadata
    {
        return $this->container->get($this->realDriverId)->loadMetadataForClass($class);
    }
}
