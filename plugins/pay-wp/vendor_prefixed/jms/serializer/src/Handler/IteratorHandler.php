<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Handler;

use ArrayIterator;
use Generator;
use Iterator;
use WPPayVendor\JMS\Serializer\DeserializationContext;
use WPPayVendor\JMS\Serializer\Functions;
use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
use WPPayVendor\JMS\Serializer\SerializationContext;
use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
use WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface;
final class IteratorHandler implements \WPPayVendor\JMS\Serializer\Handler\SubscribingHandlerInterface
{
    private const SUPPORTED_FORMATS = ['json', 'xml'];
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];
        foreach (self::SUPPORTED_FORMATS as $format) {
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'type' => \Iterator::class, 'format' => $format, 'method' => 'serializeIterable'];
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION, 'type' => \Iterator::class, 'format' => $format, 'method' => 'deserializeIterator'];
        }
        foreach (self::SUPPORTED_FORMATS as $format) {
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'type' => \ArrayIterator::class, 'format' => $format, 'method' => 'serializeIterable'];
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION, 'type' => \ArrayIterator::class, 'format' => $format, 'method' => 'deserializeIterator'];
        }
        foreach (self::SUPPORTED_FORMATS as $format) {
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'type' => \Generator::class, 'format' => $format, 'method' => 'serializeIterable'];
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION, 'type' => \Generator::class, 'format' => $format, 'method' => 'deserializeGenerator'];
        }
        return $methods;
    }
    /**
     * @return array|\ArrayObject|null
     */
    public function serializeIterable(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, iterable $iterable, array $type, \WPPayVendor\JMS\Serializer\SerializationContext $context) : ?iterable
    {
        $type['name'] = 'array';
        $context->stopVisiting($iterable);
        $result = $visitor->visitArray(\WPPayVendor\JMS\Serializer\Functions::iterableToArray($iterable), $type);
        $context->startVisiting($iterable);
        return $result;
    }
    /**
     * @param mixed $data
     */
    public function deserializeIterator(\WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface $visitor, $data, array $type, \WPPayVendor\JMS\Serializer\DeserializationContext $context) : \Iterator
    {
        $type['name'] = 'array';
        return new \ArrayIterator($visitor->visitArray($data, $type));
    }
    /**
     * @param mixed $data
     */
    public function deserializeGenerator(\WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface $visitor, $data, array $type, \WPPayVendor\JMS\Serializer\DeserializationContext $context) : \Generator
    {
        return (static function () use(&$visitor, &$data, &$type) : Generator {
            $type['name'] = 'array';
            yield from $visitor->visitArray($data, $type);
        })();
    }
}
