<?php

namespace ShopWP;

use ShopWP\Utils;
use ShopWP\Utils\Data as Utils_Data;
use ShopWP\Transients;

if (!defined('ABSPATH')) {
    exit();
}

class CPT
{
    public $DB_Settings_General;
    public $plugin_settings;

    public function __construct($DB_Settings_General, $plugin_settings)
    {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->plugin_settings = $plugin_settings;
    }

    public function find_post_type_slug($type, $enable_default_pages)
    {
        if (!$enable_default_pages) {
            return $type;
        }

        $url = $this->plugin_settings['general']['url_' . $type];

        $slug = basename(parse_url($url, PHP_URL_PATH));

        if (!$url || !$slug) {
            return $type;
        }

        return $slug;
    }

    public static function set_post_id_if_exists($model, $existing_post_id)
    {
        if (!empty($existing_post_id)) {
            $model['ID'] = $existing_post_id;
        }

        return $model;
    }

    public static function return_author_id()
    {
        if (get_current_user_id() === 0) {
            $author_id = 1;
        } else {
            $author_id = get_current_user_id();
        }

        return intval($author_id);
    }

    public static function set_post_id($post, $post_id)
    {
        $post->post_id = $post_id;

        return $post;
    }

    public static function add_props($item, $props)
    {
        foreach ($props as $key => $value) {
            $item->{$key} = $value;
        }

        return $item;
    }

    public static function add_props_to_items($items, $props)
    {
        return array_map(function ($item) use ($props) {
            return self::add_props($item, $props);
        }, $items);
    }

    public function post_type_products()
    {
        if (post_type_exists(SHOPWP_PRODUCTS_POST_TYPE_SLUG)) {
            return;
        }

        $enable_default_pages = $this->plugin_settings['general']['enable_default_pages'];

        $slug = $this->find_post_type_slug('products', $enable_default_pages);

        $rewrite_rules = [
            'slug' => $slug,
            'with_front' => false,
            'feeds' => true,
        ];

        $publicly_queryable = true;
        $exclude_from_search = false;

        $labels = [
            'name' => 'Products',
            'singular_name' => 'Product',
            'menu_name' => 'Products',
            'new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
            'not_found' => 'No Products found',
            'not_found_in_trash' => 'No Products found in trash',
        ];

        $args = [
            'label' => 'Products',
            'description' => 'Custom Post Type for Products',
            'labels' => $labels,
            'supports' => [
                'title',
                'page-attributes',
                'custom-fields',
                'thumbnail',
            ],
            'taxonomies' => ['category'],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_position' => 100,
            'menu_icon' => 'dashicons-megaphone',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'show_in_rest' => true,
            'has_archive' => true,
            'exclude_from_search' => $exclude_from_search,
            'publicly_queryable' => $publicly_queryable,
            'capability_type' => 'post',
            'rewrite' => $rewrite_rules,
            'capabilities' => [
                'create_posts' => false,
            ],
            'map_meta_cap' => true,
        ];

        register_post_type(
            SHOPWP_PRODUCTS_POST_TYPE_SLUG,
            apply_filters('shopwp_register_products_args', $args)
        );
    }

    public function post_type_collections()
    {
        if (post_type_exists(SHOPWP_COLLECTIONS_POST_TYPE_SLUG)) {
            return;
        }

        $enable_default_pages = $this->plugin_settings['general']['enable_default_pages'];

        $slug = $this->find_post_type_slug('collections', $enable_default_pages);

        $rewrite_rules = [
            'slug' => $slug,
            'with_front' => false,
            'feeds' => true,
        ];
        
        $publicly_queryable = true;
        $exclude_from_search = false;

        $labels = [
            'name' => 'Collections',
            'singular_name' => 'Collection',
            'menu_name' => 'Collections',
            'parent_item_colon' => 'Parent Item:',
            'new_item' => 'Add New Collection',
            'edit_item' => 'Edit Collection',
            'not_found' => 'No Collections found',
            'not_found_in_trash' => 'No Collections found in trash',
        ];

        $args = [
            'label' => 'Collections',
            'description' => 'Custom Post Type for Collections',
            'labels' => $labels,
            'supports' => [
                'title',
                'page-attributes',
                'custom-fields',
                'thumbnail',
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_position' => 100,
            'menu_icon' => 'dashicons-megaphone',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'exclude_from_search' => $exclude_from_search,
            'publicly_queryable' => $publicly_queryable,
            'capability_type' => 'post',
            'rewrite' => $rewrite_rules,
            'capabilities' => [
                'create_posts' => false,
            ],
            'map_meta_cap' => true,
        ];

        register_post_type(
            SHOPWP_COLLECTIONS_POST_TYPE_SLUG,
            apply_filters('shopwp_register_collections_args', $args)
        );
    }

    

    public function init()
    {
        $this->post_type_products();
        $this->post_type_collections();
    }
}
