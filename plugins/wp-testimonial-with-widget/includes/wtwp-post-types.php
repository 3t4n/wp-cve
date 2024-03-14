<?php
/**
 * Function to create custom post type
 * 
 * @package WP Testimonials with rotator widget Pro
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to register post types
 * 
 * @since 1.0
 */
function wtwp_register_post_types() {

	$labels = array(
		'name'					=> __( 'SP Testimonials', 'wp-testimonial-with-widget' ),
		'singular_name'			=> __( 'Testimonial', 'wp-testimonial-with-widget' ),
		'add_new'				=> __( 'Add New', 'wp-testimonial-with-widget' ),
		'add_new_item'			=> __( 'Add New Testimonial', 'wp-testimonial-with-widget' ),
		'edit_item'				=> __( 'Edit Testimonial', 'wp-testimonial-with-widget' ),
		'new_item'				=> __( 'New Testimonial', 'wp-testimonial-with-widget' ),
		'all_items'				=> __( 'All Testimonial', 'wp-testimonial-with-widget' ),
		'view_item'				=> __( 'View Testimonial', 'wp-testimonial-with-widget' ),
		'search_items'			=> __( 'Search Testimonial', 'wp-testimonial-with-widget' ),
		'not_found'				=> __( 'No Testimonial Found', 'wp-testimonial-with-widget' ),
		'not_found_in_trash'	=> __( 'No Testimonial Found IN Trash', 'wp-testimonial-with-widget' ),
		'parent_item_colon'		=> '',
		'menu_name'				=> __( 'WP Testimonials', 'wp-testimonial-with-widget' )
	);

	$archive_slug	= apply_filters( 'sp_testimonials_archive_slug', __( 'wp_testimonial', 'wp-testimonial-with-widget' ) );

	$args = array(
		'labels'				 => $labels,
		'public'				 => true,
		'publicly_queryable'	 => true,
		'show_ui'				 => true,
		'show_in_menu'			 => true,
		'query_var'				 => true,
		'hierarchical'			 => false,
		'capability_type'		 => 'post',
		'has_archive'			 => $archive_slug,
		'rewrite'				=> array( 
										'slug'			=> apply_filters( 'sp_testimonials_single_slug', 'testimonial' ),
										'with_front'	=> false
									),
		'supports'				 => array( 'title', 'author' ,'editor', 'thumbnail', 'page-attributes', 'publicize', 'wpcom-markdown' ),			
		'menu_icon'				 => 'dashicons-format-quote'
	);

	register_post_type( 'testimonial', apply_filters( 'sp_testimonials_post_type_args', $args ) );
}
add_action( 'init', 'wtwp_register_post_types' );

/**
 * Function to register taxonomy
 * 
 * @since 1.0
 */
function wtwp_register_taxonomies() {

	$labels = array(
		'name'				=> __( 'Category', 'wp-testimonial-with-widget' ),
		'singular_name'		=> __( 'Category', 'wp-testimonial-with-widget' ),
		'search_items'		=> __( 'Search Category', 'wp-testimonial-with-widget' ),
		'all_items'			=> __( 'All Category', 'wp-testimonial-with-widget' ),
		'parent_item'		=> __( 'Parent Category', 'wp-testimonial-with-widget' ),
		'parent_item_colon'	=> __( 'Parent Category', 'wp-testimonial-with-widget' ),
		'edit_item'			=> __( 'Edit Category', 'wp-testimonial-with-widget' ),
		'update_item'		=> __( 'Update Category', 'wp-testimonial-with-widget' ),
		'add_new_item'		=> __( 'Add New Category', 'wp-testimonial-with-widget' ),
		'new_item_name'		=> __( 'New Category Name', 'wp-testimonial-with-widget' ),
		'menu_name'			=> __( 'Category', 'wp-testimonial-with-widget' ),
	);

	$args = array(
		'labels'			=> $labels,
		'hierarchical'		=> true,
		'show_ui'			=> true,
		'show_admin_column'	=> true,
		'query_var'			=> true,
		'rewrite'			=> array( 
									'slug'			=> apply_filters( 'sp_testimonials_cat_slug', 'testimonial-category' ), 
									'with_front'	=> false,
								),
	);

	register_taxonomy( 'testimonial-category', array( 'testimonial' ), $args );
}
add_action( 'init', 'wtwp_register_taxonomies' );