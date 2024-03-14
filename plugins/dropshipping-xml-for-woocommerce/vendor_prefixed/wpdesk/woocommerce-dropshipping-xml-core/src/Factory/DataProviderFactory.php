<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportManagerDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\SettingsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportCsvSelectorDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\DropshippingDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportSidebarDataProvider;
/**
 * Class DataProviderFactory, factory for data providers.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Factory
 */
class DataProviderFactory
{
    /**
     * @var DependencyResolverInterface
     */
    private $resolver;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $dependency_resolver)
    {
        $this->resolver = $dependency_resolver;
    }
    public function create_by_class_name(string $class_name, array $arguments = []) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\DropshippingDataProvider
    {
        $data_providers = $this->get_all_data_providers();
        foreach ($data_providers as $data_provider) {
            if ($class_name === $data_provider) {
                return $this->resolver->resolve($data_provider, $arguments);
            }
        }
        throw new \RuntimeException('Error, data provider classname ' . $class_name . ' not found.');
    }
    public function create_by_id(string $id, array $arguments = []) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\DropshippingDataProvider
    {
        $data_providers = $this->get_all_data_providers();
        foreach ($data_providers as $data_provider) {
            if ($id === $data_provider::get_id()) {
                return $this->resolver->resolve($data_provider, $arguments);
            }
        }
        throw new \RuntimeException('Error, data provider id ' . $id . ' not found.');
    }
    private function get_all_data_providers() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportManagerDataProvider::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\SettingsDataProvider::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportCsvSelectorDataProvider::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportSidebarDataProvider::class];
    }
}
