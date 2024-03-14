<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lion_Badge_CPT extends Lion_Badges {

	public function __construct() {
		add_action( 'init', array( $this, 'register_custom_post_type' ) );
	}

	/**
	 * Register a custom post type called "badge"
	 */
	public function register_custom_post_type() {
		$labels = array(
			'name'                  => _x( 'Badges', 'Post type general name', 'lionplugins' ),
			'singular_name'         => _x( 'Badge', 'Post type singular name', 'lionplugins' ),
			'menu_name'             => _x( 'Badges', 'Admin Menu text', 'lionplugins' ),
			'name_admin_bar'        => _x( 'Badge', 'Add New on Toolbar', 'lionplugins' ),
			'add_new'               => __( 'Add New', 'lionplugins' ),
			'add_new_item'          => __( 'Add New Badge', 'lionplugins' ),
			'new_item'              => __( 'New Badge', 'lionplugins' ),
			'edit_item'             => __( 'Edit Badge', 'lionplugins' ),
			'view_item'             => __( 'View Badge', 'lionplugins' ),
			'all_items'             => __( 'All Badges', 'lionplugins' ),
			'search_items'          => __( 'Search Badges', 'lionplugins' ),
			'parent_item_colon'     => __( 'Parent Badges:', 'lionplugins' ),
			'not_found'             => __( 'No badges found.', 'lionplugins' ),
			'not_found_in_trash'    => __( 'No badges found in Trash.', 'lionplugins' ),
			'featured_image'        => _x( 'Badge Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'lionplugins' ),
			'set_featured_image'    => _x( 'Set badge image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'lionplugins' ),
			'remove_featured_image' => _x( 'Remove badge image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'lionplugins' ),
			'use_featured_image'    => _x( 'Use as badge image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'lionplugins' ),
			'archives'              => _x( 'Badge archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'lionplugins' ),
			'insert_into_item'      => _x( 'Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'lionplugins' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'lionplugins' ),
			'filter_items_list'     => _x( 'Filter badges list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'lionplugins' ),
			'items_list_navigation' => _x( 'Badges list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'lionplugins' ),
			'items_list'            => _x( 'Badges list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'lionplugins' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'badge' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'			 => 'dashicons-pressthis',
			'supports'           => array( 'title' ),
		);

		register_post_type( 'lion_badge', $args );
	}
}

new Lion_Badge_CPT();
