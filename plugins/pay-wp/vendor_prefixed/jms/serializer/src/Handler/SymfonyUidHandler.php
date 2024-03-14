<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Handler;

use WPPayVendor\JMS\Serializer\DeserializationContext;
use WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException;
use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
use WPPayVendor\JMS\Serializer\SerializationContext;
use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
use WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface;
use WPPayVendor\JMS\Serializer\XmlSerializationVisitor;
use WPPayVendor\Symfony\Component\Uid\AbstractUid;
use WPPayVendor\Symfony\Component\Uid\Ulid;
use WPPayVendor\Symfony\Component\Uid\Uuid;
use WPPayVendor\Symfony\Component\Uid\UuidV1;
use WPPayVendor\Symfony\Component\Uid\UuidV3;
use WPPayVendor\Symfony\Component\Uid\UuidV4;
use WPPayVendor\Symfony\Component\Uid\UuidV5;
use WPPayVendor\Symfony\Component\Uid\UuidV6;
use WPPayVendor\Symfony\Component\Uid\UuidV7;
use WPPayVendor\Symfony\Component\Uid\UuidV8;
final class SymfonyUidHandler implements \WPPayVendor\JMS\Serializer\Handler\SubscribingHandlerInterface
{
    public const FORMAT_BASE32 = 'base32';
    public const FORMAT_BASE58 = 'base58';
    public const FORMAT_CANONICAL = 'canonical';
    public const FORMAT_RFC4122 = 'rfc4122';
    private const UID_CLASSES = [\WPPayVendor\Symfony\Component\Uid\Ulid::class, \WPPayVendor\Symfony\Component\Uid\Uuid::class, \WPPayVendor\Symfony\Component\Uid\UuidV1::class, \WPPayVendor\Symfony\Component\Uid\UuidV3::class, \WPPayVendor\Symfony\Component\Uid\UuidV4::class, \WPPayVendor\Symfony\Component\Uid\UuidV5::class, \WPPayVendor\Symfony\Component\Uid\UuidV6::class, \WPPayVendor\Symfony\Component\Uid\UuidV7::class, \WPPayVendor\Symfony\Component\Uid\UuidV8::class];
    /**
     * @var string
     * @phpstan-var self::FORMAT_*
     */
    private $defaultFormat;
    /**
     * @var bool
     */
    private $xmlCData;
    public function __construct(string $defaultFormat = self::FORMAT_CANONICAL, bool $xmlCData = \true)
    {
        $this->defaultFormat = $defaultFormat;
        $this->xmlCData = $xmlCData;
    }
    public static function getSubscribingMethods() : array
    {
        $methods = [];
        $formats = ['json', 'xml'];
        foreach ($formats as $format) {
            foreach (self::UID_CLASSES as $class) {
                $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'format' => $format, 'type' => $class, 'method' => 'serializeUid'];
                $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION, 'format' => $format, 'type' => $class, 'method' => 'deserializeUidFrom' . \ucfirst($format)];
            }
        }
        return $methods;
    }
    /**
     * @phpstan-param array{name: class-string<AbstractUid>, params: array} $type
     */
    public function deserializeUidFromJson(\WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface $visitor, ?string $data, array $type, \WPPayVendor\JMS\Serializer\DeserializationContext $context) : ?\WPPayVendor\Symfony\Component\Uid\AbstractUid
    {
        if (null === $data) {
            return null;
        }
        return $this->deserializeUid($data, $type);
    }
    /**
     * @phpstan-param array{name: class-string<AbstractUid>, params: array} $type
     */
    public function deserializeUidFromXml(\WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface $visitor, \SimpleXMLElement $data, array $type, \WPPayVendor\JMS\Serializer\DeserializationContext $context) : ?\WPPayVendor\Symfony\Component\Uid\AbstractUid
    {
        if ($this->isDataXmlNull($data)) {
            return null;
        }
        return $this->deserializeUid((string) $data, $type);
    }
    /**
     * @phpstan-param array{name: class-string<AbstractUid>, params: array} $type
     */
    private function deserializeUid(string $data, array $type) : ?\WPPayVendor\Symfony\Component\Uid\AbstractUid
    {
        /** @var class-string<AbstractUid> $uidClass */
        $uidClass = $type['name'];
        try {
            return $uidClass::fromString($data);
        } catch (\InvalidArgumentException|\TypeError $exception) {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('"%s" is not a valid UID string.', $data), 0, $exception);
        }
    }
    /**
     * @return \DOMText|string
     *
     * @phpstan-param array{name: class-string<AbstractUid>, params: array} $type
     */
    public function serializeUid(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, \WPPayVendor\Symfony\Component\Uid\AbstractUid $uid, array $type, \WPPayVendor\JMS\Serializer\SerializationContext $context)
    {
        /** @phpstan-var self::FORMAT_* $format */
        $format = $type['params'][0]['name'] ?? $this->defaultFormat;
        switch ($format) {
            case self::FORMAT_BASE32:
                $serialized = $uid->toBase32();
                break;
            case self::FORMAT_BASE58:
                $serialized = $uid->toBase58();
                break;
            case self::FORMAT_CANONICAL:
                $serialized = (string) $uid;
                break;
            case self::FORMAT_RFC4122:
                $serialized = $uid->toRfc4122();
                break;
            default:
                throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('The "%s" format is not valid.', $format));
        }
        if ($visitor instanceof \WPPayVendor\JMS\Serializer\XmlSerializationVisitor && \false === $this->xmlCData) {
            return $visitor->visitSimpleString($serialized, $type);
        }
        return $visitor->visitString($serialized, $type);
    }
    /**
     * @param mixed $data
     */
    private function isDataXmlNull($data) : bool
    {
        $attributes = $data->attributes('xsi', \true);
        return isset($attributes['nil'][0]) && 'true' === (string) $attributes['nil'][0];
    }
}
