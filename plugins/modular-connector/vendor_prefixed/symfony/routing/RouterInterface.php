<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Routing;

use Modular\ConnectorDependencies\Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Modular\ConnectorDependencies\Symfony\Component\Routing\Matcher\UrlMatcherInterface;
/**
 * RouterInterface is the interface that all Router classes must implement.
 *
 * This interface is the concatenation of UrlMatcherInterface and UrlGeneratorInterface.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
interface RouterInterface extends UrlMatcherInterface, UrlGeneratorInterface
{
    /**
     * Gets the RouteCollection instance associated with this Router.
     *
     * WARNING: This method should never be used at runtime as it is SLOW.
     *          You might use it in a cache warmer though.
     *
     * @return RouteCollection
     */
    public function getRouteCollection();
}
