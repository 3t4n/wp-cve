<?php

namespace DropshippingXmlFreeVendor\WPDesk\View\Resolver;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * Provide resolvers and this class can try them one after another
 *
 * @package WPDesk\View\Resolver
 */
class ChainResolver implements \DropshippingXmlFreeVendor\WPDesk\View\Resolver\Resolver
{
    /** @var Resolver[] */
    private $resolvers;
    /**
     * Warning: function with variadic input. Input should be list of Resolver instances.
     */
    public function __construct()
    {
        $args = \func_get_args();
        foreach ($args as $resolver) {
            $this->appendResolver($resolver);
        }
    }
    /**
     * Append resolver to the end of the list
     *
     * @param Resolver $resolver
     */
    public function appendResolver($resolver)
    {
        $this->resolvers[] = $resolver;
    }
    /**
     * Resolve name to full path
     *
     * @param string $name
     * @param Renderer|null $renderer
     *
     * @return string
     */
    public function resolve($name, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        foreach ($this->resolvers as $resolver) {
            try {
                return $resolver->resolve($name);
            } catch (\DropshippingXmlFreeVendor\WPDesk\View\Resolver\Exception\CanNotResolve $e) {
                // not interested
            }
        }
        throw new \DropshippingXmlFreeVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Cannot resolve {$name}");
    }
}
