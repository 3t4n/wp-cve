<?php
/**
 * @license BSD-3-Clause
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare(strict_types=1);

namespace CoffeeCode\PhpParser\Node\Expr;

use CoffeeCode\PhpParser\Node\Expr;

class Include_ extends Expr
{
    const TYPE_INCLUDE      = 1;
    const TYPE_INCLUDE_ONCE = 2;
    const TYPE_REQUIRE      = 3;
    const TYPE_REQUIRE_ONCE = 4;

    /** @var Expr Expression */
    public $expr;
    /** @var int Type of include */
    public $type;

    /**
     * Constructs an include node.
     *
     * @param Expr  $expr       Expression
     * @param int   $type       Type of include
     * @param array $attributes Additional attributes
     */
    public function __construct(Expr $expr, int $type, array $attributes = []) {
        $this->attributes = $attributes;
        $this->expr = $expr;
        $this->type = $type;
    }

    public function getSubNodeNames() : array {
        return ['expr', 'type'];
    }
    
    public function getType() : string {
        return 'Expr_Include';
    }
}
