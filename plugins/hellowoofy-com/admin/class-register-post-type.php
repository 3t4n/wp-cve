<?php
/**
 * Display slider on front end.
 *
 * PHP version 7
 *
 * @package  Register_Post_Type
 */

/**
 * Display slider on front end.
 *
 * Template Class
 *
 * @package  Register_Post_Type
 */
class Register_Post_Type {

	/** Create the custom post types */
	public function __construct() {

		/* Register custom post types */
		add_action( 'init', array( $this, 'mws_custom_post_type' ) );
	}

	/**
	 * Register custom post type.
	 *
	 * @since    1.0.3
	 */
	public function mws_custom_post_type() {
		$total_path   = plugin_dir_url( __FILE__ );
		$menu_icon  = dirname( $total_path ) . '/admin/assets/img/icon.png';

		// Set UI labels for Custom Post Type.

		$labels = array(

			'name'                => _x( 'WebStories', 'Post Type General Name' ),

			'singular_name'       => _x( 'WebStory', 'Post Type Singular Name' ),

			'menu_name'           => __( 'HelloWoofy.com, Smart Marketing for Underdogs' ),

			'parent_item_colon'   => __( 'Parent WebStory' ),

			// 'all_items'           => __( 'All WebStories' ),

			'view_item'           => __( 'View WebStory' ),

			'add_new_item'        => __( 'Add New WebStory' ),

			'add_new'             => __( 'Add New' ),

			'edit_item'           => __( 'Edit WebStory' ),

			'update_item'         => __( 'Update WebStory' ),

			'search_items'        => __( 'Search WebStory' ),

			'not_found'           => __( 'Not Found' ),

			'not_found_in_trash'  => __( 'Not found in Trash' ),

		);

		// Set other options for Custom Post Type.

		$args = array(

			'label'               => __( 'webstories', 'storefront' ),

			'description'         => __( 'HelloWoofy Web Stories' ),

			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
			'taxonomies'          => array( 'genres' ),
			'hierarchical'        => false,

			'public'              => true,

			'show_ui'             => true,

			'show_in_menu'        => false,

			// 'menu_icon'           => $menu_icon,

				  'show_in_nav_menus'   => false,

			'show_in_admin_bar'   => false,

			'menu_position'       => 5,

			'can_export'          => true,

			'has_archive'         => true,

			'exclude_from_search' => false,

			'publicly_queryable'  => true,

			'capability_type'     => 'post',

			'capabilities'        => array( 'create_posts' => false ),

			'map_meta_cap' => true,

			'show_in_rest' => true,

		);

		// Registering your Custom Post Type.

		register_post_type( 'webstories', $args );

	}

}

new Register_Post_Type();

