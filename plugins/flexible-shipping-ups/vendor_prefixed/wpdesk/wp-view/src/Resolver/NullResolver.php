<?php

namespace UpsFreeVendor\WPDesk\View\Resolver;

use UpsFreeVendor\WPDesk\View\Renderer\Renderer;
use UpsFreeVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \UpsFreeVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \UpsFreeVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \UpsFreeVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Null Cannot resolve");
    }
}
