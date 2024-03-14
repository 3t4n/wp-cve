<?php

namespace SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Helper;

use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\ValueDefinition;
/**
 * Helps defining a value.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ValueDefinitionHelper implements DefinitionHelper
{
    /**
     * @var mixed
     */
    private $value;
    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
    /**
     * @param string $entryName Container entry name
     * @return ValueDefinition
     */
    public function getDefinition($entryName)
    {
        return new ValueDefinition($entryName, $this->value);
    }
}
