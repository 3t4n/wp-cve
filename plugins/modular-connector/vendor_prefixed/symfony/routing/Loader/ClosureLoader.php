<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Routing\Loader;

use Modular\ConnectorDependencies\Symfony\Component\Config\Loader\Loader;
use Modular\ConnectorDependencies\Symfony\Component\Routing\RouteCollection;
/**
 * ClosureLoader loads routes from a PHP closure.
 *
 * The Closure must return a RouteCollection instance.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
class ClosureLoader extends Loader
{
    /**
     * Loads a Closure.
     *
     * @param \Closure    $closure A Closure
     * @param string|null $type    The resource type
     *
     * @return RouteCollection
     */
    public function load($closure, ?string $type = null)
    {
        return $closure($this->env);
    }
    /**
     * {@inheritdoc}
     */
    public function supports($resource, ?string $type = null)
    {
        return $resource instanceof \Closure && (!$type || 'closure' === $type);
    }
}
