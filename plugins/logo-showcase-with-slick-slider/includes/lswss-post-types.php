<?php
/**
 * Register Post type functionality
 *
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to register post type
 * 
 * @since 1.0
 */
function lswss_register_post_type() {

	$lswss_post_lbls = apply_filters( 'lswssp_post_type_labels', array(
							'name'                 	=> __('Logo Showcase', 'logo-showcase-with-slick-slider'),
							'singular_name'        	=> __('Logo Showcase', 'logo-showcase-with-slick-slider'),
							'add_new'              	=> __('Add Logo Showcase', 'logo-showcase-with-slick-slider'),
							'add_new_item'         	=> __('Add New Logo Showcase', 'logo-showcase-with-slick-slider'),
							'edit_item'            	=> __('Edit Logo Showcase', 'logo-showcase-with-slick-slider'),
							'new_item'             	=> __('New Logo Showcase', 'logo-showcase-with-slick-slider'),
							'view_item'            	=> __('View Logo Showcase', 'logo-showcase-with-slick-slider'),
							'search_items'         	=> __('Search Logo Showcase', 'logo-showcase-with-slick-slider'),
							'not_found'            	=> __('No logo showcase found', 'logo-showcase-with-slick-slider'),
							'not_found_in_trash'   	=> __('No logo showcase found in Trash', 'logo-showcase-with-slick-slider'),
							'menu_name'           	=> __('Logo Showcase', 'logo-showcase-with-slick-slider')
						));

	$lswss_post_args = array(
		'labels'				=> $lswss_post_lbls,
		'show_ui'             	=> true,
		'public'              	=> false,
		'query_var'           	=> false,
		'rewrite'             	=> false,
		'hierarchical'        	=> false,
		'capability_type'     	=> 'post',
		'menu_icon'				=> 'dashicons-format-gallery',
		'supports'            	=> apply_filters( 'lswssp_post_type_supports', array('title') ),
	);

	// Register slick slider post type
	register_post_type( LSWSS_POST_TYPE, apply_filters( 'lswssp_registered_post_type_args', $lswss_post_args ) );
}

// Action to register plugin post type
add_action('init', 'lswss_register_post_type');

/**
 * Function to update post message for team showcase
 * 
 * @since 1.0
 */
function lswss_post_updated_messages( $messages ) {

	global $post;

	$messages[LSWSS_POST_TYPE] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Logo Showcase updated.', 'logo-showcase-with-slick-slider' ) ),
		2 => __( 'Logo Showcase Custom field updated.', 'logo-showcase-with-slick-slider' ),
		3 => __( 'Logo Showcase Custom field deleted.', 'logo-showcase-with-slick-slider' ),
		4 => __( 'Logo Showcase updated.', 'logo-showcase-with-slick-slider' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Logo Showcase restored to revision from %s', 'logo-showcase-with-slick-slider' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Logo Showcase published.', 'logo-showcase-with-slick-slider' ) ),
		7 => __( 'Logo Showcase saved.', 'logo-showcase-with-slick-slider' ),
		8 => sprintf( __( 'Logo Showcase submitted.', 'logo-showcase-with-slick-slider' ) ),
		9 => sprintf( __( 'Logo Showcase scheduled for: <strong>%1$s</strong>.', 'logo-showcase-with-slick-slider' ),
		  date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ) ),
		10 => sprintf( __( 'Logo Showcase draft updated.', 'logo-showcase-with-slick-slider' ) ),
	);
	
	return $messages;
}

// Filter to update post message
add_filter( 'post_updated_messages', 'lswss_post_updated_messages' );