<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
use InvalidArgumentException;
/**
 * Class DataFormat, checks if file or content is xml or csv mime type.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Data
 */
class DataFormat
{
    const FILTER_MIME_CSV = 'wpdesk_dropshipping_mime_csv';
    const FILTER_MIME_XML = 'wpdesk_dropshipping_mime_xml';
    const XML = 'xml';
    const CSV = 'csv';
    const CSV_MIME_TYPES = ['text/plain', 'text/csv', 'text/x-csv', 'application/csv', 'application/x-csv'];
    const XML_MIME_TYPES = ['text/xml', 'application/xml'];
    public function is_xml($mime_type) : bool
    {
        $mime_type = \strtolower($mime_type);
        return \in_array($mime_type, \apply_filters(self::FILTER_MIME_XML, self::XML_MIME_TYPES));
    }
    public function is_csv($mime_type) : bool
    {
        $mime_type = \strtolower($mime_type);
        return \in_array($mime_type, \apply_filters(self::FILTER_MIME_CSV, self::CSV_MIME_TYPES));
    }
    public function is_file_xml(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $file) : bool
    {
        return $this->is_xml($file->getMimeType());
    }
    public function is_file_csv(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $file) : bool
    {
        return $this->is_csv($file->getMimeType());
    }
    public function get_format_from_mime_type($mime_type) : string
    {
        if ($this->is_csv($mime_type)) {
            return self::CSV;
        }
        if ($this->is_xml($mime_type)) {
            return self::XML;
        }
        throw new \InvalidArgumentException('Undefined data format: ' . $mime_type);
    }
}
