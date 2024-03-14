<?php

declare (strict_types=1);
namespace WpifyWooDeps\PHPStan\PhpDocParser\Ast\PhpDoc;

use WpifyWooDeps\PHPStan\PhpDocParser\Ast\NodeAttributes;
use WpifyWooDeps\PHPStan\PhpDocParser\Ast\Type\TypeNode;
class TypeAliasTagValueNode implements PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var string */
    public $alias;
    /** @var TypeNode */
    public $type;
    public function __construct(string $alias, TypeNode $type)
    {
        $this->alias = $alias;
        $this->type = $type;
    }
    public function __toString() : string
    {
        return \trim("{$this->alias} {$this->type}");
    }
}
