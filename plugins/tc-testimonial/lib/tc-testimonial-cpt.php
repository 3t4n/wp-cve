<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Custom Post Type Setup
function tcodes_testimonial_post_type() {
	$labels = array(
		'name' => __('All Testimonials', 'TCODES'),
		'singular_name' => __('TC Testimonial', 'TCODES'),
		'add_new' => __('Add New', 'TCODES'),
		'all_items' => __('All Testimonials', 'TCODES' ),
		'add_new_item' => __('Add New Testimonial', 'TCODES'),
		'edit_item' => __('Edit Testimonial', 'TCODES'),
		'new_item' => __('New Testimonial', 'TCODES'),
		'view_item' => __('View Testimonial', 'TCODES'),
		'search_items' => __('Search Testimonial', 'TCODES'),
		'not_found' => __('No Testimonial', 'TCODES'),
		'not_found_in_trash' => __('No Testimonial found in Trash', 'TCODES'),
		'parent_item_colon' => '',
		'menu_name' => __('TC Testimonial', 'TCODES') // this name will be shown on the menu
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
		'menu_position' => 5,
		'menu_icon' =>'dashicons-testimonial',
		'supports' => array('title','thumbnail','editor')
	);
	register_post_type('tctestimonial', $args);
}

 add_action( 'init', 'tcodes_testimonial_post_type' );

 // Adding a taxonomy for the Slider post type
 function themescode_tctestimonial_taxonomy() {
 		$args = array('hierarchical' => true);
 		register_taxonomy( 'tctestimonial_category', 'tctestimonial', $args );
 	}
  add_action( 'init', 'themescode_tctestimonial_taxonomy', 0 );
