<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Resolver;

use OctolizeShippingNoticesVendor\WPDesk\View\Renderer\Renderer;
use OctolizeShippingNoticesVendor\WPDesk\View\Resolver\DirResolver;
use OctolizeShippingNoticesVendor\WPDesk\View\Resolver\Resolver;
/**
 * Use with View to resolver form fields to default templates.
 *
 * @package WPDesk\Forms\Resolver
 */
class DefaultFormFieldResolver implements \OctolizeShippingNoticesVendor\WPDesk\View\Resolver\Resolver
{
    /** @var Resolver */
    private $dir_resolver;
    public function __construct()
    {
        $this->dir_resolver = new \OctolizeShippingNoticesVendor\WPDesk\View\Resolver\DirResolver(__DIR__ . '/../../templates');
    }
    public function resolve($name, \OctolizeShippingNoticesVendor\WPDesk\View\Renderer\Renderer $renderer = null) : string
    {
        return $this->dir_resolver->resolve($name, $renderer);
    }
}
