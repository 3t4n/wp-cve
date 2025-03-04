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

namespace Tangible\ScssPhp\Ast\Sass\Statement;

use Tangible\ScssPhp\Ast\Sass\Interpolation;
use Tangible\ScssPhp\Ast\Sass\Statement;
use Tangible\ScssPhp\SourceSpan\FileSpan;
use Tangible\ScssPhp\Visitor\StatementVisitor;

/**
 * A loud CSS-style comment.
 *
 * @internal
 */
final class LoudComment implements Statement
{
    private readonly Interpolation $text;

    public function __construct(Interpolation $text)
    {
        $this->text = $text;
    }

    public function getText(): Interpolation
    {
        return $this->text;
    }

    public function getSpan(): FileSpan
    {
        return $this->text->getSpan();
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitLoudComment($this);
    }

    public function __toString(): string
    {
        return (string) $this->text;
    }
}
