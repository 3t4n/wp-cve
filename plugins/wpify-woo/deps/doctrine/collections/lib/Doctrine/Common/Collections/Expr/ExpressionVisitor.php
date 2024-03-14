<?php

namespace WpifyWooDeps\Doctrine\Common\Collections\Expr;

use RuntimeException;
use function get_class;
/**
 * An Expression visitor walks a graph of expressions and turns them into a
 * query for the underlying implementation.
 */
abstract class ExpressionVisitor
{
    /**
     * Converts a comparison expression into the target query language output.
     *
     * @return mixed
     */
    public abstract function walkComparison(Comparison $comparison);
    /**
     * Converts a value expression into the target query language part.
     *
     * @return mixed
     */
    public abstract function walkValue(Value $value);
    /**
     * Converts a composite expression into the target query language output.
     *
     * @return mixed
     */
    public abstract function walkCompositeExpression(CompositeExpression $expr);
    /**
     * Dispatches walking an expression to the appropriate handler.
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public function dispatch(Expression $expr)
    {
        switch (\true) {
            case $expr instanceof Comparison:
                return $this->walkComparison($expr);
            case $expr instanceof Value:
                return $this->walkValue($expr);
            case $expr instanceof CompositeExpression:
                return $this->walkCompositeExpression($expr);
            default:
                throw new RuntimeException('Unknown Expression ' . get_class($expr));
        }
    }
}
