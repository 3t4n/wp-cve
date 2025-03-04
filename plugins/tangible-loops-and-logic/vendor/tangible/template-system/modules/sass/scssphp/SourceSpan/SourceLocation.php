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

namespace Tangible\ScssPhp\SourceSpan;

/**
 * @internal
 */
final class SourceLocation
{
    private readonly SourceFile $file;

    private readonly int $offset;

    public function __construct(SourceFile $file, int $offset)
    {
        $this->file = $file;
        $this->offset = $offset;
    }

    public function getFile(): SourceFile
    {
        return $this->file;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * The 0-based line of that location
     */
    public function getLine(): int
    {
        return $this->file->getLine($this->offset);
    }

    /**
     * The 0-based column of that location
     */
    public function getColumn(): int
    {
        return $this->file->getColumn($this->offset);
    }

    public function getSourceUrl(): ?string
    {
        return $this->file->getSourceUrl();
    }

    /**
     * Returns a span that covers only a single point: this location.
     */
    public function pointSpan(): FileSpan
    {
        return new ConcreteFileSpan($this->file, $this->offset, $this->offset);
    }
}
