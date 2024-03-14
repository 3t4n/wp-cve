<?php

/**
 * Register Post type functionality
 *
 * @package Timeline and History Slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to register post type
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
function wpostahs_slider_init() {
	$wpostahs_slider_labels = array(
		'name'					=> __( 'Timeline Slider', 'timeline-and-history-slider' ),
		'singular_name'			=> __( 'Timeline Slider', 'timeline-and-history-slider' ),
		'add_new'				=> __( 'Add Slide', 'timeline-and-history-slider' ),
		'add_new_item'			=> __( 'Add New slide', 'timeline-and-history-slider' ),
		'edit_item'				=> __( 'Edit', 'timeline-and-history-slider' ),
		'new_item'				=> __( 'New', 'timeline-and-history-slider' ),
		'view_item'				=> __( 'View', 'timeline-and-history-slider' ),
		'search_items'			=> __( 'Search', 'timeline-and-history-slider' ),
		'not_found'				=> __( 'NoItems found', 'timeline-and-history-slider' ),
		'not_found_in_trash'	=> __( 'No Items found in Trash', 'timeline-and-history-slider' ),
		'_builtin'				=>  false,
		'parent_item_colon'		=> '',
		'menu_name'				=> __( 'Timeline Slider', 'admin menu', 'timeline-and-history-slider' ),
	);

	$wpostahs_slider_args = array(
		'labels'				=> $wpostahs_slider_labels,
		'public'				=> true,
		'publicly_queryable'	=> true,
		'exclude_from_search'	=> false,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'query_var'				=> true,
		'rewrite'				=> array(
									'slug' => 'timeline_slider_post',
									'with_front' => false
								),
		'capability_type'		=> 'post',
		'has_archive'			=> true,
		'hierarchical'			=> false,
		'menu_position'			=> 8,
		'menu_icon'				=> 'dashicons-feedback',
		'supports'				=> array('title','editor','thumbnail')
	);

	register_post_type('timeline_slider_post',$wpostahs_slider_args);
}
add_action('init', 'wpostahs_slider_init');

/**
 * Function to Register Taxonomy for timeline post type
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
function wpostahs_slider_taxonomies() {
	$labels = array(
		'name'				=> __( 'Category', 'timeline-and-history-slider' ),
		'singular_name'		=> __( 'Category', 'timeline-and-history-slider' ),
		'search_items'		=> __( 'Search Category', 'timeline-and-history-slider' ),
		'all_items'			=> __( 'All Category', 'timeline-and-history-slider' ),
		'parent_item'		=> __( 'Parent Category', 'timeline-and-history-slider' ),
		'parent_item_colon'	=> __( 'Parent Category' , 'timeline-and-history-slider' ),
		'edit_item'			=> __( 'Edit Category', 'timeline-and-history-slider' ),
		'update_item'		=> __( 'Update Category', 'timeline-and-history-slider' ),
		'add_new_item'		=> __( 'Add New Category', 'timeline-and-history-slider' ),
		'new_item_name'		=> __( 'New Category Name', 'timeline-and-history-slider' ),
		'menu_name'			=> __( 'Timeline Category', 'timeline-and-history-slider' ),
	);

	$args = array(
		'labels'			=> $labels,
		'hierarchical'		=> true,
		'show_ui'			=> true,
		'show_admin_column'	=> true,
		'query_var'			=> true,
		'rewrite'			=> array( 'slug' => 'wpostahs-slider-category' ),
	);

	register_taxonomy( 'wpostahs-slider-category', array( 'timeline_slider_post' ), $args );
}
add_action( 'init', 'wpostahs_slider_taxonomies');