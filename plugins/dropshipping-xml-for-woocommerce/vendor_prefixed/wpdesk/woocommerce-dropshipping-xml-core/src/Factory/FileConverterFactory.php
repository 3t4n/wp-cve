<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\CsvFileConverter;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\XmlFileConverter;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\FileConverterInterface;
/**
 * Class FileConverterFactory, file converter factory.
 * @package WPDesk\Library\DropshippingXmlCore\Factory
 */
class FileConverterFactory
{
    /**
     * @var DependencyResolverInterface
     */
    private $resolver;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $dependency_resolver)
    {
        $this->resolver = $dependency_resolver;
    }
    public function create_by_data_format(string $format, array $parameters = array()) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\FileConverterInterface
    {
        $data_converters = $this->get_all_product_data_converters();
        foreach ($data_converters as $data_converter) {
            if ($format === $data_converter::get_supported_data_format()) {
                return $this->resolver->resolve($data_converter, $parameters);
            }
        }
        throw new \RuntimeException('Error, product data converter for format ' . $format . ' not found.');
    }
    private function get_all_product_data_converters() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\CsvFileConverter::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\XmlFileConverter::class];
    }
}
