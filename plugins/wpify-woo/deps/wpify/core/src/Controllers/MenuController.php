<?php

namespace WpifyWooDeps\Wpify\Core\Controllers;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractController;
use WpifyWooDeps\Wpify\Core\Menu;
class MenuController extends AbstractController
{
    private $items = array();
    public function get_menu($slug = 0, $options = array())
    {
        $menu = $this->plugin->create_component(Menu::class, ['slug' => $slug, 'options' => $options]);
        $menu->init();
        $items = array();
        foreach ($menu->get_items() as $item) {
            $items[] = $this->to_array($item);
        }
        return $items;
    }
    public function to_array($item)
    {
        return array('id' => $item->get_id(), 'name' => $item->get_name(), 'url' => $item->get_url(), 'target' => $item->get_target(), 'level' => $item->get_level(), 'type' => $item->get_type(), 'current' => $item->is_current(), 'menu_item_parent' => $item->get_menu_item_parent(), 'has_child_class' => $item->has_child_class(), 'meta' => $item->get_meta(), 'classes' => $item->get_classes(), 'object_id' => $item->get_object_id(), 'children' => \array_map(function ($item) {
            return $this->to_array($item);
        }, $item->get_children()));
    }
    /**
     * @return array
     */
    public function get_items() : array
    {
        return $this->items;
    }
}
