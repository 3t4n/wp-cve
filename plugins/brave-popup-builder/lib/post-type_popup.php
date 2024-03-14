<?php

add_action( 'init', 'bravepop_register_popup' );
/**
 * Register a book post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function bravepop_register_popup() {
	$labels = array(
		'name'               => _x( 'Popups', 'post type general name', 'bravepop' ),
		'singular_name'      => _x( 'Popup', 'post type singular name', 'bravepop' ),
		'menu_name'          => _x( 'Popups', 'admin menu', 'bravepop' ),
		'name_admin_bar'     => _x( 'Popup', 'add new on admin bar', 'bravepop' ),
		'add_new'            => _x( 'Add New', 'popup', 'bravepop' ),
		'add_new_item'       => __( 'Add New Popup', 'bravepop' ),
		'new_item'           => __( 'New Popup', 'bravepop' ),
		'edit_item'          => __( 'Edit Popup', 'bravepop' ),
		'view_item'          => __( 'View Popup', 'bravepop' ),
		'all_items'          => __( 'All Popups', 'bravepop' ),
		'search_items'       => __( 'Search Popups', 'bravepop' ),
		'parent_item_colon'  => __( 'Parent Popups:', 'bravepop' ),
		'not_found'          => __( 'No popups found.', 'bravepop' ),
		'not_found_in_trash' => __( 'No popups found in Trash.', 'bravepop' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'bravepop' ),
		'public'             => true,
		'publicly_queryable' => false,
		'show_ui'            => false,
		'show_in_menu'       => false,
      'show_in_rest'       => true,
      'exclude_from_search' => true,
		'rest_base'          => 'popups',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'popup' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'excerpt', 'thumbnail' ),
	);

	register_post_type( 'popup', $args );
}
