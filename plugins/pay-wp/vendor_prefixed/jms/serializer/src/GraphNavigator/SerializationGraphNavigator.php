<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\GraphNavigator;

use WPPayVendor\JMS\Serializer\Accessor\AccessorStrategyInterface;
use WPPayVendor\JMS\Serializer\Context;
use WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcher;
use WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use WPPayVendor\JMS\Serializer\EventDispatcher\ObjectEvent;
use WPPayVendor\JMS\Serializer\EventDispatcher\PreSerializeEvent;
use WPPayVendor\JMS\Serializer\Exception\CircularReferenceDetectedException;
use WPPayVendor\JMS\Serializer\Exception\ExcludedClassException;
use WPPayVendor\JMS\Serializer\Exception\ExpressionLanguageRequiredException;
use WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException;
use WPPayVendor\JMS\Serializer\Exception\NotAcceptableException;
use WPPayVendor\JMS\Serializer\Exception\RuntimeException;
use WPPayVendor\JMS\Serializer\Exception\SkipHandlerException;
use WPPayVendor\JMS\Serializer\Exception\UninitializedPropertyException;
use WPPayVendor\JMS\Serializer\Exclusion\ExpressionLanguageExclusionStrategy;
use WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Functions;
use WPPayVendor\JMS\Serializer\GraphNavigator;
use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
use WPPayVendor\JMS\Serializer\Handler\HandlerRegistryInterface;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\NullAwareVisitorInterface;
use WPPayVendor\JMS\Serializer\SerializationContext;
use WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface;
use WPPayVendor\JMS\Serializer\VisitorInterface;
use WPPayVendor\Metadata\MetadataFactoryInterface;
use function assert;
/**
 * Handles traversal along the object graph.
 *
 * This class handles traversal along the graph, and calls different methods
 * on visitors, or custom handlers to process its nodes.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
final class SerializationGraphNavigator extends \WPPayVendor\JMS\Serializer\GraphNavigator
{
    /**
     * @var SerializationVisitorInterface
     */
    protected $visitor;
    /**
     * @var SerializationContext
     */
    protected $context;
    /**
     * @var ExpressionLanguageExclusionStrategy
     */
    private $expressionExclusionStrategy;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;
    /**
     * @var HandlerRegistryInterface
     */
    private $handlerRegistry;
    /**
     * @var AccessorStrategyInterface
     */
    private $accessor;
    /**
     * @var bool
     */
    private $shouldSerializeNull;
    public function __construct(\WPPayVendor\Metadata\MetadataFactoryInterface $metadataFactory, \WPPayVendor\JMS\Serializer\Handler\HandlerRegistryInterface $handlerRegistry, \WPPayVendor\JMS\Serializer\Accessor\AccessorStrategyInterface $accessor, ?\WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcherInterface $dispatcher = null, ?\WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface $expressionEvaluator = null)
    {
        $this->dispatcher = $dispatcher ?: new \WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcher();
        $this->metadataFactory = $metadataFactory;
        $this->handlerRegistry = $handlerRegistry;
        $this->accessor = $accessor;
        if ($expressionEvaluator) {
            $this->expressionExclusionStrategy = new \WPPayVendor\JMS\Serializer\Exclusion\ExpressionLanguageExclusionStrategy($expressionEvaluator);
        }
    }
    public function initialize(\WPPayVendor\JMS\Serializer\VisitorInterface $visitor, \WPPayVendor\JMS\Serializer\Context $context) : void
    {
        \assert($context instanceof \WPPayVendor\JMS\Serializer\SerializationContext);
        parent::initialize($visitor, $context);
        $this->shouldSerializeNull = $context->shouldSerializeNull();
    }
    /**
     * Called for each node of the graph that is being traversed.
     *
     * @param mixed $data the data depends on the direction, and type of visitor
     * @param array|null $type array has the format ["name" => string, "params" => array]
     *
     * @return mixed the return value depends on the direction, and type of visitor
     */
    public function accept($data, ?array $type = null)
    {
        // If the type was not given, we infer the most specific type from the
        // input data in serialization mode.
        if (null === $type) {
            $typeName = \gettype($data);
            if ('object' === $typeName) {
                $typeName = \get_class($data);
            }
            $type = ['name' => $typeName, 'params' => []];
        } elseif (null === $data) {
            // If the data is null, we have to force the type to null regardless of the input in order to
            // guarantee correct handling of null values, and not have any internal auto-casting behavior.
            $type = ['name' => 'NULL', 'params' => []];
        }
        // Sometimes data can convey null but is not of a null type.
        // Visitors can have the power to add this custom null evaluation
        if ($this->visitor instanceof \WPPayVendor\JMS\Serializer\NullAwareVisitorInterface && \true === $this->visitor->isNull($data)) {
            $type = ['name' => 'NULL', 'params' => []];
        }
        switch ($type['name']) {
            case 'NULL':
                if (!$this->shouldSerializeNull && !$this->isRootNullAllowed()) {
                    throw new \WPPayVendor\JMS\Serializer\Exception\NotAcceptableException();
                }
                return $this->visitor->visitNull($data, $type);
            case 'string':
                return $this->visitor->visitString((string) $data, $type);
            case 'int':
            case 'integer':
                return $this->visitor->visitInteger((int) $data, $type);
            case 'bool':
            case 'boolean':
                return $this->visitor->visitBoolean((bool) $data, $type);
            case 'double':
            case 'float':
                return $this->visitor->visitDouble((float) $data, $type);
            case 'iterable':
                return $this->visitor->visitArray(\WPPayVendor\JMS\Serializer\Functions::iterableToArray($data), $type);
            case 'array':
            case 'list':
                return $this->visitor->visitArray((array) $data, $type);
            case 'resource':
                $msg = 'Resources are not supported in serialized data.';
                if (null !== ($path = $this->context->getPath())) {
                    $msg .= ' Path: ' . $path;
                }
                throw new \WPPayVendor\JMS\Serializer\Exception\RuntimeException($msg);
            default:
                if (null !== $data) {
                    if ($this->context->isVisiting($data)) {
                        throw new \WPPayVendor\JMS\Serializer\Exception\CircularReferenceDetectedException();
                    }
                    $this->context->startVisiting($data);
                }
                // If we're serializing a polymorphic type, then we'll be interested in the
                // metadata for the actual type of the object, not the base class.
                if (\class_exists($type['name'], \false) || \interface_exists($type['name'], \false)) {
                    if (\is_subclass_of($data, $type['name'], \false) && null === $this->handlerRegistry->getHandler(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, $type['name'], $this->format)) {
                        $type = ['name' => \get_class($data), 'params' => $type['params'] ?? []];
                    }
                }
                // Trigger pre-serialization callbacks, and listeners if they exist.
                // Dispatch pre-serialization event before handling data to have ability change type in listener
                if ($this->dispatcher->hasListeners('serializer.pre_serialize', $type['name'], $this->format)) {
                    $this->dispatcher->dispatch('serializer.pre_serialize', $type['name'], $this->format, $event = new \WPPayVendor\JMS\Serializer\EventDispatcher\PreSerializeEvent($this->context, $data, $type));
                    $type = $event->getType();
                }
                // First, try whether a custom handler exists for the given type. This is done
                // before loading metadata because the type name might not be a class, but
                // could also simply be an artifical type.
                if (null !== ($handler = $this->handlerRegistry->getHandler(\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, $type['name'], $this->format))) {
                    try {
                        $rs = \call_user_func($handler, $this->visitor, $data, $type, $this->context);
                        $this->context->stopVisiting($data);
                        return $rs;
                    } catch (\WPPayVendor\JMS\Serializer\Exception\SkipHandlerException $e) {
                        // Skip handler, fallback to default behavior
                    } catch (\WPPayVendor\JMS\Serializer\Exception\NotAcceptableException $e) {
                        $this->context->stopVisiting($data);
                        throw $e;
                    }
                }
                $metadata = $this->metadataFactory->getMetadataForClass($type['name']);
                \assert($metadata instanceof \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata);
                if ($metadata->usingExpression && null === $this->expressionExclusionStrategy) {
                    throw new \WPPayVendor\JMS\Serializer\Exception\ExpressionLanguageRequiredException(\sprintf('To use conditional exclude/expose in %s you must configure the expression language.', $metadata->name));
                }
                if (null !== $this->exclusionStrategy && $this->exclusionStrategy->shouldSkipClass($metadata, $this->context)) {
                    $this->context->stopVisiting($data);
                    throw new \WPPayVendor\JMS\Serializer\Exception\ExcludedClassException();
                }
                if (null !== $this->expressionExclusionStrategy && $this->expressionExclusionStrategy->shouldSkipClass($metadata, $this->context)) {
                    $this->context->stopVisiting($data);
                    throw new \WPPayVendor\JMS\Serializer\Exception\ExcludedClassException();
                }
                if (!\is_object($data)) {
                    throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException('Value at ' . $this->context->getPath() . ' is expected to be an object of class ' . $type['name'] . ' but is of type ' . \gettype($data));
                }
                $this->context->pushClassMetadata($metadata);
                foreach ($metadata->preSerializeMethods as $method) {
                    $method->invoke($data);
                }
                $this->visitor->startVisitingObject($metadata, $data, $type);
                foreach ($metadata->propertyMetadata as $propertyMetadata) {
                    if (null !== $this->exclusionStrategy && $this->exclusionStrategy->shouldSkipProperty($propertyMetadata, $this->context)) {
                        continue;
                    }
                    if (null !== $this->expressionExclusionStrategy && $this->expressionExclusionStrategy->shouldSkipProperty($propertyMetadata, $this->context)) {
                        continue;
                    }
                    try {
                        $v = $this->accessor->getValue($data, $propertyMetadata, $this->context);
                    } catch (\WPPayVendor\JMS\Serializer\Exception\UninitializedPropertyException $e) {
                        continue;
                    }
                    if (null === $v && \true !== $this->shouldSerializeNull) {
                        continue;
                    }
                    $this->context->pushPropertyMetadata($propertyMetadata);
                    $this->visitor->visitProperty($propertyMetadata, $v);
                    $this->context->popPropertyMetadata();
                }
                $this->afterVisitingObject($metadata, $data, $type);
                return $this->visitor->endVisitingObject($metadata, $data, $type);
        }
    }
    private function isRootNullAllowed() : bool
    {
        return $this->context->hasAttribute('allows_root_null') && $this->context->getAttribute('allows_root_null') && 0 === $this->context->getVisitingSet()->count();
    }
    private function afterVisitingObject(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, object $object, array $type) : void
    {
        $this->context->stopVisiting($object);
        $this->context->popClassMetadata();
        foreach ($metadata->postSerializeMethods as $method) {
            $method->invoke($object);
        }
        if ($this->dispatcher->hasListeners('serializer.post_serialize', $metadata->name, $this->format)) {
            $this->dispatcher->dispatch('serializer.post_serialize', $metadata->name, $this->format, new \WPPayVendor\JMS\Serializer\EventDispatcher\ObjectEvent($this->context, $object, $type));
        }
    }
}
