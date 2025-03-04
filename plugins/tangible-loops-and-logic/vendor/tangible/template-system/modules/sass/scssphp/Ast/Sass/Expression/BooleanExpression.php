<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace Tangible\ScssPhp\Ast\Sass\Expression;

use Tangible\ScssPhp\Ast\Sass\Expression;
use Tangible\ScssPhp\SourceSpan\FileSpan;
use Tangible\ScssPhp\Visitor\ExpressionVisitor;

/**
 * A boolean literal, `true` or `false`.
 *
 * @internal
 */
final class BooleanExpression implements Expression
{
    private readonly bool $value;

    private readonly FileSpan $span;

    public function __construct(bool $value, FileSpan $span)
    {
        $this->value = $value;
        $this->span = $span;
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        return $visitor->visitBooleanExpression($this);
    }

    public function __toString(): string
    {
        return $this->value ? 'true' : 'false';
    }
}
