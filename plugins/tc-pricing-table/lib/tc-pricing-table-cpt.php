<?php
// Custom Post Type Setup
add_action( 'init', 'tc_pricing_table_post_type' );
function tc_pricing_table_post_type() {
	$labels = array(
		'name' => __('TC Pricing Table', 'tc-pricing-table'),
		'singular_name' => __('TC Pricing Table', 'tc-pricing-table'),
		'all_items' => __('All Tables', 'tc-pricing-table' ),
		'add_new' => __('Add New Table', 'tc-pricing-table'),
		'add_new_item' => __('Add New Table', 'tc-pricing-table'),
		'edit_item' => __('Edit Table', 'tc-pricing-table'),
		'new_item' => __('New Pricing Table', 'tc-pricing-table'),
		'view_item' => __('View Pricing Table', 'tc-pricing-table'),
		'search_items' => __('Search Pricing Table', 'tc-pricing-table'),
		'not_found' => __('No Pricing Table', 'tc-pricing-table'),
		'not_found_in_trash' => __('Pricing Table found in Trash', 'tc-pricing-table'),
		'parent_item_colon' => '',
		'menu_name' => __('TC Pricing Table', 'tc-pricing-table') // this name will be shown on the menu
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'exclude_from_search' => true,
		'publicly_queryable' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'page',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => 21,
		'menu_icon' => 'dashicons-editor-table',
		'supports' => array('title')
	);
	register_post_type('tcpricingtable', $args);
}
