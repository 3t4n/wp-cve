<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
use DOMDocument;
/**
 * Class XmlFileConverter, xml file converter.
 * @package WPDesk\Library\DropshippingXmlCore\Converter\File
 */
class XmlFileConverter implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\FileConverterInterface
{
    const DEST_FILE_ENCODING = 'UTF-8';
    const DEST_FILE_VERSION = '1.0';
    /**
     * @var string
     */
    private $encoding = '';
    /**
     * @var string
     */
    private $version = '';
    public function set_parameters(array $options)
    {
        if (isset($options['encoding'])) {
            $this->encoding = (string) $options['encoding'];
        }
        if (isset($options['version'])) {
            $this->version = (string) $options['version'];
        }
    }
    public function convert(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $source_file, string $save_location) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject
    {
        $dom = new \DOMDocument($this->get_version(), $this->get_encoding());
        $dom->formatOutput = \false;
        $dom->load($source_file->getRealPath(), \LIBXML_COMPACT | \LIBXML_PARSEHUGE);
        $dom->save($save_location, \LIBXML_NOEMPTYTAG);
        unset($dom);
        return new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject($save_location);
    }
    public static function get_supported_data_format() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML;
    }
    private function get_encoding() : string
    {
        return '' === $this->encoding ? self::DEST_FILE_ENCODING : $this->encoding;
    }
    private function get_version() : string
    {
        return '' === $this->version ? self::DEST_FILE_VERSION : $this->version;
    }
}
