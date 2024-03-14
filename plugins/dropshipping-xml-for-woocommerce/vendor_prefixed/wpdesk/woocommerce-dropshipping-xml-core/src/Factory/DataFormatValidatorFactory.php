<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat\CsvDataFormatValidator;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat\DataFormatValidator;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat\XmlDataFormatValidator;
use RuntimeException;
/**
 * Class DataFormatValidatorFactory, factory for data format validators.
 * @package WPDesk\Library\DropshippingXmlCore\Factory
 */
class DataFormatValidatorFactory
{
    /**
     * @var DependencyResolverInterface
     */
    private $resolver;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $dependency_resolver)
    {
        $this->resolver = $dependency_resolver;
    }
    public function create_validator(string $mime_type) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat\DataFormatValidator
    {
        $validators = $this->get_all_validators();
        foreach ($validators as $validator) {
            if ($validator::is_mime_type_supported($mime_type)) {
                return $this->resolver->resolve($validator);
            }
        }
        throw new \RuntimeException('File mime type ' . $mime_type . ' is not supported.');
    }
    private function get_all_validators() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat\XmlDataFormatValidator::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat\CsvDataFormatValidator::class];
    }
}
