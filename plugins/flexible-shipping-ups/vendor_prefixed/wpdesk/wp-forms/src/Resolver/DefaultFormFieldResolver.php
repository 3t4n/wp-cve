<?php

namespace UpsFreeVendor\WPDesk\Forms\Resolver;

use UpsFreeVendor\WPDesk\View\Renderer\Renderer;
use UpsFreeVendor\WPDesk\View\Resolver\DirResolver;
use UpsFreeVendor\WPDesk\View\Resolver\Resolver;
/**
 * Use with View to resolver form fields to default templates.
 *
 * @package WPDesk\Forms\Resolver
 */
class DefaultFormFieldResolver implements \UpsFreeVendor\WPDesk\View\Resolver\Resolver
{
    /** @var Resolver */
    private $dir_resolver;
    public function __construct()
    {
        $this->dir_resolver = new \UpsFreeVendor\WPDesk\View\Resolver\DirResolver(__DIR__ . '/../../templates');
    }
    public function resolve($name, \UpsFreeVendor\WPDesk\View\Renderer\Renderer $renderer = null) : string
    {
        return $this->dir_resolver->resolve($name, $renderer);
    }
}
