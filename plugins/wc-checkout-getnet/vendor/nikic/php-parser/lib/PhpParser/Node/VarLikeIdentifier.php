<?php
/**
 * @license BSD-3-Clause
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare(strict_types=1);

namespace CoffeeCode\PhpParser\Node;

/**
 * Represents a name that is written in source code with a leading dollar,
 * but is not a proper variable. The leading dollar is not stored as part of the name.
 *
 * Examples: Names in property declarations are formatted as variables. Names in static property
 * lookups are also formatted as variables.
 */
class VarLikeIdentifier extends Identifier
{
    public function getType() : string {
        return 'VarLikeIdentifier';
    }
}
