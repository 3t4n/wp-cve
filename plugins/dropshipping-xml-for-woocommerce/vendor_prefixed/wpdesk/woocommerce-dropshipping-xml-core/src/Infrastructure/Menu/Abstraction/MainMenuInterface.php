<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction;

/**
 * Interface MainMenuInterface, abstraction layer for main menu.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu
 */
interface MainMenuInterface extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MenuInterface
{
    public function get_icon() : string;
    public function set_icon(string $icon);
    public function get_submenus() : array;
    public function add_submenus(array $submenus);
    public function add_submenu(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\SubMenuInterface $sub_menu);
}
