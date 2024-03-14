<?php 
/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function GS_Portfolio() {
	$labels = array(
		'name'               => _x( 'Portfolios', 'gsportfolio' ),
		'singular_name'      => _x( 'Portfolio', 'gsportfolio' ),
		'menu_name'          => _x( 'GS Portfolios', 'admin menu', 'gsportfolio' ),
		'name_admin_bar'     => _x( 'GS Portfolio', 'add new on admin bar', 'gsportfolio' ),
		'add_new'            => _x( 'Add New Portfolio', 'portfolio', 'gsportfolio' ),
		'add_new_item'       => __( 'Add New Portfolio', 'gsportfolio' ),
		'new_item'           => __( 'New Portfolio', 'gsportfolio' ),
		'edit_item'          => __( 'Edit Portfolio', 'gsportfolio' ),
		'view_item'          => __( 'View Portfolio', 'gsportfolio' ),
		'all_items'          => __( 'All Portfolios', 'gsportfolio' ),
		'search_items'       => __( 'Search Portfolios', 'gsportfolio' ),
		'parent_item_colon'  => __( 'Parent Portfolios:', 'gsportfolio' ),
		'not_found'          => __( 'No portfolios found.', 'gsportfolio' ),
		'not_found_in_trash' => __( 'No portfolios found in Trash.', 'gsportfolio' ),
	);

	$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'portfolio' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 34,
			'menu_icon'          => 'dashicons-schedule',
			'supports'           => array( 'title', 'editor', 'thumbnail'),
		);
		register_post_type( 'gs-portfolio', $args );
}

add_action( 'init', 'GS_Portfolio' );


// Register Theme Features (feature image for portfolio)
if ( ! function_exists('gs_p_theme_support') ) {

	function gs_p_theme_support()  {
		// Add theme support for Featured Images
		add_theme_support( 'post-thumbnails', array( 'gs-portfolio' ) );
		// Add Shortcode support in text widget
		add_filter('widget_text', 'do_shortcode'); 
	}

	// Hook into the 'after_setup_theme' action
	add_action( 'after_setup_theme', 'gs_p_theme_support' );
}