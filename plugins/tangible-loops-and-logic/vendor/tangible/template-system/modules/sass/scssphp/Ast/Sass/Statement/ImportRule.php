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

use Tangible\ScssPhp\Ast\Sass\Import;
use Tangible\ScssPhp\Ast\Sass\Statement;
use Tangible\ScssPhp\SourceSpan\FileSpan;
use Tangible\ScssPhp\Visitor\StatementVisitor;

/**
 * An `@import` rule.
 *
 * @internal
 */
final class ImportRule implements Statement
{
    /**
     * @var list<Import>
     */
    private readonly array $imports;

    private readonly FileSpan $span;

    /**
     * @param list<Import> $imports
     */
    public function __construct(array $imports, FileSpan $span)
    {
        $this->imports = $imports;
        $this->span = $span;
    }

    /**
     * @return list<Import>
     */
    public function getImports(): array
    {
        return $this->imports;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitImportRule($this);
    }

    public function __toString(): string
    {
        return '@import ' . implode(', ', $this->imports) . ';';
    }
}
