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

namespace Tangible\ScssPhp\Serializer;

/**
 * The result of converting a CSS AST to CSS text.
 *
 * @internal
 */
final class SerializeResult
{
    private readonly string $css;

    public function __construct(string $css)
    {
        $this->css = $css;
    }

    public function getCss(): string
    {
        return $this->css;
    }
}
