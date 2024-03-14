<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Expression;

use WPPayVendor\Symfony\Component\ExpressionLanguage\ExpressionLanguage;
/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
class ExpressionEvaluator implements \WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface, \WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface
{
    /**
     * @var ExpressionLanguage
     */
    private $expressionLanguage;
    /**
     * @var array
     */
    private $context;
    public function __construct(\WPPayVendor\Symfony\Component\ExpressionLanguage\ExpressionLanguage $expressionLanguage, array $context = [])
    {
        $this->expressionLanguage = $expressionLanguage;
        $this->context = $context;
    }
    /**
     * @param mixed $value
     */
    public function setContextVariable(string $name, $value) : void
    {
        $this->context[$name] = $value;
    }
    /**
     * @return mixed
     */
    public function evaluate(string $expression, array $data = [])
    {
        return $this->expressionLanguage->evaluate($expression, $data + $this->context);
    }
    /**
     * @return mixed
     */
    public function evaluateParsed(\WPPayVendor\JMS\Serializer\Expression\Expression $expression, array $data = [])
    {
        return $this->expressionLanguage->evaluate($expression->getExpression(), $data + $this->context);
    }
    public function parse(string $expression, array $names = []) : \WPPayVendor\JMS\Serializer\Expression\Expression
    {
        return new \WPPayVendor\JMS\Serializer\Expression\Expression($this->expressionLanguage->parse($expression, \array_merge(\array_keys($this->context), $names)));
    }
}
