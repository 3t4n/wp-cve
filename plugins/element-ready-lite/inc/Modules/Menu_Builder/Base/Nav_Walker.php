<?php

namespace Element_Ready\Modules\Menu_Builder\Base;

class Nav_Walker extends \Walker_Nav_Menu
{
    public $q_menu_Settings;
    // get menu setting
    public function get_item_meta($item_id)
    {

        $is_mega_menu                     = get_post_meta($item_id, 'element_ready_mega_menu_post_item_mega_menu_enable', true);
        $is_off_canvas                    = get_post_meta($item_id, 'element_ready_mega_menu_post_item_mega_offcanvas_enable', true);
        $is_mobile_menu                   = get_post_meta($item_id, 'element_ready_mega_menu_post_item_mega_is_mobile_menu', true);
        $content_id                       = get_post_meta($item_id, 'element_ready_mega_menu_post_item_mega_content_id', true);
        $submenu_content_type             = get_post_meta($item_id, 'element_ready_mobile_submenu_content_type', true);
        $megamenu_width_type              = get_post_meta($item_id, 'element_ready_megamenu_width_type', true);
        $vertical_megamenu_position       = get_post_meta($item_id, 'element_ready_vertical_megamenu_position_type', true);


        $default = [
            "menu_id"                    => null,
            "is_mobile_mega_menu"        => $is_mobile_menu,
            "is_mega_menu"               => $is_mega_menu,
            "is_off_canvas"              => $is_off_canvas,
            "submenu_content_type"       => $submenu_content_type,
            "megamenu_width_type"        => $megamenu_width_type,
            "vertical_megamenu_position" => $vertical_megamenu_position,
            "content_id"                 => $content_id,
        ];

        return $default;
    }

    public function is_megamenu($menu_slug)
    {

        $menu_slug = (((gettype($menu_slug) == 'object') && (isset($menu_slug->slug))) ? $menu_slug->slug : $menu_slug);
        $active = get_option('element_ready_mega_menu_options_enable_menu' . $menu_slug);
        $return = 0;

        if ('on' == $active) {
            $return = 1;
        }

        return $return;
    }

    public function is_megamenu_item($item_meta, $menu)
    {
        //element_ready_mega_menu_post_item_mega_menu_enable
        if ($this->is_megamenu($menu) && $item_meta['is_mega_menu'] == 'yes' && class_exists('Elementor\Plugin')) {
            return true;
        }

        return false;
    }

    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"element-ready-dropdown sub_menu element-ready-submenu-section\">\n";
    }
    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;


        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        // New
        $class_names .= ' nav-item';
        $item_meta = $this->get_item_meta($item->ID);

        $is_megamenu_item = $this->is_megamenu_item($item_meta, $args->menu);

        if (in_array('menu-item-has-children', $classes) || $is_megamenu_item == true) {
            $class_names .= ' element-ready-dropdown-has ' . $item_meta['vertical_megamenu_position'] . ' element-ready-dropdown-menu-' . $item_meta['megamenu_width_type'] . '';
        }

        if ($is_megamenu_item == true) {
            $class_names .= ' element-ready-has-megamenu';
        }


        if (in_array('current-menu-item', $classes)) {
            $class_names .= ' active';
        }


        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        // New

        $data_attr = '';

        //
        $output .= $indent . '<li' . $id . $class_names . $data_attr . '>';
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target)     ? $item->target     : '';
        $atts['rel']    = !empty($item->xfn)        ? $item->xfn        : '';
        $atts['href']   = !empty($item->url)        ? $item->url        : '';

        $submenu_indicator = '';

        // New
        if ($depth === 0) {
            $atts['class'] = 'element-ready-menu-nav-link';
        }
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $atts['class'] .= ' element-ready-menu-dropdown-toggle';
        }
        if (in_array('menu-item-has-children', $classes) || $is_megamenu_item == true) {
            $submenu_indicator .= '<i class="fa fa-angle-down element-ready-submenu-indicator"></i>';
        }
        if ($depth > 0) {
            $manual_class = array_values($classes)[0] . ' ' . 'dropdown-item';
            $atts['class'] = $manual_class;
        }
        if (in_array('current-menu-item', $item->classes)) {
            $atts['class'] .= ' active';
        }


        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $item_output = $args->before;
        // New

        //
        $item_output .= '<a' . $attributes . '>';

        if ($this->is_megamenu($args->menu) == 1) {

            // add menu icon & style
            if (isset($item_meta['menu_icon']) &&  $item_meta['menu_icon'] != '') {
                $icon_style = 'color:' . isset($item_meta['menu_icon_color']) ? $item_meta['menu_icon_color'] : '#000';
                $item_output .= wp_kses_post('<i class="element-ready-menu-icon ' . $item_meta['menu_icon'] . '" style="' . $icon_style . '" ></i>');
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= $submenu_indicator . '</a>';
        $item_output .= $args->after;
        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of {@see wp_nav_menu()} arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    /**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_el(&$output, $item, $depth = 0, $args = array())
    {


        if ($depth === 0) {

            if ($this->is_megamenu($args->menu) == 1) {
                $item_meta = $this->get_item_meta($item->ID);
                if ($item_meta['is_mega_menu'] == 'yes' && class_exists('Elementor\Plugin')) {
                    $builder_post_title = 'dynamic-content-megamenu-menu-item' . $item->ID;
                    $builder_post = new \WP_Query(array(
                        'post_type' => 'element_ready_content',
                        'post_title' => $builder_post_title,
                        'post_status' => 'publish',
                        'posts_per_page' => 1,
                    ));
                    $output .= '<div class="mega_menu element-ready-megamenu-section">';
                    if ($item_meta['content_id'] != '') {
                        $elementor = \Elementor\Plugin::instance();
                        $output .= $elementor->frontend->get_builder_content_for_display($item_meta['content_id']);
                    } else {
                        $output .= esc_html__('No content found', 'element-ready-lite');
                    }
                    $output .= '</div>';
                } // end if
            }
            $output .= "</li>\n";
        }
    }
}
