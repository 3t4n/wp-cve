<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\Doctrine\Common\Annotations\Reader;
use WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use WPPayVendor\JMS\Serializer\Type\ParserInterface;
/**
 * @deprecated
 */
class AnnotationDriver extends \WPPayVendor\JMS\Serializer\Metadata\Driver\AnnotationOrAttributeDriver
{
    /**
     * @var Reader
     */
    private $reader;
    public function __construct(\WPPayVendor\Doctrine\Common\Annotations\Reader $reader, \WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface $namingStrategy, ?\WPPayVendor\JMS\Serializer\Type\ParserInterface $typeParser = null, ?\WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface $expressionEvaluator = null)
    {
        parent::__construct($namingStrategy, $typeParser, $expressionEvaluator, $reader);
        $this->reader = $reader;
    }
    /**
     * @return list<object>
     */
    protected function getClassAnnotations(\ReflectionClass $class) : array
    {
        return $this->reader->getClassAnnotations($class);
    }
    /**
     * @return list<object>
     */
    protected function getMethodAnnotations(\ReflectionMethod $method) : array
    {
        return $this->reader->getMethodAnnotations($method);
    }
    /**
     * @return list<object>
     */
    protected function getPropertyAnnotations(\ReflectionProperty $property) : array
    {
        return $this->reader->getPropertyAnnotations($property);
    }
}
