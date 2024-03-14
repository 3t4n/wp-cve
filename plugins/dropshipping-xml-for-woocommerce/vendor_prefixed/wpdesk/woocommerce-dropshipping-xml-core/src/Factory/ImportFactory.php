<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
/**
 * Class ImportFactory, import factory class.
 * @package WPDesk\Library\DropshippingXmlCore\Factory
 */
class ImportFactory
{
    /**
     * @var DependencyResolverInterface
     */
    private $resolver;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $dependency_resolver)
    {
        $this->resolver = $dependency_resolver;
    }
    public function create(array $properties) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import
    {
        $entity = $this->resolver->resolve(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::class);
        $entity->update($properties);
        return $entity;
    }
}
