<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Accessor;

use WPPayVendor\JMS\Serializer\DeserializationContext;
use WPPayVendor\JMS\Serializer\Exception\ExpressionLanguageRequiredException;
use WPPayVendor\JMS\Serializer\Exception\LogicException;
use WPPayVendor\JMS\Serializer\Exception\UninitializedPropertyException;
use WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Expression\Expression;
use WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\StaticPropertyMetadata;
use WPPayVendor\JMS\Serializer\SerializationContext;
/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class DefaultAccessorStrategy implements \WPPayVendor\JMS\Serializer\Accessor\AccessorStrategyInterface
{
    /**
     * @var callable[]
     */
    private $readAccessors = [];
    /**
     * @var callable[]
     */
    private $writeAccessors = [];
    /**
     * @var \ReflectionProperty[]
     */
    private $propertyReflectionCache = [];
    /**
     * @var ExpressionEvaluatorInterface
     */
    private $evaluator;
    public function __construct(?\WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface $evaluator = null)
    {
        $this->evaluator = $evaluator;
    }
    /**
     * {@inheritdoc}
     */
    public function getValue(object $object, \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $metadata, \WPPayVendor\JMS\Serializer\SerializationContext $context)
    {
        if ($metadata instanceof \WPPayVendor\JMS\Serializer\Metadata\StaticPropertyMetadata) {
            return $metadata->getValue();
        }
        if ($metadata instanceof \WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata) {
            if (null === $this->evaluator) {
                throw new \WPPayVendor\JMS\Serializer\Exception\ExpressionLanguageRequiredException(\sprintf('The property %s on %s requires the expression accessor strategy to be enabled.', $metadata->name, $metadata->class));
            }
            $variables = ['object' => $object, 'context' => $context, 'property_metadata' => $metadata];
            if ($metadata->expression instanceof \WPPayVendor\JMS\Serializer\Expression\Expression && $this->evaluator instanceof \WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface) {
                return $this->evaluator->evaluateParsed($metadata->expression, $variables);
            }
            return $this->evaluator->evaluate($metadata->expression, $variables);
        }
        if (null !== $metadata->getter) {
            return $object->{$metadata->getter}();
        }
        if ($metadata->forceReflectionAccess) {
            $ref = $this->propertyReflectionCache[$metadata->class][$metadata->name] ?? null;
            if (null === $ref) {
                $ref = new \ReflectionProperty($metadata->class, $metadata->name);
                $ref->setAccessible(\true);
                $this->propertyReflectionCache[$metadata->class][$metadata->name] = $ref;
            }
            return $ref->getValue($object);
        }
        $accessor = $this->readAccessors[$metadata->class] ?? null;
        if (null === $accessor) {
            $accessor = \Closure::bind(static fn($o, $name) => $o->{$name}, null, $metadata->class);
            $this->readAccessors[$metadata->class] = $accessor;
        }
        try {
            return $accessor($object, $metadata->name);
        } catch (\Error $e) {
            // handle uninitialized properties in PHP >= 7.4
            if (\preg_match('/^Typed property ([\\w\\\\@]+)::\\$(\\w+) must not be accessed before initialization$/', $e->getMessage(), $matches)) {
                throw new \WPPayVendor\JMS\Serializer\Exception\UninitializedPropertyException(\sprintf('Uninitialized property "%s::$%s".', $metadata->class, $metadata->name), 0, $e);
            }
            throw $e;
        }
    }
    /**
     * {@inheritdoc}
     */
    public function setValue(object $object, $value, \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $metadata, \WPPayVendor\JMS\Serializer\DeserializationContext $context) : void
    {
        if (\true === $metadata->readOnly) {
            throw new \WPPayVendor\JMS\Serializer\Exception\LogicException(\sprintf('%s on %s is read only.', $metadata->name, $metadata->class));
        }
        if (null !== $metadata->setter) {
            $object->{$metadata->setter}($value);
            return;
        }
        if ($metadata->forceReflectionAccess) {
            $ref = $this->propertyReflectionCache[$metadata->class][$metadata->name] ?? null;
            if (null === $ref) {
                $ref = new \ReflectionProperty($metadata->class, $metadata->name);
                $ref->setAccessible(\true);
                $this->propertyReflectionCache[$metadata->class][$metadata->name] = $ref;
            }
            $ref->setValue($object, $value);
            return;
        }
        $accessor = $this->writeAccessors[$metadata->class] ?? null;
        if (null === $accessor) {
            $accessor = \Closure::bind(static function ($o, $name, $value) : void {
                $o->{$name} = $value;
            }, null, $metadata->class);
            $this->writeAccessors[$metadata->class] = $accessor;
        }
        $accessor($object, $metadata->name, $value);
    }
}
