<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Serializer;

use WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
interface SerializerInterface
{
    public function serializeDataToDto(array $data, string $type) : \WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
    public function toArray(object $object) : array;
    public function fromArray(array $data, string $type);
    public function deserializeXml(string $xml, string $type) : \WPPayVendor\BlueMedia\Serializer\SerializableInterface;
    public function toXml($data) : string;
}
