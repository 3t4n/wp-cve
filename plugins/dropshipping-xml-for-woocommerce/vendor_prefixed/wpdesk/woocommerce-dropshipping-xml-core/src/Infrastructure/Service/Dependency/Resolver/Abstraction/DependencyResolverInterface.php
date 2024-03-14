<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction;

/**
 * Interface DependencyResolverInterface, abstraction layer for dependency resolver implementation.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Service
 */
interface DependencyResolverInterface
{
    /**
     * @param string $class_name
     * @param array $arguments
     *
     * @return object
     */
    public function resolve(string $class_name, array $arguments = array());
}
