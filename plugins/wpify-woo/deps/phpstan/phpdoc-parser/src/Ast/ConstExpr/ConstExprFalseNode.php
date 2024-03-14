<?php

declare (strict_types=1);
namespace WpifyWooDeps\PHPStan\PhpDocParser\Ast\ConstExpr;

use WpifyWooDeps\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ConstExprFalseNode implements ConstExprNode
{
    use NodeAttributes;
    public function __toString() : string
    {
        return 'false';
    }
}
