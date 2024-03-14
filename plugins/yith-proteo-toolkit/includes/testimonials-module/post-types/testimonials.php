<?php
/**
 * Testimonials post type
 *
 * @since 1.1.0
 * @package YITH_Proteo_tookit
 */

/**
 * Register post type
 *
 * @return void
 */
function yith_proteo_toolkit_register_testimonials_post_type() {

	$labels = array(
		'name'                  => esc_html_x( 'Testimonials', 'Post Type General Name', 'yith-proteo-toolkit' ),
		'singular_name'         => esc_html_x( 'Testimonial', 'Post Type Singular Name', 'yith-proteo-toolkit' ),
		'menu_name'             => esc_html__( 'Testimonials', 'yith-proteo-toolkit' ),
		'name_admin_bar'        => esc_html__( 'Testimonial', 'yith-proteo-toolkit' ),
		'archives'              => esc_html__( 'Testimonial archives', 'yith-proteo-toolkit' ),
		'attributes'            => esc_html__( 'Testimonial attributes', 'yith-proteo-toolkit' ),
		'all_items'             => esc_html__( 'All testimonials', 'yith-proteo-toolkit' ),
		'add_new_item'          => esc_html__( 'Add new testimonial', 'yith-proteo-toolkit' ),
		'add_new'               => esc_html__( 'Add new testimonial', 'yith-proteo-toolkit' ),
		'new_item'              => esc_html__( 'New testimonial', 'yith-proteo-toolkit' ),
		'edit_item'             => esc_html__( 'Edit testimonial', 'yith-proteo-toolkit' ),
		'update_item'           => esc_html__( 'Update testimonial', 'yith-proteo-toolkit' ),
		'view_item'             => esc_html__( 'View testimonial', 'yith-proteo-toolkit' ),
		'view_items'            => esc_html__( 'View testimonials', 'yith-proteo-toolkit' ),
		'search_items'          => esc_html__( 'Search testimonials', 'yith-proteo-toolkit' ),
		'featured_image'        => esc_html__( 'Profile picture', 'yith-proteo-toolkit' ),
		'set_featured_image'    => esc_html__( 'Set profile picture', 'yith-proteo-toolkit' ),
		'remove_featured_image' => esc_html__( 'Remove profile picture', 'yith-proteo-toolkit' ),
		'use_featured_image'    => esc_html__( 'Use as profile picture', 'yith-proteo-toolkit' ),
		'items_list'            => esc_html__( 'Testimonials list', 'yith-proteo-toolkit' ),
		'items_list_navigation' => esc_html__( 'Testimonials list navigation', 'yith-proteo-toolkit' ),
		'filter_items_list'     => esc_html__( 'Filter testimonials list', 'yith-proteo-toolkit' ),
	);
	$args   = array(
		'label'               => esc_html__( 'Testimonials', 'yith-proteo-toolkit' ),
		'description'         => esc_html__( 'Testimonials for your project', 'yith-proteo-toolkit' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-awards',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'proteo_testimonials', $args );

}

add_action( 'init', 'yith_proteo_toolkit_register_testimonials_post_type' );

add_filter( 'enter_title_here', 'yith_proteo_toolkit_testimonials_title_placeholder' );

/**
 * Customize placeholder for custom post type new record
 *
 * @param string $input Placeholder string.
 * @return string
 */
function yith_proteo_toolkit_testimonials_title_placeholder( $input ) {
	if ( 'proteo_testimonials' === get_post_type() ) {
		return esc_html__( 'Testimonial name', 'yith-proteo-toolkit' );
	}

	return $input;
}


/**
 * Register testimonials taxonomy
 *
 * @return void
 */
function yith_proteo_toolkit_create_testimonials_tax() {

	$labels = array(
		'name'          => esc_html_x( 'Testimonials taxonomy', 'taxonomy general name', 'yith-proteo-toolkit' ),
		'singular_name' => esc_html_x( 'Testimonial taxonomy', 'taxonomy singular name', 'yith-proteo-toolkit' ),
		'search_items'  => esc_html__( 'Search testimonials taxonomy', 'yith-proteo-toolkit' ),
		'edit_item'     => esc_html__( 'Edit testimonials taxonomy', 'yith-proteo-toolkit' ),
		'update_item'   => esc_html__( 'Update testimonials taxonomy', 'yith-proteo-toolkit' ),
		'add_new_item'  => esc_html__( 'Add new testimonials taxonomy', 'yith-proteo-toolkit' ),
		'new_item_name' => esc_html__( 'New testimonials taxonomy name', 'yith-proteo-toolkit' ),
		'menu_name'     => esc_html__( 'Testimonials taxonomies', 'yith-proteo-toolkit' ),
	);
	$args   = array(
		'labels'             => $labels,
		'description'        => esc_html__( 'Testimonials taxonomy', 'yith-proteo-toolkit' ),
		'hierarchical'       => false,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => false,
		'show_tagcloud'      => false,
		'show_in_quick_edit' => false,
		'show_admin_column'  => true,
		'show_in_rest'       => true,
	);
	register_taxonomy( 'proteo_testimonials_tax', array( 'proteo_testimonials' ), $args );

}
add_action( 'init', 'yith_proteo_toolkit_create_testimonials_tax' );
