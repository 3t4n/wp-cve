<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Parser;

use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\BlueMedia\Serializer\Serializer;
final class ServiceResponseParser extends \WPPayVendor\BlueMedia\Common\Parser\ResponseParser
{
    public function parseListResponse(string $type) : \WPPayVendor\BlueMedia\Serializer\SerializableInterface
    {
        $this->isErrorResponse();
        return (new \WPPayVendor\BlueMedia\Serializer\Serializer())->deserializeXml($this->response, $type);
    }
}
