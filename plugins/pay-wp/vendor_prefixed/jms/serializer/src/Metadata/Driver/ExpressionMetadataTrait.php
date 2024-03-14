<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\JMS\Serializer\Exception\InvalidMetadataException;
use WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Expression\Expression;
trait ExpressionMetadataTrait
{
    /**
     * @var CompilableExpressionEvaluatorInterface
     */
    private $expressionEvaluator;
    /**
     * @return Expression|string
     *
     * @throws InvalidMetadataException
     */
    private function parseExpression(string $expression, array $names = [])
    {
        if (null === $this->expressionEvaluator) {
            return $expression;
        }
        try {
            return $this->expressionEvaluator->parse($expression, \array_merge(['context', 'property_metadata', 'object'], $names));
        } catch (\LogicException $e) {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidMetadataException(\sprintf('Can not parse the expression "%s"', $expression), 0, $e);
        }
    }
}
