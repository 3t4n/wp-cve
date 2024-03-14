<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Serializer;

use WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
use WPPayVendor\JMS\Serializer\SerializerBuilder;
use WPPayVendor\JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use WPPayVendor\JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
final class Serializer implements \WPPayVendor\BlueMedia\Serializer\SerializerInterface
{
    private const XML_TYPE = 'xml';
    private $serializer;
    public function __construct()
    {
        $this->serializer = \WPPayVendor\JMS\Serializer\SerializerBuilder::create()->setPropertyNamingStrategy(new \WPPayVendor\JMS\Serializer\Naming\SerializedNameAnnotationStrategy(new \WPPayVendor\JMS\Serializer\Naming\IdenticalPropertyNamingStrategy()))->build();
    }
    public function serializeDataToDto(array $data, string $type) : \WPPayVendor\BlueMedia\Common\Dto\AbstractDto
    {
        return $this->serializer->fromArray($data, $type);
    }
    public function toArray(object $object) : array
    {
        return $this->serializer->toArray($object);
    }
    public function fromArray(array $data, string $type)
    {
        return $this->serializer->fromArray($data, $type);
    }
    public function deserializeXml(string $xml, string $type) : \WPPayVendor\BlueMedia\Serializer\SerializableInterface
    {
        return $this->serializer->deserialize($xml, $type, self::XML_TYPE);
    }
    public function toXml($data) : string
    {
        return $this->serializer->serialize($data, self::XML_TYPE);
    }
}
