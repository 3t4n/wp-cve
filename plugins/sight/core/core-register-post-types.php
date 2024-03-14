<?php
/**
 * Register post types.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    Sight
 */

/**
 * Register custom post types.
 */
function sight_register_custom_post_types() {

	register_post_type(
		'sight-projects',
		array(
			'labels'             => array(
				'name'               => esc_html__( 'Projects', 'sight' ),
				'singular_name'      => esc_html__( 'Project', 'sight' ),
				'menu_name'          => esc_html__( 'Projects', 'sight' ),
				'parent_item_colon'  => esc_html__( 'Parent Project', 'sight' ),
				'all_items'          => esc_html__( 'All Projects', 'sight' ),
				'view_item'          => esc_html__( 'View Project', 'sight' ),
				'add_new_item'       => esc_html__( 'Add New Project', 'sight' ),
				'add_new'            => esc_html__( 'Add New', 'sight' ),
				'edit_item'          => esc_html__( 'Edit Project', 'sight' ),
				'update_item'        => esc_html__( 'Update Project', 'sight' ),
				'search_items'       => esc_html__( 'Search Project', 'sight' ),
				'not_found'          => esc_html__( 'Not Found', 'sight' ),
				'not_found_in_trash' => esc_html__( 'Not found in Trash', 'sight' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'projects' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'page-attributes', 'custom-fields' ),
		)
	);
}
add_action( 'init', 'sight_register_custom_post_types' );

/**
 * Register custom taxonomies.
 */
function sight_register_custom_taxonomies() {

	register_taxonomy(
		'sight-categories',
		array( 'sight-projects' ),
		array(
			'label'             => '',
			'labels'            => array(
				'name'              => esc_html__( 'Categories', 'sight' ),
				'singular_name'     => esc_html__( 'Categories', 'sight' ),
				'search_items'      => esc_html__( 'Search Categories', 'sight' ),
				'all_items'         => esc_html__( 'All Categories', 'sight' ),
				'view_item '        => esc_html__( 'View Category', 'sight' ),
				'parent_item'       => esc_html__( 'Parent Category', 'sight' ),
				'parent_item_colon' => esc_html__( 'Parent Category:', 'sight' ),
				'edit_item'         => esc_html__( 'Edit Category', 'sight' ),
				'update_item'       => esc_html__( 'Update Category', 'sight' ),
				'add_new_item'      => esc_html__( 'Add New Category', 'sight' ),
				'new_item_name'     => esc_html__( 'New Types Name', 'sight' ),
				'menu_name'         => esc_html__( 'Categories', 'sight' ),
			),
			'description'       => '',
			'public'            => sight_is_archive(),
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array(
				'slug' => 'categories',
			),
			'capabilities'      => array(),
			'meta_box_cb'       => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'sight_register_custom_taxonomies' );
