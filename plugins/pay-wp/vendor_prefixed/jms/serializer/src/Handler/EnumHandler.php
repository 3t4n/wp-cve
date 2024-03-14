<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Handler;

use WPPayVendor\JMS\Serializer\Exception\InvalidMetadataException;
use WPPayVendor\JMS\Serializer\Exception\RuntimeException;
use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
use WPPayVendor\JMS\Serializer\SerializationContext;
use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
use WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface;
final class EnumHandler implements \WPPayVendor\JMS\Serializer\Handler\SubscribingHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];
        foreach (['json', 'xml'] as $format) {
            $methods[] = ['type' => 'enum', 'direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION, 'format' => $format, 'method' => 'deserializeEnum'];
            $methods[] = ['type' => 'enum', 'format' => $format, 'direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'method' => 'serializeEnum'];
        }
        return $methods;
    }
    public function serializeEnum(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, \UnitEnum $enum, array $type, \WPPayVendor\JMS\Serializer\SerializationContext $context)
    {
        if (isset($type['params'][1]) && 'value' === $type['params'][1] || !isset($type['params'][1]) && $enum instanceof \BackedEnum) {
            if (!$enum instanceof \BackedEnum) {
                throw new \WPPayVendor\JMS\Serializer\Exception\InvalidMetadataException(\sprintf('The type "%s" is not a backed enum, thus you can not use "value" as serialization mode for its value.', \get_class($enum)));
            }
            $valueType = isset($type['params'][2]) ? ['name' => $type['params'][2]] : null;
            return $context->getNavigator()->accept($enum->value, $valueType);
        } else {
            return $context->getNavigator()->accept($enum->name);
        }
    }
    /**
     * @param int|string|\SimpleXMLElement $data
     * @param array $type
     */
    public function deserializeEnum(\WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface $visitor, $data, array $type) : ?\UnitEnum
    {
        $enumType = $type['params'][0];
        $caseValue = (string) $data;
        $ref = new \ReflectionEnum($enumType);
        if (isset($type['params'][1]) && 'value' === $type['params'][1] || !isset($type['params'][1]) && \is_a($enumType, \BackedEnum::class, \true)) {
            if (!\is_a($enumType, \BackedEnum::class, \true)) {
                throw new \WPPayVendor\JMS\Serializer\Exception\InvalidMetadataException(\sprintf('The type "%s" is not a backed enum, thus you can not use "value" as serialization mode for its value.', $enumType));
            }
            if ('int' === $ref->getBackingType()->getName()) {
                if (!\is_numeric($caseValue)) {
                    throw new \WPPayVendor\JMS\Serializer\Exception\RuntimeException(\sprintf('"%s" is not a valid backing value for enum "%s"', $caseValue, $enumType));
                }
                $caseValue = (int) $caseValue;
            }
            return $enumType::from($caseValue);
        } else {
            if (!$ref->hasCase($caseValue)) {
                throw new \WPPayVendor\JMS\Serializer\Exception\InvalidMetadataException(\sprintf('The type "%s" does not have the case "%s"', $ref->getName(), $caseValue));
            }
            return $ref->getCase($caseValue)->getValue();
        }
    }
}
