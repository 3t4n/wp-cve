<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Create the links Collection custom post type
 *
 * @link       https://walterpinem.me/
 * @since      1.0.6
 *
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/admin/partials
 * @author     Walter Pinem <hello@walterpinem.me>
 * @copyright  Copyright (c) 2020, Walter Pinem, Seni Berpikir
 */
// Post Type Key: Collection
function wp_mylinks_register_post_type_collection()
{

    $labels = array(
        'name' => _x('MyLinks Collection', 'Post Type General Name', 'wp-mylinks'),
        'singular_name' => _x('MyLinks Collection', 'Post Type Singular Name', 'wp-mylinks'),
        'menu_name' => _x('Collection', 'Admin Menu text', 'wp-mylinks'),
        'name_admin_bar' => _x('MyLinks Collection', 'Add New on Toolbar', 'wp-mylinks'),
        'archives' => __('Collection', 'wp-mylinks'),
        'attributes' => __('Collection', 'wp-mylinks'),
        'parent_item_colon' => __('Collection', 'wp-mylinks'),
        'all_items' => __('All Collection', 'wp-mylinks'),
        'add_new_item' => __('Add New MyLink Collection', 'wp-mylinks'),
        'add_new' => __('Add New', 'wp-mylinks'),
        'new_item' => __('New Collection', 'wp-mylinks'),
        'edit_item' => __('Edit MyLink Collection', 'wp-mylinks'),
        'update_item' => __('Update Collection', 'wp-mylinks'),
        'view_item' => __('View Collection', 'wp-mylinks'),
        'view_items' => __('View Collection', 'wp-mylinks'),
        'search_items' => __('Search Collection', 'wp-mylinks'),
        'not_found' => __('Not found', 'wp-mylinks'),
        'not_found_in_trash' => __('Not found in Trash', 'wp-mylinks'),
        'featured_image' => __('Featured Image', 'wp-mylinks'),
        'set_featured_image' => __('Set featured image', 'wp-mylinks'),
        'remove_featured_image' => __('Remove featured image', 'wp-mylinks'),
        'use_featured_image' => __('Use as featured image', 'wp-mylinks'),
        'insert_into_item' => __('Insert into Collection', 'wp-mylinks'),
        'uploaded_to_this_item' => __('Uploaded to this Collection', 'wp-mylinks'),
        'items_list' => __('Collection list', 'wp-mylinks'),
        'items_list_navigation' => __('Collection list navigation', 'wp-mylinks'),
        'filter_items_list' => __('Filter Collection list', 'wp-mylinks'),
    );

    $args = array(
        'label' => __('Collection', 'wp-mylinks'),
        'description' => __('Collection', 'wp-mylinks'),
        'labels' => $labels,
        'supports' => array('title'),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => true,
        'show_in_nav_menus' => false,
        'capability_type' => 'page',
        'show_in_rest' => false,
        'register_meta_box_cb' => 'wp_mylinks_collection_register_metabox',
    );
    register_post_type('mylinks-collection', $args);
}
add_action('init', 'wp_mylinks_register_post_type_collection', 0);

// Register the meta box
function wp_mylinks_collection_register_metabox()
{
    wp_mylinks_collection_cmb2_metaboxes();
}
add_action('cmb2_admin_init', 'wp_mylinks_collection_cmb2_metaboxes');
function wp_mylinks_collection_cmb2_metaboxes()
{
    $cmb = new_cmb2_box(
        [
            'id'           => mylinks_collection('form'),
            'title'        => esc_html__('Link', 'wp-mylinks'),
            'object_types' => array('mylinks-collection'),
            'context'      => 'normal',
            'priority'     => 'high',
        ]
    );
    $cmb->add_field(
        [
            'name'         => __('Link', 'wp-mylinks'),
            'desc'         => __('Include the http:// or https:// protocol, including trailing slash (/) if applicable.', 'wp-mylinks'),
            'id'           => mylinks_collection('link_collection'),
            'type'         => 'text_url',
            'before_row'   => __('If you have a link that you will reuse later, add it here so upon building your MyLink page, this link will be searchable.', 'wp-mylinks'),
            'attributes'  => array(
                'class' => 'mylinks-input-url',
            ),
            'options' => [],
        ]
    );
}

// Set its menu
function wp_mylinks_post_type_collection_submenu()
{
    add_submenu_page(
        'edit.php?post_type=mylink',
        'Collection',
        'Collection',
        'manage_options',
        'edit.php?post_type=mylinks-collection'
    );
}
add_action('admin_menu', 'wp_mylinks_post_type_collection_submenu');
