<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Resolver;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataFormatValidatorFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
/**
 * Class FileFormatResolverService
 * @package WPDesk\Library\DropshippingXmlCore\Service\Resolver
 */
class FileFormatResolverService
{
    /**
     * @var DataFormatValidatorFactory
     */
    private $validator_factory;
    /**
     * @var DataFormat
     */
    private $data_format;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataFormatValidatorFactory $factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat $data_format)
    {
        $this->validator_factory = $factory;
        $this->data_format = $data_format;
    }
    public function resolve_data_format(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $file)
    {
        try {
            $mime_types = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML_MIME_TYPES;
            $mime_type = \reset($mime_types);
            if (!$this->is_valid($file, $mime_type)) {
                $mime_type = $file->getMimeType();
            }
        } catch (\Exception $e) {
            $mime_type = $file->getMimeType();
        }
        return $this->data_format->get_format_from_mime_type($mime_type);
    }
    public function is_valid(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $file, string $mime_type) : bool
    {
        $validator = $this->validator_factory->create_validator($mime_type);
        return $validator->is_file_valid($file);
    }
}
