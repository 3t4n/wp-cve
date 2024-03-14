<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Routing\Matcher\Dumper;

use Modular\ConnectorDependencies\Symfony\Component\Routing\RouteCollection;
/**
 * MatcherDumperInterface is the interface that all matcher dumper classes must implement.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
interface MatcherDumperInterface
{
    /**
     * Dumps a set of routes to a string representation of executable code
     * that can then be used to match a request against these routes.
     *
     * @return string
     */
    public function dump(array $options = []);
    /**
     * Gets the routes to dump.
     *
     * @return RouteCollection
     */
    public function getRoutes();
}
