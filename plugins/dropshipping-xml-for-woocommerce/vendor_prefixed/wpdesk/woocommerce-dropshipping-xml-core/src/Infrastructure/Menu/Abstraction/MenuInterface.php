<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction;

/**
 * Interface MenuInterface, abstraction layer for menu.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu
 */
interface MenuInterface
{
    public function get_position() : int;
    public function set_position(int $position);
    public function get_slug() : string;
    public function get_title() : string;
    public function set_title(string $title);
    public function get_capability() : string;
    public function set_capability(string $capability);
    public function is_hidden() : bool;
    public function set_hidden(bool $hidden);
    public function add_view_actions(array $view_classes);
    public function has_view_action(string $action) : bool;
    public function has_view_class_name(string $class_name) : bool;
    public function get_view_by_action(string $action) : string;
    public function get_action_by_class_name(string $class_name) : string;
}
