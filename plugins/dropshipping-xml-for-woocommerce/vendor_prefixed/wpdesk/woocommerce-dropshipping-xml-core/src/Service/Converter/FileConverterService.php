<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Converter;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\FileConverterInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\FileConverterFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\ConverterValidatorService;
/**
 * Class FileConverterService
 * @package WPDesk\Library\DropshippingXmlCore\Service\Converter
 */
class FileConverterService
{
    /**
     * @var DataFormat
     */
    private $data_format;
    /**
     * @var FileConverterFactory
     */
    private $converter_factory;
    /**
     * @var FileConverterInterface
     */
    private $converter;
    /**
     * @var ConverterValidatorService
     */
    private $validator_service;
    /**
     * @var string
     */
    private $convert_from_format = '';
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat $data_format, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\FileConverterFactory $factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\ConverterValidatorService $validator_service)
    {
        $this->data_format = $data_format;
        $this->converter_factory = $factory;
        $this->validator_service = $validator_service;
    }
    public function convert_from_format(string $format)
    {
        $this->convert_from_format = \strtolower($format);
    }
    public function convert(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $source_file, array $parameters = array())
    {
        $format = !empty($this->convert_from_format) ? $this->convert_from_format : $this->data_format->get_format_from_mime_type($source_file->getMimeType());
        $this->converter = $this->converter_factory->create_by_data_format($format);
        $save_file_location = $this->get_converted_file_location($source_file);
        $this->converter->set_parameters($parameters);
        $this->validator_service->is_save_path_valid($save_file_location);
        return $this->converter->convert($source_file, $save_file_location);
    }
    public function get_used_converter()
    {
        if (!isset($this->converter)) {
            throw new \RuntimeException('Converter is not set, please convert data before get access to used converter.');
        }
        return $this->converter;
    }
    public function is_file_converted(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $source_file)
    {
        $converted_file_location = $this->get_converted_file_location($source_file);
        return \file_exists($converted_file_location);
    }
    public function get_converted_file_location(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $source_file)
    {
        return \trailingslashit($source_file->getDirLocation()) . $source_file->getName() . '.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML;
    }
}
