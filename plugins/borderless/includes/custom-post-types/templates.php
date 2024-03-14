<?php
/**
 * Anzu Page Templates Library
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Register Custom Post Type
function anzu_templates() {

	$labels = array(
		'name'                  => _x( 'Templates', 'Post Type General Name', 'borderless' ),
		'singular_name'         => _x( 'Template', 'Post Type Singular Name', 'borderless' ),
		'menu_name'             => __( 'Templates', 'borderless' ),
		'name_admin_bar'        => __( 'Template', 'borderless' ),
		'archives'              => __( 'Item Archives', 'borderless' ),
		'attributes'            => __( 'Item Attributes', 'borderless' ),
		'parent_item_colon'     => __( 'Parent Item:', 'borderless' ),
		'all_items'             => __( 'All Items', 'borderless' ),
		'add_new_item'          => __( 'Add New Item', 'borderless' ),
		'add_new'               => __( 'Add New', 'borderless' ),
		'new_item'              => __( 'New Item', 'borderless' ),
		'edit_item'             => __( 'Edit Item', 'borderless' ),
		'update_item'           => __( 'Update Item', 'borderless' ),
		'view_item'             => __( 'View Item', 'borderless' ),
		'view_items'            => __( 'View Items', 'borderless' ),
		'search_items'          => __( 'Search Item', 'borderless' ),
		'not_found'             => __( 'Not found', 'borderless' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'borderless' ),
		'featured_image'        => __( 'Featured Image', 'borderless' ),
		'set_featured_image'    => __( 'Set featured image', 'borderless' ),
		'remove_featured_image' => __( 'Remove featured image', 'borderless' ),
		'use_featured_image'    => __( 'Use as featured image', 'borderless' ),
		'insert_into_item'      => __( 'Insert into item', 'borderless' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'borderless' ),
		'items_list'            => __( 'Items list', 'borderless' ),
		'items_list_navigation' => __( 'Items list navigation', 'borderless' ),
		'filter_items_list'     => __( 'Filter items list', 'borderless' ),
	);
	$args = array(
		'label'                 => __( 'Template', 'borderless' ),
		'description'           => __( 'Anzu Templates Library', 'borderless' ),
		'labels'                => $labels,
		'show_in_rest'          => true,
		'supports'              => array( 'title', 'editor', 'revisions' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'anzu_templates', $args );

}
add_action( 'init', 'anzu_templates', 0 );


function anzu_templates_menu() {
	add_submenu_page( 
		'anzu',
		__( 'Templates', 'borderless' ),
		__( 'Templates', 'borderless' ),
		'manage_options',
		'edit.php?post_type=anzu_templates',
		'',
		1
	);
}

add_action('admin_menu', 'anzu_templates_menu');