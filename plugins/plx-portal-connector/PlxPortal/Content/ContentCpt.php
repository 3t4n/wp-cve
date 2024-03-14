<?php

namespace PlxPortal\Content;

class ContentCpt
{
    const POST_TYPE = 'plx_portal_content';

    public function __construct()
    {
        add_action('init', array($this, 'register_post_type'));
    }

    public function register_post_type()
    {
        $labels = array(
            'name'               => _x('Web Content', 'post type general name', 'plx-portal'),
            'singular_name'      => _x('Web Content', 'post type singular name', 'plx-portal'),
            'menu_name'          => _x('PLX Portal', 'admin menu', 'plx-portal'),
            'name_admin_bar'     => _x('Web Content', 'add new on admin bar', 'plx-portal'),
            'add_new'            => _x('Add New', 'Web Content', 'plx-portal'),
            'add_new_item'       => __('Add New Web Content', 'plx-portal'),
            'new_item'           => __('New Web Content', 'plx-portal'),
            'edit_item'          => __('Edit Web Content', 'plx-portal'),
            'view_item'          => __('View Web Content', 'plx-portal'),
            'all_items'          => __('Web Content', 'plx-portal'),
            'search_items'       => __('Search Web Content', 'plx-portal'),
            'parent_item_colon'  => __('Parent Web Content:', 'plx-portal'),
            'not_found'          => __('No Web Content found.', 'plx-portal'),
            'not_found_in_trash' => __('No Web Content found in Trash.', 'plx-portal')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Description.', 'plx-portal'),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 400,
            'supports'           => array(''),
        );

        register_post_type(self::POST_TYPE, $args);
    }
}
