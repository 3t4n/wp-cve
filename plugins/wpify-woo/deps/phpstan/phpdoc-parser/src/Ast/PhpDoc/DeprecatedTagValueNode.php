<?php

declare (strict_types=1);
namespace WpifyWooDeps\PHPStan\PhpDocParser\Ast\PhpDoc;

use WpifyWooDeps\PHPStan\PhpDocParser\Ast\NodeAttributes;
class DeprecatedTagValueNode implements PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var string (may be empty) */
    public $description;
    public function __construct(string $description)
    {
        $this->description = $description;
    }
    public function __toString() : string
    {
        return \trim($this->description);
    }
}
