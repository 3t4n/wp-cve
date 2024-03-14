<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Exclusion;

use WPPayVendor\JMS\Serializer\Context;
use WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Expression\Expression;
use WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\JMS\Serializer\SerializationContext;
/**
 * Exposes an exclusion strategy based on the Symfony's expression language.
 * This is not a standard exclusion strategy and can not be used in user applications.
 *
 * @internal
 *
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class ExpressionLanguageExclusionStrategy
{
    /**
     * @var ExpressionEvaluatorInterface
     */
    private $expressionEvaluator;
    public function __construct(\WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface $expressionEvaluator)
    {
        $this->expressionEvaluator = $expressionEvaluator;
    }
    public function shouldSkipClass(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $class, \WPPayVendor\JMS\Serializer\Context $navigatorContext) : bool
    {
        if (null === $class->excludeIf) {
            return \false;
        }
        $variables = ['context' => $navigatorContext, 'class_metadata' => $class];
        if ($navigatorContext instanceof \WPPayVendor\JMS\Serializer\SerializationContext) {
            $variables['object'] = $navigatorContext->getObject();
        } else {
            $variables['object'] = null;
        }
        if ($class->excludeIf instanceof \WPPayVendor\JMS\Serializer\Expression\Expression && $this->expressionEvaluator instanceof \WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface) {
            return $this->expressionEvaluator->evaluateParsed($class->excludeIf, $variables);
        }
        return $this->expressionEvaluator->evaluate($class->excludeIf, $variables);
    }
    public function shouldSkipProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $property, \WPPayVendor\JMS\Serializer\Context $navigatorContext) : bool
    {
        if (null === $property->excludeIf) {
            return \false;
        }
        $variables = ['context' => $navigatorContext, 'property_metadata' => $property];
        if ($navigatorContext instanceof \WPPayVendor\JMS\Serializer\SerializationContext) {
            $variables['object'] = $navigatorContext->getObject();
        } else {
            $variables['object'] = null;
        }
        if ($property->excludeIf instanceof \WPPayVendor\JMS\Serializer\Expression\Expression && $this->expressionEvaluator instanceof \WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface) {
            return $this->expressionEvaluator->evaluateParsed($property->excludeIf, $variables);
        }
        return $this->expressionEvaluator->evaluate($property->excludeIf, $variables);
    }
}
