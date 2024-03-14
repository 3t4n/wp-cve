<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
use DOMDocument;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat\DataFormatValidator;
/**
 * Class XmlDataFormatValidator, xml data format validator.
 * @package WPDesk\Library\DropshippingXmlCore\Validator\DataFormat
 */
class XmlDataFormatValidator implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat\DataFormatValidator
{
    public function is_content_valid(string $content) : bool
    {
        \libxml_use_internal_errors(\true);
        $dom = new \DOMDocument();
        $dom->loadXML($content, \LIBXML_COMPACT | \LIBXML_PARSEHUGE);
        $errors = \libxml_get_errors();
        unset($dom);
        $this->throw_exception_if_error($errors);
        return \true;
    }
    public function is_file_valid(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $file) : bool
    {
        \libxml_use_internal_errors(\true);
        $dom = new \DOMDocument();
        $dom->load($file->getRealPath(), \LIBXML_COMPACT | \LIBXML_PARSEHUGE);
        $errors = \libxml_get_errors();
        unset($dom);
        $this->throw_exception_if_error($errors);
        return \true;
    }
    /**
     * @param array $array
     *
     * @throws RuntimeException
     * @return void
     */
    private function throw_exception_if_error(array $array)
    {
        if (!empty($array)) {
            $val = \array_values($array);
            $error = \array_shift($val);
            throw new \RuntimeException('XML validator error - ' . $error->message);
        }
    }
    public static function is_mime_type_supported(string $mime_type) : bool
    {
        return (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat())->is_xml($mime_type);
    }
}
