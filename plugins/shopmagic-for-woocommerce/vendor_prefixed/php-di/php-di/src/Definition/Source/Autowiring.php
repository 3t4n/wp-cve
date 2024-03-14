<?php

declare (strict_types=1);
namespace ShopMagicVendor\DI\Definition\Source;

use ShopMagicVendor\DI\Definition\Exception\InvalidDefinition;
use ShopMagicVendor\DI\Definition\ObjectDefinition;
/**
 * Source of definitions for entries of the container.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Autowiring
{
    /**
     * Autowire the given definition.
     *
     * @throws InvalidDefinition An invalid definition was found.
     * @return ObjectDefinition|null
     */
    public function autowire(string $name, ObjectDefinition $definition = null);
}
