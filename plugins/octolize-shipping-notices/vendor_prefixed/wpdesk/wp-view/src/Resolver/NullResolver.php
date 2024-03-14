<?php

namespace OctolizeShippingNoticesVendor\WPDesk\View\Resolver;

use OctolizeShippingNoticesVendor\WPDesk\View\Renderer\Renderer;
use OctolizeShippingNoticesVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \OctolizeShippingNoticesVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \OctolizeShippingNoticesVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \OctolizeShippingNoticesVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Null Cannot resolve");
    }
}
