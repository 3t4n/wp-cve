<?php
namespace Shop_Ready\base;

abstract class Custom_Post
{

    /**
     * service initializer
     * @return void
     * @since 1.0
     */
    public function init($type, $singular_label, $plural_label, $settings = array())
    {

        $default_settings = array(
            'labels' => array(
                'name' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html($plural_label)),
                'singular_name' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html($singular_label)),
                'add_new_item' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html('Add New ' . $singular_label)),
                'edit_item' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html('Edit ' . $singular_label)),
                'new_item' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html('New ' . $singular_label)),
                'view_item' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html('View ' . $singular_label)),
                'search_items' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html('Search ' . $plural_label)),
                'not_found' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html('No ' . $plural_label . 'found')),
                'not_found_in_trash' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html('No ' . $plural_label . 'found in trash')),
                'parent_item_colon' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html('Parent ' . $singular_label)),
                'menu_name' => sprintf(__('%s', 'shopready-elementor-addon'), esc_html($plural_label))
            ),

            'public' => true,
            'has_archive' => true,
            'menu_icon' => '',
            'menu_position' => 20,
            'supports' => array(
                'title',
                'editor',

            ),
            'rewrite' => array(
                'slug' => sanitize_title_with_dashes($plural_label)
            )
        );

        $this->posts[$type] = array_merge($default_settings, $settings);
    }

    public function register_custom_post()
    {

        foreach ($this->posts as $key => $value) {
            register_post_type($key, $value);
            flush_rewrite_rules(false);
        }

    }
}