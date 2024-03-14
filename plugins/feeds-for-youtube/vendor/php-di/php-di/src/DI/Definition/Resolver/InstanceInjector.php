<?php

namespace SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Resolver;

use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Definition;
use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\InstanceDefinition;
use SmashBalloon\YoutubeFeed\Vendor\DI\DependencyException;
use SmashBalloon\YoutubeFeed\Vendor\Interop\Container\Exception\NotFoundException;
/**
 * Injects dependencies on an existing instance.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InstanceInjector extends ObjectCreator
{
    /**
     * Injects dependencies on an existing instance.
     *
     * @param InstanceDefinition $definition
     *
     * {@inheritdoc}
     */
    public function resolve(Definition $definition, array $parameters = [])
    {
        try {
            $this->injectMethodsAndProperties($definition->getInstance(), $definition->getObjectDefinition());
        } catch (NotFoundException $e) {
            $message = \sprintf('Error while injecting dependencies into %s: %s', \get_class($definition->getInstance()), $e->getMessage());
            throw new DependencyException($message, 0, $e);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function isResolvable(Definition $definition, array $parameters = [])
    {
        return \true;
    }
}
