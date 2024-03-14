<?php
// custom post register
add_action( 'init', 'wpbforwpbakery_custom_posts');
function wpbforwpbakery_custom_posts() {

	$labels = array(
		'name'                  => _x( 'Templates', 'Template', 'wpbforwpbakery' ),
		'singular_name'         => _x( 'Template', 'Template', 'wpbforwpbakery' ),
		'menu_name'             => esc_html__( 'WC Page Builder', 'wpbforwpbakery' ),
		'name_admin_bar'        => esc_html__( 'WC Page Builder', 'wpbforwpbakery' ),
		'archives'              => esc_html__( 'Template Archives', 'wpbforwpbakery' ),
		'parent_item_colon'     => esc_html__( 'Parent Template:', 'wpbforwpbakery' ),
		'all_items'             => esc_html__( 'All Templates', 'wpbforwpbakery' ),
		'add_new_item'          => esc_html__( 'Add New Template', 'wpbforwpbakery' ),
		'add_new'               => esc_html__( 'Add New', 'wpbforwpbakery' ),
		'new_item'              => esc_html__( 'New Template', 'wpbforwpbakery' ),
		'edit_item'             => esc_html__( 'Edit Template', 'wpbforwpbakery' ),
		'update_item'           => esc_html__( 'Update Template', 'wpbforwpbakery' ),
		'view_item'             => esc_html__( 'View Template', 'wpbforwpbakery' ),
		'search_items'          => esc_html__( 'Search Template', 'wpbforwpbakery' ),
		'not_found'             => esc_html__( 'Not found', 'wpbforwpbakery' ),
		'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'wpbforwpbakery' ),
		'featured_image'        => esc_html__( 'Featured Image', 'wpbforwpbakery' ),
		'set_featured_image'    => esc_html__( 'Set featured image', 'wpbforwpbakery' ),
		'remove_featured_image' => esc_html__( 'Remove featured image', 'wpbforwpbakery' ),
		'use_featured_image'    => esc_html__( 'Use as featured image', 'wpbforwpbakery' ),
		'insert_into_item'      => esc_html__( 'Insert into item', 'wpbforwpbakery' ),
		'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'wpbforwpbakery' ),
		'items_list'            => esc_html__( 'Items list', 'wpbforwpbakery' ),
		'items_list_navigation' => esc_html__( 'Items list navigation', 'wpbforwpbakery' ),
		'filter_items_list'     => esc_html__( 'Filter items list', 'wpbforwpbakery' ),
	);
	$args = array(
		'label'                 => esc_html__( 'Templates', 'wpbforwpbakery' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'wpbforwpbakery_options',
		'menu_position'         => 5,
		'menu_icon'   			=> 'dashicons-editor-kitchensink',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'wpbfwpb_template', $args );

}