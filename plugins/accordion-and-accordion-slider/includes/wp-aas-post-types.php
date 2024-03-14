<?php
/**
 * Register Post type functionality
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Function to register post type
 * 
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_register_post_type() {

	$wp_aas_post_lbls = apply_filters( 'wp_aas_post_labels', array(
								'name'					=> __( 'Accordion slider', 'accordion-and-accordion-slider' ),
								'singular_name'			=> __( 'Accordion slider', 'accordion-and-accordion-slider' ),
								'add_new'				=> __( 'Add Accordion slider', 'accordion-and-accordion-slider' ),
								'add_new_item'			=> __( 'Add New Accordion slider', 'accordion-and-accordion-slider' ),
								'edit_item'				=> __( 'Edit Accordion slider', 'accordion-and-accordion-slider' ),
								'new_item'				=> __( 'New Accordion slider', 'accordion-and-accordion-slider' ),
								'view_item'				=> __( 'View Accordion slider', 'accordion-and-accordion-slider' ),
								'search_items'			=> __( 'Search Accordion slider', 'accordion-and-accordion-slider' ),
								'not_found'				=> __( 'No Accordion slider', 'accordion-and-accordion-slider' ),
								'not_found_in_trash'	=> __( 'No Accordion slider found in Trash', 'accordion-and-accordion-slider' ),								
								'menu_name'				=> __( 'Accordion slider', 'accordion-and-accordion-slider' ),
							));

	$wp_aas_slider_args = array(
								'labels'				=> $wp_aas_post_lbls,
								'public'				=> true,
								'show_ui'				=> true,
								'query_var'				=> false,
								'rewrite'				=> true,
								'capability_type'		=> 'post',
								'hierarchical'			=> false,
								'menu_icon'				=> 'dashicons-format-gallery',
								'supports'				=> array('title')
	);

	// Register slick slider post type
	register_post_type( WP_AAS_POST_TYPE, apply_filters( 'wp_aas_registered_post_type_args', $wp_aas_slider_args ) );
}

// Action to register plugin post type
add_action('init', 'wp_aas_register_post_type');

/**
 * Function to update post message for portfolio
 * 
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */
function wp_aas_post_updated_messages( $messages ) {

	global $post, $post_ID;

	$messages[WP_AAS_POST_TYPE] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Image Gallery updated.', 'accordion-and-accordion-slider' ) ),
		2 => __( 'Custom field updated.', 'accordion-and-accordion-slider' ),
		3 => __( 'Custom field deleted.', 'accordion-and-accordion-slider' ),
		4 => __( 'Image Gallery updated.', 'accordion-and-accordion-slider' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Image Gallery restored to revision from %s', 'accordion-and-accordion-slider' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Image Gallery published.', 'accordion-and-accordion-slider' ) ),
		7 => __( 'Image Gallery saved.', 'accordion-and-accordion-slider' ),
		8 => sprintf( __( 'Image Gallery submitted.', 'accordion-and-accordion-slider' ) ),
		9 => sprintf( __( 'Image Gallery scheduled for: <strong>%1$s</strong>.', 'accordion-and-accordion-slider' ),
		  date_i18n( __( 'M j, Y @ G:i', 'accordion-and-accordion-slider' ), strtotime( $post->post_date ) ) ),
		10 => sprintf( __( 'Image Gallery draft updated.', 'accordion-and-accordion-slider' ) ),
	);

	return $messages;
}

// Filter to update slider post message
add_filter( 'post_updated_messages', 'wp_aas_post_updated_messages' );