<?php

namespace FedExVendor\WPDesk\View\Resolver;

use FedExVendor\WPDesk\View\Renderer\Renderer;
use FedExVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \FedExVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \FedExVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \FedExVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Null Cannot resolve");
    }
}
