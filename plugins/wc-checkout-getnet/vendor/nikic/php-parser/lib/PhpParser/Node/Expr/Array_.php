<?php
/**
 * @license BSD-3-Clause
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare(strict_types=1);

namespace CoffeeCode\PhpParser\Node\Expr;

use CoffeeCode\PhpParser\Node\Expr;

class Array_ extends Expr
{
    // For use in "kind" attribute
    const KIND_LONG = 1;  // array() syntax
    const KIND_SHORT = 2; // [] syntax

    /** @var (ArrayItem|null)[] Items */
    public $items;

    /**
     * Constructs an array node.
     *
     * @param (ArrayItem|null)[] $items      Items of the array
     * @param array       $attributes Additional attributes
     */
    public function __construct(array $items = [], array $attributes = []) {
        $this->attributes = $attributes;
        $this->items = $items;
    }

    public function getSubNodeNames() : array {
        return ['items'];
    }
    
    public function getType() : string {
        return 'Expr_Array';
    }
}
