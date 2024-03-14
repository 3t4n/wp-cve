<?php

namespace WpifyWooDeps\Wpify\Core;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;
class Menu extends AbstractComponent
{
    /**
     * @var integer The depth of the menu we are rendering
     */
    private $depth = 0;
    /**
     * @var array|null Array of `Timber\Menu` objects you can to iterate through.
     */
    private $items = null;
    /**
     * @var integer The ID of the menu, corresponding to the wp_terms table.
     */
    private $id;
    /**
     * @var integer The ID of the menu, corresponding to the wp_terms table.
     */
    private $term_id = 0;
    /**
     * @var string The name of the menu (ex: `Main Navigation`).
     */
    private $name = '';
    /**
     * Menu options.
     * @api
     * @since 1.9.6
     * @var array An array of menu options.
     */
    private $options = array();
    /**
     * @api
     * @var array The unfiltered options sent forward via the user in the __construct
     */
    private $raw_options = array();
    /**
     * Theme Location.
     * @api
     * @since 1.9.6
     * @var string The theme location of the menu, if available.
     */
    private $theme_location = '';
    private $slug;
    private $term_group;
    private $term_taxonomy_id;
    private $taxonomy;
    private $description;
    private $count;
    private $filter;
    private $term_order;
    /**
     * Initialize a menu.
     *
     * @param int|string $slug A menu slug, the term ID of the menu, the full name from the admin
     *                            menu, the slug of the registered location or nothing. Passing
     *                            nothing is good if you only have one menu. Timber will grab what
     *                            it finds.
     * @param array      $options Optional. An array of options. Right now, only the `depth` is
     *                                 supported which says how many levels of hierarchy should be
     *                                 included in the menu. Default `0`, which is all levels.
     */
    public function __construct($slug = 0, $options = array())
    {
        $this->slug = $slug;
        $this->options = $options;
    }
    public function setup()
    {
        $menu_id = \false;
        $locations = get_nav_menu_locations();
        // For future enhancements?
        $this->raw_options = $this->options;
        $this->options = wp_parse_args((array) $this->options, array('depth' => 0));
        $this->depth = (int) $this->options['depth'];
        if ($this->slug != 0 && \is_numeric($this->slug)) {
            $menu_id = $this->slug;
        } elseif (\is_array($locations) && !empty($locations)) {
            $menu_id = $this->get_menu_id_from_locations($this->slug, $locations);
        } elseif ($this->slug === \false) {
            $menu_id = \false;
        }
        if (!$menu_id) {
            $menu_id = $this->get_menu_id_from_terms($this->slug);
        }
        if ($menu_id) {
            $this->initialize($menu_id);
        } else {
            $this->init_as_page_menu();
        }
    }
    /**
     * @param string $slug
     * @param array  $locations
     *
     * @return integer
     * @internal
     */
    protected function get_menu_id_from_locations($slug, $locations)
    {
        if ($slug === 0) {
            $slug = $this->get_menu_id_from_terms($slug);
        }
        if (\is_numeric($slug)) {
            $slug = \array_search($slug, $locations);
        }
        if (isset($locations[$slug])) {
            $menu_id = $locations[$slug];
            if (\function_exists('wpml_object_id_filter')) {
                $menu_id = wpml_object_id_filter($locations[$slug], 'nav_menu');
            }
            return $menu_id;
        }
    }
    /**
     * @param int $slug
     *
     * @return int
     * @internal
     */
    protected function get_menu_id_from_terms($slug = 0)
    {
        if (!\is_numeric($slug) && \is_string($slug)) {
            // we have a string so lets search for that
            $menu = get_term_by('slug', $slug, 'nav_menu');
            if ($menu) {
                return $menu->term_id;
            }
            $menu = get_term_by('name', $slug, 'nav_menu');
            if ($menu) {
                return $menu->term_id;
            }
        }
        $menus = get_terms('nav_menu', array('hide_empty' => \true));
        if (\is_array($menus) && \count($menus)) {
            if (isset($menus[0]->term_id)) {
                return $menus[0]->term_id;
            }
        }
        return 0;
    }
    /**
     * @param int $menu_id
     *
     * @internal
     */
    public function initialize($menu_id)
    {
        $menu = wp_get_nav_menu_items($menu_id);
        $locations = get_nav_menu_locations();
        // Set theme location if available.
        if (!empty($locations) && \in_array($menu_id, $locations, \true)) {
            $this->theme_location = \array_search($menu_id, $locations, \true);
        }
        if ($menu) {
            _wp_menu_item_classes_by_context($menu);
            if (\is_array($menu)) {
                /**
                 * Default arguments from wp_nav_menu() function.
                 * @see wp_nav_menu()
                 */
                $default_args_array = array('menu' => '', 'container' => 'div', 'container_class' => '', 'container_id' => '', 'menu_class' => 'menu', 'menu_id' => '', 'echo' => \true, 'fallback_cb' => 'wp_page_menu', 'before' => '', 'after' => '', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>', 'item_spacing' => 'preserve', 'depth' => $this->depth, 'walker' => '', 'theme_location' => '');
                /**
                 * Improve compatibility with third-party plugins.
                 * @see wp_nav_menu()
                 */
                $default_args_array = apply_filters('wp_nav_menu_args', $default_args_array);
                $default_args_obj = (object) $default_args_array;
                $menu = apply_filters('wp_nav_menu_objects', $menu, $default_args_obj);
                $menu = self::order_children($menu);
                $menu = self::strip_to_depth_limit($menu);
            }
            $this->items = $menu;
            $menu_info = wp_get_nav_menu_object($menu_id);
            foreach ($menu_info as $key => $val) {
                if ($key == 'parent') {
                    continue;
                }
                $this->{$key} = $val;
            }
            $this->id = $this->term_id;
        }
    }
    /**
     * @param array $items
     *
     * @return array
     * @internal
     */
    protected function order_children($items)
    {
        $index = array();
        $menu = array();
        $wp_post_menu_item = null;
        foreach ($items as $item) {
            if (isset($item->ID)) {
                $menu_item = $this->create_menu_item($item);
                $index[$item->ID] = $menu_item;
            }
        }
        foreach ($index as $item) {
            /** @var MenuItem $item */
            if ($item->get_menu_item_parent() && isset($index[$item->get_menu_item_parent()])) {
                $index[$item->get_menu_item_parent()]->add_child($item);
            } else {
                $menu[] = $item;
            }
        }
        return $menu;
    }
    /**
     * @param object $item the WP menu item object to wrap
     *
     * @return mixed an instance of the user-configured $MenuItemClass
     * @internal
     */
    protected function create_menu_item($item)
    {
        return $this->plugin->create_component(MenuItem::class, ['data' => $item, 'menu' => $this]);
    }
    /**
     * @param array $menu
     *
     * @internal
     */
    protected function strip_to_depth_limit($menu, $current = 1)
    {
        $depth = (int) $this->depth;
        // Confirms still int.
        if ($depth <= 0) {
            return $menu;
        }
        foreach ($menu as &$currentItem) {
            if ($current == $depth) {
                $currentItem->children = \false;
                continue;
            }
            $currentItem->children = self::strip_to_depth_limit($currentItem->children, $current + 1);
        }
        return $menu;
    }
    /**
     * @internal
     */
    protected function init_as_page_menu()
    {
        $menu = get_pages(array('sort_column' => 'menu_order'));
        if ($menu) {
            foreach ($menu as $mi) {
                $mi->__title = $mi->post_title;
            }
            _wp_menu_item_classes_by_context($menu);
            if (\is_array($menu)) {
                $menu = self::order_children($menu);
            }
            $this->items = $menu;
        }
    }
    /**
     * Find a parent menu item in a set of menu items.
     *
     * @param array $menu_items An array of menu items.
     * @param int   $parent_id The parent ID to look for.
     *
     * @return \Timber\MenuItem|bool A menu item. False if no parent was found.
     * @api
     */
    public function find_parent_item_in_menu($menu_items, $parent_id)
    {
        foreach ($menu_items as &$item) {
            if ($item->ID == $parent_id) {
                return $item;
            }
        }
    }
    /**
     * @return array|null
     */
    public function get_items()
    {
        if (\is_array($this->items)) {
            return $this->items;
        }
        return array();
    }
    /**
     * @return int
     */
    public function get_depth() : int
    {
        return $this->depth;
    }
    /**
     * @return int
     */
    public function get_term_id() : int
    {
        return $this->term_id;
    }
    /**
     * @return string
     */
    public function get_name() : string
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function get_title() : string
    {
        return $this->title;
    }
    /**
     * @return array
     */
    public function get_options() : array
    {
        return $this->options;
    }
    /**
     * @return array
     */
    public function get_raw_options() : array
    {
        return $this->raw_options;
    }
    /**
     * @return string
     */
    public function get_theme_location() : string
    {
        return $this->theme_location;
    }
    /**
     * @return mixed
     */
    public function get_term_group()
    {
        return $this->term_group;
    }
    /**
     * @return mixed
     */
    public function get_term_taxonomy_id()
    {
        return $this->term_taxonomy_id;
    }
    /**
     * @return mixed
     */
    public function get_taxonomy()
    {
        return $this->taxonomy;
    }
    /**
     * @return mixed
     */
    public function get_description()
    {
        return $this->description;
    }
    /**
     * @return mixed
     */
    public function get_count()
    {
        return $this->count;
    }
    /**
     * @return mixed
     */
    public function get_filter()
    {
        return $this->filter;
    }
    /**
     * @return mixed
     */
    public function get_term_order()
    {
        return $this->term_order;
    }
}
