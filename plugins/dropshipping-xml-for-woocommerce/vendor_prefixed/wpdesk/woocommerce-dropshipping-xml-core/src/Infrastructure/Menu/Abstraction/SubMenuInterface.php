<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction;

/**
 * Interface SubMenuInterface, abstraction layer for sub menu..
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu
 */
interface SubMenuInterface extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MenuInterface
{
    public function get_parent_slug() : string;
    public function set_parent_slug(string $parent_slug);
}
