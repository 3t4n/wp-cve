<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Itn\Builder;

use WPPayVendor\BlueMedia\Itn\Dto\ItnDto;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\BlueMedia\Serializer\Serializer;
use WPPayVendor\BlueMedia\Common\Util\XMLParser;
final class ItnDtoBuilder
{
    public static function build(string $itnData) : \WPPayVendor\BlueMedia\Serializer\SerializableInterface
    {
        $serializer = new \WPPayVendor\BlueMedia\Serializer\Serializer();
        $xmlData = \WPPayVendor\BlueMedia\Common\Util\XMLParser::parse($itnData);
        $xmlTransaction = $xmlData->transactions->transaction->asXML();
        $itnDto = $serializer->deserializeXml($xmlTransaction, \WPPayVendor\BlueMedia\Itn\Dto\ItnDto::class);
        $itnDto->getItn()->setServiceID((string) $xmlData->serviceID);
        $itnDto->getItn()->setHash((string) $xmlData->hash);
        return $itnDto;
    }
}
