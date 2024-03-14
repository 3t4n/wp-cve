<?php
/* ====================================================================
|  POST TYPES
|  Additional custom post types
'---------------------------------------------------------------------- */

// Register the sullivan_slideshows custom post type
if ( ! function_exists( 'sullivan_compat_register_custom_post_types' ) ) {
	function sullivan_compat_register_custom_post_types(){

		$args = array(
			'label'               => __( 'Slideshows', 'sullivan-compatibility' ),
			'description'         => __( 'Post type for slideshows.', 'sullivan-compatibility' ),
			'labels'              => array(
				'name'               => _x( 'Slideshows', 'Post Type General Name', 'sullivan-compatibility' ),
				'singular_name'      => _x( 'Slideshow', 'Post Type Singular Name', 'sullivan-compatibility' ),
				'menu_name'          => __( 'Slideshows', 'sullivan-compatibility' ),
				'name_admin_bar'     => __( 'Slideshow', 'sullivan-compatibility' ),
				'archives'           => __( 'Slide archive', 'sullivan-compatibility' ),
				'all_items'          => __( 'All slides', 'sullivan-compatibility' ),
				'add_new_item'       => __( 'Create new slide', 'sullivan-compatibility' ),
				'add_new'            => __( 'Create new', 'sullivan-compatibility' ),
				'new_item'           => __( 'New slide', 'sullivan-compatibility' ),
				'edit_item'          => __( 'Edit slide', 'sullivan-compatibility' ),
				'update_item'        => __( 'Update slide', 'sullivan-compatibility' ),
				'view_item'          => __( 'Show slide', 'sullivan-compatibility' ),
				'search_items'       => __( 'Search slides', 'sullivan-compatibility' ),
				'not_found'          => __( 'No slides found', 'sullivan-compatibility' ),
				'not_found_in_trash' => __( 'Could not find any slides in the trash', 'sullivan-compatibility' ),
			),
			'supports'            => array( 'title', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 60,
			'menu_icon'           => 'dashicons-slides',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'show_in_rest'        => true,
			'capability_type'     => 'page',
		);

		register_post_type( 'sullivan_slideshow', $args );

	}
}
add_action( 'init', 'sullivan_compat_register_custom_post_types' );