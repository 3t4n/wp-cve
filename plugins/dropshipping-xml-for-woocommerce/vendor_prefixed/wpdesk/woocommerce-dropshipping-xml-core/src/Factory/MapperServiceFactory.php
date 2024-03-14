<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductAttributeMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductCategoryMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductImageMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedAttributeMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedImageMapperService;
/**
 * Class MapperServiceFactory, factory for mapper services.
 * @package WPDesk\Library\DropshippingXmlCore\Factory
 */
class MapperServiceFactory
{
    /**
     * @var DependencyResolverInterface
     */
    protected $resolver;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $dependency_resolver)
    {
        $this->resolver = $dependency_resolver;
    }
    public function create_product_mapper(string $class_name, array $arguments = array()) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface
    {
        $product_mappers = $this->get_all_product_mappers();
        foreach ($product_mappers as $mapper) {
            if ($class_name === $mapper) {
                return $this->resolver->resolve($mapper, $arguments);
            }
        }
        throw new \RuntimeException('Error, product mapper class name ' . $class_name . ' not found.');
    }
    public function create_import_mapper(string $class_name, array $arguments = array()) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface
    {
        $import_mappers = $this->get_all_import_mappers();
        foreach ($import_mappers as $mapper) {
            if ($class_name === $mapper) {
                return $this->resolver->resolve($mapper, $arguments);
            }
        }
        throw new \RuntimeException('Error, import mapper class name ' . $class_name . ' not found.');
    }
    protected function get_all_product_mappers() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductImageMapperService::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductAttributeMapperService::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductCategoryMapperService::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedMapperService::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedAttributeMapperService::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedImageMapperService::class];
    }
    protected function get_all_import_mappers() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService::class];
    }
}
