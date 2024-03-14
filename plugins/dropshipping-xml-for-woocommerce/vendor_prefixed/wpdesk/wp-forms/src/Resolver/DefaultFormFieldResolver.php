<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Resolver;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\View\Resolver\DirResolver;
use DropshippingXmlFreeVendor\WPDesk\View\Resolver\Resolver;
/**
 * Use with View to resolver form fields to default templates.
 *
 * @package WPDesk\Forms\Resolver
 */
class DefaultFormFieldResolver implements \DropshippingXmlFreeVendor\WPDesk\View\Resolver\Resolver
{
    /** @var Resolver */
    private $dir_resolver;
    public function __construct()
    {
        $this->dir_resolver = new \DropshippingXmlFreeVendor\WPDesk\View\Resolver\DirResolver(__DIR__ . '/../../templates');
    }
    public function resolve($name, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        return $this->dir_resolver->resolve($name, $renderer);
    }
}
