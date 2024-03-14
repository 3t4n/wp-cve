<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Middleware\Attributes;

use Attribute;

#[Attribute]
class MapFrom
{
    /** @var string */
    public $source;

    public function __construct(string $source)
    {
        $this->source = $source;
    }
}
