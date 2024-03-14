<?php

namespace WpifyWooDeps\Wpify\Core;

use stdClass;
use WP_Post;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;
class MenuItem extends AbstractComponent
{
    /**
     * @var Menu
     */
    private $menu;
    private $id;
    private $name = '';
    private $menu_object;
    private $classes = array();
    private $class = '';
    private $level = 0;
    private $post_name;
    private $url;
    private $object_id;
    private $object;
    private $type;
    private $target;
    private $current;
    private $menu_item_parent;
    private $current_item_parent;
    private $current_item_ancestor;
    /**
     * @api
     * @var array Array of children of a menu item. Empty if there are no child menu items.
     */
    private $children = array();
    /**
     * @api
     * @var bool Whether the menu item has a `menu-item-has-children` CSS class.
     */
    private $has_child_class = \false;
    /**
     * @param WP_Post
     * @param Menu
     *
     * @internal
     */
    public function __construct($data, $menu = null)
    {
        $this->menu = $menu;
        $this->id = $data->ID;
        $this->import_classes($data);
        $this->name = $data->title;
        $this->menu_item_parent = $data->menu_item_parent;
        $this->url = $data->url;
        $this->current = $data->current;
        $this->current = $data->current;
        $this->current_item_ancestor = $data->current_item_ancestor;
        $this->current_item_parent = $data->current_item_parent;
        $this->object_id = $data->object_id;
        $this->object = $data->object;
        $this->type = $data->type;
        $this->target = $data->target;
        $this->add_class('menu-item-' . $data->ID);
        $this->menu_object = $data;
    }
    /**
     * Imports the classes to be used in CSS.
     *
     * @param array|object $data
     *
     * @internal
     */
    public function import_classes($data)
    {
        $this->classes = \array_merge($this->classes, $data->classes);
        $this->classes = \array_unique($this->classes);
        $options = new stdClass();
        if ($this->menu->get_options()) {
            // The options need to be an object.
            $options = (object) $this->menu->get_options();
        }
        /**
         * Filters the CSS classes applied to a menu item’s list item.
         *
         * @param string[] $classes An array of the CSS classes that can be applied to the
         *                                  menu item’s `<li>` element.
         * @param MenuItem $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments. In Timber, we
         *                                  don’t have these arguments because we don’t use a menu
         *                                  walker. Instead, you get the options that were used to
         *                                  create the `Timber\Menu` object.
         * @param int $depth Depth of menu item.
         */
        $this->classes = apply_filters('nav_menu_css_class', $this->classes, $this, $options, 0);
        $this->class = \trim(\implode(' ', $this->classes));
    }
    /**
     * Add a CSS class the menu item should have.
     *
     * @param string $class_name CSS class name to be added.
     */
    public function add_class($class_name)
    {
        $this->classes[] = $class_name;
        $this->class .= ' ' . $class_name;
    }
    /**
     * Add a new `MenuItem` object as a child of this menu item.
     *
     * @param MenuItem $item The menu item to add.
     */
    public function add_child($item)
    {
        if (!$this->has_child_class) {
            $this->add_class('menu-item-has-children');
            $this->has_child_class = \true;
        }
        $this->children[] = $item;
        $item->level = $this->level + 1;
        if (\count($this->children)) {
            $this->update_child_levels();
        }
    }
    /**
     * @return bool|null
     * @internal
     */
    public function update_child_levels()
    {
        if (\is_array($this->children)) {
            foreach ($this->children as $child) {
                $child->level = $this->level + 1;
                $child->update_child_levels();
            }
        }
        return \true;
    }
    /**
     * @return MenuItem[]
     */
    public function get_children()
    {
        return $this->children;
    }
    public function is_external()
    {
        if ($this->get_type() != 'custom') {
            return \false;
        }
        return URLHelper::is_external($this->url);
    }
    /**
     * @return mixed
     */
    public function get_type()
    {
        return $this->type;
    }
    /**
     * @return Menu|null
     */
    public function menu()
    {
        return $this->menu;
    }
    /**
     * Get a meta value of the menu item.
     * Plugins like Advanced Custom Fields allow you to set custom fields for menu items. With this
     * method you can retrieve the value of these.
     *
     * @param string $key The meta key to get the value for.
     *
     * @return mixed Whatever value is stored in the database.
     * @example
     * ```twig
     * <a class="icon-{{ item.meta('icon') }}" href="{{ item.link }}">{{ item.title }}</a>
     * ```
     * @api
     */
    public function get_meta($key = '')
    {
        if (\is_object($this->menu_object) && \is_a($this->menu_object, 'WP_Post')) {
            if ($key) {
                return get_post_meta($this->menu_object->ID, $key, \true);
            } else {
                $meta = get_post_meta($this->menu_object->ID);
                $result = array();
                foreach ($meta as $key => $items) {
                    if (\sizeof($items) > 1) {
                        foreach ($items as $item) {
                            $result[$key] = maybe_unserialize($item);
                        }
                    } else {
                        $result[$key] = maybe_unserialize($items[0]);
                    }
                }
                return $result;
            }
        }
        if (isset($this->{$key})) {
            return $this->{$key};
        }
    }
    /**
     * @return bool
     */
    public function has_child_class() : bool
    {
        return $this->has_child_class;
    }
    /**
     * @return array
     */
    public function get_classes() : array
    {
        return $this->classes;
    }
    /**
     * @return string
     */
    public function get_class() : string
    {
        return $this->class;
    }
    /**
     * @return int
     */
    public function get_level() : int
    {
        return $this->level;
    }
    /**
     * @return mixed
     */
    public function get_post_name()
    {
        return $this->post_name;
    }
    /**
     * @return mixed
     */
    public function get_url()
    {
        return $this->url;
    }
    /**
     * @return bool
     */
    public function is_current() : bool
    {
        return $this->current;
    }
    /**
     * @return bool
     */
    public function get_current_item_parent() : bool
    {
        return $this->current_item_parent;
    }
    /**
     * @return bool
     */
    public function is_current_item_ancestor() : bool
    {
        return $this->current_item_ancestor;
    }
    /**
     * @return Menu
     */
    public function get_menu() : Menu
    {
        return $this->menu;
    }
    /**
     * @return string
     */
    public function get_name() : string
    {
        return $this->name;
    }
    /**
     * @return mixed
     */
    public function get_menu_object()
    {
        return $this->menu_object;
    }
    /**
     * @return mixed
     */
    public function get_menu_item_parent()
    {
        return $this->menu_item_parent;
    }
    /**
     * @return mixed
     */
    public function get_object_id()
    {
        return $this->object_id;
    }
    /**
     * @return mixed
     */
    public function get_object()
    {
        return $this->object;
    }
    /**
     * @return mixed
     */
    public function get_target()
    {
        return $this->target;
    }
    /**
     * @return mixed
     */
    public function get_current_item_ancestor()
    {
        return $this->current_item_ancestor;
    }
    public function get_id()
    {
        return $this->id;
    }
}
