<?php

namespace SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Source;

use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Definition;
use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Exception\DefinitionException;
/**
 * Source of definitions for entries of the container.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface DefinitionSource
{
    /**
     * Returns the DI definition for the entry name.
     *
     * @param string $name
     *
     * @throws DefinitionException An invalid definition was found.
     * @return Definition|null
     */
    public function getDefinition($name);
}
