<?php

add_action('init', 'kopa_staff_init');

function kopa_staff_init() {
    $labels = array(
        'name' => __('Staffs', 'kopa-nictitate-toolkit'),
        'singular_name' => __('Staff', 'kopa-nictitate-toolkit'),
        'add_new' => __('Add New', 'kopa-nictitate-toolkit'),
        'add_new_item' => __('Add New Item', 'kopa-nictitate-toolkit'),
        'edit_item' => __('Edit Item', 'kopa-nictitate-toolkit'),
        'new_item' => __('New Item', 'kopa-nictitate-toolkit'),
        'all_items' => __('All Items', 'kopa-nictitate-toolkit'),
        'view_item' => __('View Item', 'kopa-nictitate-toolkit'),
        'search_items' => __('Search Items', 'kopa-nictitate-toolkit'),
        'not_found' => __('No items found', 'kopa-nictitate-toolkit'),
        'not_found_in_trash' => __('No items found in Trash', 'kopa-nictitate-toolkit'),
        'parent_item_colon' => '',
        'menu_name' => __('Staffs', 'kopa-nictitate-toolkit')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'staffs'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'menu_position' => 100,
        'supports' => array('title', 'thumbnail', 'editor', 'excerpt'),
        'can_export' => true,
        'register_meta_box_cb' => ''
    );

    register_post_type('staffs', $args);

    $taxonomy_category_args = array(
        'public' => true,
        'hierarchical' => true,
        'labels' => array(
            'name' => __('Staff Categories', 'taxonomy general name', 'kopa-nictitate-toolkit'),
            'singular_name' => __('Category', 'taxonomy singular name', 'kopa-nictitate-toolkit'),
            'search_items' => __('Search Category', 'kopa-nictitate-toolkit'),
            'popular_items' => __('Popular Staffs', 'kopa-nictitate-toolkit'),
            'all_items' => __('All Staffs', 'kopa-nictitate-toolkit'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Staff', 'kopa-nictitate-toolkit'),
            'update_item' => __('Update Staff', 'kopa-nictitate-toolkit'),
            'add_new_item' => __('Add New Staff', 'kopa-nictitate-toolkit'),
            'new_item_name' => __('New Staff Name', 'kopa-nictitate-toolkit'),
            'separate_items_with_commas' => __('Separate categories with commas', 'kopa-nictitate-toolkit'),
            'add_or_remove_items' => __('Add or remove category', 'kopa-nictitate-toolkit'),
            'choose_from_most_used' => __('Choose from the most used categories', 'kopa-nictitate-toolkit'),
            'menu_name' => __('Staff Categories', 'kopa-nictitate-toolkit')
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'staff_category')
    );

    register_taxonomy('staff_category', 'staffs', $taxonomy_category_args);

    #TAXONOMY TAG
    $taxonomy_tag_args = array(
        'public' => true,
        'hierarchical' => false,
        'labels' => array(
            'name' => __('Staff Tags', 'taxonomy general name', 'kopa-nictitate-toolkit'),
            'singular_name' => __('Tag', 'taxonomy singular name', 'kopa-nictitate-toolkit'),
            'search_items' => __('Search Tag', 'kopa-nictitate-toolkit'),
            'popular_items' => __('Popular Tags', 'kopa-nictitate-toolkit'),
            'all_items' => __('All Tags', 'kopa-nictitate-toolkit'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Tag', 'kopa-nictitate-toolkit'),
            'update_item' => __('Update Tag', 'kopa-nictitate-toolkit'),
            'add_new_item' => __('Add New Tag', 'kopa-nictitate-toolkit'),
            'new_item_name' => __('New Tag Name', 'kopa-nictitate-toolkit'),
            'separate_items_with_commas' => __('Separate tags with commas', 'kopa-nictitate-toolkit'),
            'add_or_remove_items' => __('Add or remove tag', 'kopa-nictitate-toolkit'),
            'choose_from_most_used' => __('Choose from the most used tags', 'kopa-nictitate-toolkit'),
            'menu_name' => __('Staff Tags', 'kopa-nictitate-toolkit')
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'staff_tag')
    );

    register_taxonomy('staff_tag', 'staffs', $taxonomy_tag_args);

    flush_rewrite_rules(false);    
}