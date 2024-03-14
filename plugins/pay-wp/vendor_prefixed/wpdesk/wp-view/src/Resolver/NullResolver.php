<?php

namespace WPPayVendor\WPDesk\View\Resolver;

use WPPayVendor\WPDesk\View\Renderer\Renderer;
use WPPayVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \WPPayVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \WPPayVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \WPPayVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Null Cannot resolve");
    }
}
