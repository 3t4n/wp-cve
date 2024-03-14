<?php
// Custom Post Type Setup
function tcpc_post_type() {
	$labels = array(
		'name' => __('All Products', 'tcpc'),
		'singular_name' => __('Product Catalog', 'tcpc'),
		'add_new' => __('Add New Product', 'tcpc'),
		'add_new_item' => __('Add New Product', 'tcpc'),
		'all_items' => __('All Products', 'tcpc' ),
		'edit_item' => __('Edit Product', 'tcpc'),
		'new_item' => __('New Product', 'tcpc'),
		'view_item' => __('View Product', 'tcpc'),
		'search_items' => __('Search Product', 'tcpc'),
		'not_found' => __('No Product', 'tcpc'),
		'not_found_in_trash' => __('No Product found in Trash', 'tcpc'),
		'parent_item_colon' => '',
		'menu_name' => __('TC Products', 'tcpc') // this name will be shown on the menu
	);
	$args = array(
		'labels' => $labels,
		'has_archive' => true,
		'supports' => array('title','thumbnail','editor'),
		 'taxonomies' => array( '' ),
		'public' => true,
		'capability_type' => 'post',
		'rewrite' => array( 'slug' => 'tcpc' ),
		'menu_position' => 21,
		'menu_icon' =>plugins_url('/tc-product-catalog/assets/images/tcpc.png'),

	);
	register_post_type('tcpc', $args);
}

 add_action( 'init', 'tcpc_post_type' );
