<?php
/**
 * Register Post type functionality
 *
 * @package WP Slick Slider and Image Carousel
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to register post type
 * 
 * @since 1.0.0
 */
function wpsisac_register_post_type() {

	$wpsisac_slider_labels = array(
								'name'					=> __( 'Slick Slider', 'wp-slick-slider-and-image-carousel' ),
								'singular_name'			=> __( 'slick slider', 'wp-slick-slider-and-image-carousel' ),
								'all_items'				=> __( 'All Slides', 'wp-slick-slider-and-image-carousel' ),
								'add_new'				=> __( 'Add Slide', 'wp-slick-slider-and-image-carousel' ),
								'add_new_item'			=> __( 'Add New slide', 'wp-slick-slider-and-image-carousel' ),
								'edit_item'				=> __( 'Edit Slick Slider', 'wp-slick-slider-and-image-carousel' ),
								'new_item'				=> __( 'New Slick Slider', 'wp-slick-slider-and-image-carousel' ),
								'view_item'				=> __( 'View Slick Slider', 'wp-slick-slider-and-image-carousel' ),
								'search_items'			=> __( 'Search Slide', 'wp-slick-slider-and-image-carousel' ),
								'not_found'				=> __( 'No Slick Slider Items found', 'wp-slick-slider-and-image-carousel' ),
								'not_found_in_trash'	=> __( 'No Slick Slider Items found in Trash', 'wp-slick-slider-and-image-carousel' ),
								'parent_item_colon'		=> '',
								'menu_name'				=> __( 'Slick Slider', 'wp-slick-slider-and-image-carousel' ),
								'featured_image'		=> __( 'Slide Image', 'wp-slick-slider-and-image-carousel' ),
								'set_featured_image'	=> __( 'Set slide image', 'wp-slick-slider-and-image-carousel' ),
								'remove_featured_image'	=> __( 'Remove slide image', 'wp-slick-slider-and-image-carousel' ),
								'use_featured_image'	=> __( 'Use as slide image', 'wp-slick-slider-and-image-carousel' ),
							);

	$wpsisac_slider_args = array(
								'labels'				=> $wpsisac_slider_labels,
								'public'				=> false,
								'show_ui'				=> true,
								'show_in_menu'			=> true,
								'query_var'				=> false,
								'rewrite'				=> false,
								'has_archive'			=> false,
								'hierarchical'			=> false,
								'exclude_from_search'	=> true,
								'capability_type'		=> 'post',
								'menu_icon'				=> 'dashicons-slides',
								'supports'				=> array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'publicize' )
							);

	register_post_type( WPSISAC_POST_TYPE, apply_filters( 'wpsisac_get_post_type_args', $wpsisac_slider_args ) );
}

// Action to register plugin post type
add_action( 'init', 'wpsisac_register_post_type' );

/**
 * Function to register taxonomy
 * 
 * @since 1.0.0
 */
function wpsisac_register_taxonomies() {

	$labels = array(
				'name'				=> __( 'Category', 'wp-slick-slider-and-image-carousel' ),
				'singular_name'		=> __( 'Category', 'wp-slick-slider-and-image-carousel' ),
				'search_items'		=> __( 'Search Category', 'wp-slick-slider-and-image-carousel' ),
				'all_items'			=> __( 'All Category', 'wp-slick-slider-and-image-carousel' ),
				'parent_item'		=> __( 'Parent Category', 'wp-slick-slider-and-image-carousel' ),
				'parent_item_colon'	=> __( 'Parent Category:', 'wp-slick-slider-and-image-carousel' ),
				'edit_item'			=> __( 'Edit Category', 'wp-slick-slider-and-image-carousel' ),
				'update_item'		=> __( 'Update Category', 'wp-slick-slider-and-image-carousel' ),
				'add_new_item'		=> __( 'Add New Category', 'wp-slick-slider-and-image-carousel' ),
				'new_item_name'		=> __( 'New Category Name', 'wp-slick-slider-and-image-carousel' ),
				'menu_name'			=> __( 'Category', 'wp-slick-slider-and-image-carousel' ),
			);

	$args = array(
				'labels'			=> $labels,
				'public'			=> false,
				'hierarchical'		=> true,
				'show_ui'			=> true,
				'show_admin_column'	=> true,
				'query_var'			=> true,
				'rewrite'			=> false,
			);

	register_taxonomy( 'wpsisac_slider-category', array( WPSISAC_POST_TYPE ), apply_filters( 'wpsisac_get_registered_cat_args', $args ) );
}

// Action to register plugin taxonomies
add_action( 'init', 'wpsisac_register_taxonomies' );