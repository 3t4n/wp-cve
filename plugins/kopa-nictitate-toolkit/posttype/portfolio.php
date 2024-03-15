<?php

add_action('init', 'kopa_portfolio_init');

function kopa_portfolio_init() {
    $labels = array(
        'name' => __('Portfolios', 'kopa-nictitate-toolkit'),
        'singular_name' => __('Portfolio', 'kopa-nictitate-toolkit'),
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
        'menu_name' => __('Portfolio', 'kopa-nictitate-toolkit')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'portfolio'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'menu_position' => 100,
        'supports' => array('title', 'thumbnail', 'excerpt', 'editor'),
        'can_export' => true,
        'register_meta_box_cb' => ''
    );

    register_post_type('portfolio', $args);

    $taxonomy_category_args = array(
        'public' => true,
        'hierarchical' => true,
        'labels' => array(
            'name' => __('Portfolio Projects', 'taxonomy general name', 'kopa-nictitate-toolkit'),
            'singular_name' => __('Project', 'taxonomy singular name', 'kopa-nictitate-toolkit'),
            'search_items' => __('Search Project', 'kopa-nictitate-toolkit'),
            'popular_items' => __('Popular Projects', 'kopa-nictitate-toolkit'),
            'all_items' => __('All Projects', 'kopa-nictitate-toolkit'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Project', 'kopa-nictitate-toolkit'),
            'update_item' => __('Update Project', 'kopa-nictitate-toolkit'),
            'add_new_item' => __('Add New Project', 'kopa-nictitate-toolkit'),
            'new_item_name' => __('New Project Name', 'kopa-nictitate-toolkit'),
            'separate_items_with_commas' => __('Separate projects with commas', 'kopa-nictitate-toolkit'),
            'add_or_remove_items' => __('Add or remove category', 'kopa-nictitate-toolkit'),
            'choose_from_most_used' => __('Choose from the most used projects', 'kopa-nictitate-toolkit'),
            'menu_name' => __('Projects', 'kopa-nictitate-toolkit')
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'portfolio_project')
    );

    register_taxonomy('portfolio_project', 'portfolio', $taxonomy_category_args);

    #TAXONOMY TAG
    $taxonomy_tag_args = array(
        'public' => true,
        'hierarchical' => false,
        'labels' => array(
            'name' => __('Portfolio Tags', 'taxonomy general name', 'kopa-nictitate-toolkit'),
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
            'menu_name' => __('Tags', 'kopa-nictitate-toolkit')
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'portfolio_tag')
    );

    register_taxonomy('portfolio_tag', 'portfolio', $taxonomy_tag_args);

    flush_rewrite_rules(false);    
}