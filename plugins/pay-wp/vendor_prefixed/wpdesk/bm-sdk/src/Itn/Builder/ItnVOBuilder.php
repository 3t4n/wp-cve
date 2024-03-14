<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Itn\Builder;

use WPPayVendor\BlueMedia\Common\Util\XMLParser;
use WPPayVendor\BlueMedia\Itn\ValueObject\Itn;
use WPPayVendor\BlueMedia\Serializer\Serializer;
final class ItnVOBuilder
{
    public static function build(string $itnData) : \WPPayVendor\BlueMedia\Itn\ValueObject\Itn
    {
        $xmlData = \WPPayVendor\BlueMedia\Common\Util\XMLParser::parse($itnData);
        $xmlTransaction = $xmlData->transactions->transaction->asXML();
        return (new \WPPayVendor\BlueMedia\Serializer\Serializer())->deserializeXml($xmlTransaction, \WPPayVendor\BlueMedia\Itn\ValueObject\Itn::class);
    }
}
