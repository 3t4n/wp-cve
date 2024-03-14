<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer;

use WPPayVendor\JMS\Serializer\ContextFactory\DefaultDeserializationContextFactory;
use WPPayVendor\JMS\Serializer\ContextFactory\DefaultSerializationContextFactory;
use WPPayVendor\JMS\Serializer\ContextFactory\DeserializationContextFactoryInterface;
use WPPayVendor\JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException;
use WPPayVendor\JMS\Serializer\Exception\RuntimeException;
use WPPayVendor\JMS\Serializer\Exception\UnsupportedFormatException;
use WPPayVendor\JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface;
use WPPayVendor\JMS\Serializer\Type\Parser;
use WPPayVendor\JMS\Serializer\Type\ParserInterface;
use WPPayVendor\JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory;
use WPPayVendor\JMS\Serializer\Visitor\Factory\SerializationVisitorFactory;
use WPPayVendor\Metadata\MetadataFactoryInterface;
/**
 * Serializer Implementation.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
final class Serializer implements \WPPayVendor\JMS\Serializer\SerializerInterface, \WPPayVendor\JMS\Serializer\ArrayTransformerInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $factory;
    /**
     * @var ParserInterface
     */
    private $typeParser;
    /**
     * @var SerializationVisitorFactory[]
     */
    private $serializationVisitors;
    /**
     * @var DeserializationVisitorFactory[]
     */
    private $deserializationVisitors;
    /**
     * @var SerializationContextFactoryInterface
     */
    private $serializationContextFactory;
    /**
     * @var DeserializationContextFactoryInterface
     */
    private $deserializationContextFactory;
    /**
     * @var GraphNavigatorFactoryInterface[]
     */
    private $graphNavigators;
    /**
     * @param GraphNavigatorFactoryInterface[] $graphNavigators
     * @param SerializationVisitorFactory[] $serializationVisitors
     * @param DeserializationVisitorFactory[] $deserializationVisitors
     */
    public function __construct(\WPPayVendor\Metadata\MetadataFactoryInterface $factory, array $graphNavigators, array $serializationVisitors, array $deserializationVisitors, ?\WPPayVendor\JMS\Serializer\ContextFactory\SerializationContextFactoryInterface $serializationContextFactory = null, ?\WPPayVendor\JMS\Serializer\ContextFactory\DeserializationContextFactoryInterface $deserializationContextFactory = null, ?\WPPayVendor\JMS\Serializer\Type\ParserInterface $typeParser = null)
    {
        $this->factory = $factory;
        $this->graphNavigators = $graphNavigators;
        $this->serializationVisitors = $serializationVisitors;
        $this->deserializationVisitors = $deserializationVisitors;
        $this->typeParser = $typeParser ?? new \WPPayVendor\JMS\Serializer\Type\Parser();
        $this->serializationContextFactory = $serializationContextFactory ?: new \WPPayVendor\JMS\Serializer\ContextFactory\DefaultSerializationContextFactory();
        $this->deserializationContextFactory = $deserializationContextFactory ?: new \WPPayVendor\JMS\Serializer\ContextFactory\DefaultDeserializationContextFactory();
    }
    /**
     * Parses a direction string to one of the direction constants.
     */
    public static function parseDirection(string $dirStr) : int
    {
        switch (\strtolower($dirStr)) {
            case 'serialization':
                return \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION;
            case 'deserialization':
                return \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION;
            default:
                throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('The direction "%s" does not exist.', $dirStr));
        }
    }
    private function findInitialType(?string $type, \WPPayVendor\JMS\Serializer\SerializationContext $context) : ?string
    {
        if (null !== $type) {
            return $type;
        } elseif ($context->hasAttribute('initial_type')) {
            return $context->getAttribute('initial_type');
        }
        return null;
    }
    private function getNavigator(int $direction) : \WPPayVendor\JMS\Serializer\GraphNavigatorInterface
    {
        if (!isset($this->graphNavigators[$direction])) {
            throw new \WPPayVendor\JMS\Serializer\Exception\RuntimeException(\sprintf('Can not find a graph navigator for the direction "%s".', \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION === $direction ? 'serialization' : 'deserialization'));
        }
        return $this->graphNavigators[$direction]->getGraphNavigator();
    }
    private function getVisitor(int $direction, string $format) : \WPPayVendor\JMS\Serializer\VisitorInterface
    {
        $factories = \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION === $direction ? $this->serializationVisitors : $this->deserializationVisitors;
        if (!isset($factories[$format])) {
            throw new \WPPayVendor\JMS\Serializer\Exception\UnsupportedFormatException(\sprintf('The format "%s" is not supported for %s.', $format, \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION === $direction ? 'serialization' : 'deserialization'));
        }
        return $factories[$format]->getVisitor();
    }
    /**
     * {@InheritDoc}
     */
    public function serialize($data, string $format, ?\WPPayVendor\JMS\Serializer\SerializationContext $context = null, ?string $type = null) : string
    {
        if (null === $context) {
            $context = $this->serializationContextFactory->createSerializationContext();
        }
        $visitor = $this->getVisitor(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, $format);
        $navigator = $this->getNavigator(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION);
        $type = $this->findInitialType($type, $context);
        $result = $this->visit($navigator, $visitor, $context, $data, $format, $type);
        $context->close();
        return $visitor->getResult($result);
    }
    /**
     * {@InheritDoc}
     */
    public function deserialize(string $data, string $type, string $format, ?\WPPayVendor\JMS\Serializer\DeserializationContext $context = null)
    {
        if (null === $context) {
            $context = $this->deserializationContextFactory->createDeserializationContext();
        }
        $visitor = $this->getVisitor(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION, $format);
        $navigator = $this->getNavigator(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION);
        $result = $this->visit($navigator, $visitor, $context, $data, $format, $type);
        $context->close();
        return $visitor->getResult($result);
    }
    /**
     * {@InheritDoc}
     */
    public function toArray($data, ?\WPPayVendor\JMS\Serializer\SerializationContext $context = null, ?string $type = null) : array
    {
        if (null === $context) {
            $context = $this->serializationContextFactory->createSerializationContext();
        }
        $visitor = $this->getVisitor(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'json');
        $navigator = $this->getNavigator(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION);
        $type = $this->findInitialType($type, $context);
        $result = $this->visit($navigator, $visitor, $context, $data, 'json', $type);
        $result = $this->convertArrayObjects($result);
        if (!\is_array($result)) {
            throw new \WPPayVendor\JMS\Serializer\Exception\RuntimeException(\sprintf('The input data of type "%s" did not convert to an array, but got a result of type "%s".', \is_object($data) ? \get_class($data) : \gettype($data), \is_object($result) ? \get_class($result) : \gettype($result)));
        }
        return $result;
    }
    /**
     * {@InheritDoc}
     */
    public function fromArray(array $data, string $type, ?\WPPayVendor\JMS\Serializer\DeserializationContext $context = null)
    {
        if (null === $context) {
            $context = $this->deserializationContextFactory->createDeserializationContext();
        }
        $visitor = $this->getVisitor(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION, 'json');
        $navigator = $this->getNavigator(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION);
        return $this->visit($navigator, $visitor, $context, $data, 'json', $type, \false);
    }
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    private function visit(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface $navigator, \WPPayVendor\JMS\Serializer\VisitorInterface $visitor, \WPPayVendor\JMS\Serializer\Context $context, $data, string $format, ?string $type = null, bool $prepare = \true)
    {
        $context->initialize($format, $visitor, $navigator, $this->factory);
        $visitor->setNavigator($navigator);
        $navigator->initialize($visitor, $context);
        if ($prepare) {
            $data = $visitor->prepare($data);
        }
        if (null !== $type) {
            $type = $this->typeParser->parse($type);
        }
        return $navigator->accept($data, $type);
    }
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    private function convertArrayObjects($data)
    {
        if ($data instanceof \ArrayObject || $data instanceof \stdClass) {
            $data = (array) $data;
        }
        if (\is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->convertArrayObjects($v);
            }
        }
        return $data;
    }
}
