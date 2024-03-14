<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface;
/**
 * Class ProductVariationFactory, factory for product variations.
 */
class ProductVariationFactory
{
    /**
     * @var DependencyResolverInterface
     */
    protected $resolver;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $dependency_resolver)
    {
        $this->resolver = $dependency_resolver;
    }
    public function create_product_variations_from_xml(array $xml_elements, $var) : array
    {
        $product_mappers = $this->get_all_product_mappers();
        foreach ($product_mappers as $mapper) {
            if ($class_name === $mapper) {
                return $this->resolver->resolve($mapper, $arguments);
            }
        }
        throw new \RuntimeException('Error, product mapper class name ' . $class_name . ' not found.');
    }
}
