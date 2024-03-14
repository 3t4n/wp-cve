<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Validator\DataFormat;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
/**
 * Class DataFormatValidator, abstraction layer for data format validators.
 * @package WPDesk\Library\DropshippingXmlCore\Validator\DataFormat
 */
interface DataFormatValidator
{
    public function is_content_valid(string $content) : bool;
    public function is_file_valid(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $file) : bool;
    public static function is_mime_type_supported(string $mime_type) : bool;
}
