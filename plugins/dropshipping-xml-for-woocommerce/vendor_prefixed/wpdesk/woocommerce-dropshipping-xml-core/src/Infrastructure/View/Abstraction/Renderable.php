<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction;

/**
 * Interface Renderable, add render method to view.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\View
 */
interface Renderable
{
    /**
     * @return string
     */
    public function render() : string;
}
