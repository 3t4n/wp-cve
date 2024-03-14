<?php

namespace FlexibleWishlistVendor\WPDesk\View\Resolver;

use FlexibleWishlistVendor\WPDesk\View\Renderer\Renderer;
use FlexibleWishlistVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \FlexibleWishlistVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \FlexibleWishlistVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \FlexibleWishlistVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Null Cannot resolve");
    }
}
