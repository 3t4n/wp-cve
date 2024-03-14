<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Util;

use WPPayVendor\BlueMedia\Common\Exception\XmlException;
use SimpleXMLElement;
final class XMLParser
{
    public static function parse($xml)
    {
        try {
            return new \SimpleXMLElement($xml);
        } catch (\Throwable $exception) {
            throw \WPPayVendor\BlueMedia\Common\Exception\XmlException::xmlParseError($exception);
        }
    }
}
