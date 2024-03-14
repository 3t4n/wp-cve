<?php

namespace DropshippingXmlFreeVendor\WPDesk\View\Resolver;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \DropshippingXmlFreeVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \DropshippingXmlFreeVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Null Cannot resolve");
    }
}
