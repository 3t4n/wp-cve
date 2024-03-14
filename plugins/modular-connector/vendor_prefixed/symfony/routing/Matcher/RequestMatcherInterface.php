<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Routing\Matcher;

use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Request;
use Modular\ConnectorDependencies\Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Modular\ConnectorDependencies\Symfony\Component\Routing\Exception\NoConfigurationException;
use Modular\ConnectorDependencies\Symfony\Component\Routing\Exception\ResourceNotFoundException;
/**
 * RequestMatcherInterface is the interface that all request matcher classes must implement.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
interface RequestMatcherInterface
{
    /**
     * Tries to match a request with a set of routes.
     *
     * If the matcher cannot find information, it must throw one of the exceptions documented
     * below.
     *
     * @return array
     *
     * @throws NoConfigurationException  If no routing configuration could be found
     * @throws ResourceNotFoundException If no matching resource could be found
     * @throws MethodNotAllowedException If a matching resource was found but the request method is not allowed
     */
    public function matchRequest(Request $request);
}
