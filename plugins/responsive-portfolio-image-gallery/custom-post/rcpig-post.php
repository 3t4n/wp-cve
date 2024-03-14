<?php
/*
 *  Responsive Portfolio Image Gallery 1.2
 *  By @realwebcare - https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
function register_rcp_image_gallery() {
	$rcpig_labels =  apply_filters( 'rcpig_labels', array(
		'name'                => _x( 'Portfolios', 'Post Type General Name', 'rcpig' ),
		'singular_name'       => _x( 'Portfolio', 'Post Type Singular Name', 'rcpig' ),
		'menu_name'           => __( 'Portfolio', 'rcpig' ),
		'parent_item_colon'   => __( 'Parent Portfolio:', 'rcpig' ),
		'all_items'           => __( 'All Portfolios', 'rcpig' ),
		'view_item'           => __( 'View Portfolio', 'rcpig' ),
		'add_new_item'        => __( 'Add New Portfolio', 'rcpig' ),
		'add_new'             => __( 'New Portfolio', 'rcpig' ),
		'edit_item'           => __( 'Edit Portfolio', 'rcpig' ),
		'update_item'         => __( 'Update Portfolio', 'rcpig' ),
		'search_items'        => __( 'Search Portfolio', 'rcpig' ),
		'not_found'           => __( 'No Portfolio Found', 'rcpig' ),
		'not_found_in_trash'  => __( 'No Portfolio in Trash', 'rcpig' ),
	) );
	$rewrite = array(
		'slug'                => 'portfolio',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$rcpig_args = array(
		'label'					=> __( 'Portfolio', 'rcpig' ),
		'description'			=> __( 'Lightweight portfolio gallery grid plugin', 'rcpig' ),
		'labels' 				=> $rcpig_labels,
		'supports' 				=> apply_filters('rcpig_supports', array( 'title', 'editor', 'thumbnail', 'author' ) ),
		'taxonomies'			=> array( 'rcpig-category' ),	
		'hierarchical' 			=> false,
		'public' 				=> true,
		'show_ui' 				=> true,
		'show_in_menu' 			=> true,
		'show_in_admin_bar'		=> true,
		'menu_position'			=> null,
		'menu_icon'				=> 'dashicons-format-image',
		'can_export'			=> true,
		'has_archive' 			=> true,
		'exclude_from_search'	=> false,
		'publicly_queryable'	=> true,
		'rewrite'				=> $rewrite,
		'query_var'		 		=> true,
		'capability_type'		=> 'page',
	);
	register_post_type( 'rcpig', apply_filters( 'rcpig_post_type_args', $rcpig_args ) );
}
add_action('init', 'register_rcp_image_gallery', 0);

// Register Theme Features (feature image for portfolio)
if ( ! function_exists('rcpig_theme_support') ) {
	function rcpig_theme_support()  {
		// Add theme support for Featured Images
		add_theme_support( 'post-thumbnails', array( 'rcpig' ) );
	}
	// Hook into the 'after_setup_theme' action
	add_action( 'after_setup_theme', 'rcpig_theme_support' );
}

function rcp_image_gallery_taxonomies() {
	$labels = array(
		'name'                       => _x( 'Portfolio Categories', 'Taxonomy General Name', 'rcpig' ),
		'singular_name'              => _x( 'Portfolio Category', 'Taxonomy Singular Name', 'rcpig' ),
		'menu_name'                  => __( 'Portfolio Category', 'rcpig' ),
		'all_items'                  => __( 'All Categories', 'rcpig' ),
		'parent_item'                => __( 'Parent Category', 'rcpig' ),
		'parent_item_colon'          => __( 'Parent Category:', 'rcpig' ),
		'new_item_name'              => __( 'New Category Name', 'rcpig' ),
		'add_new_item'               => __( 'Add New Category', 'rcpig' ),
		'edit_item'                  => __( 'Edit Category', 'rcpig' ),
		'update_item'                => __( 'Update Category', 'rcpig' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'rcpig' ),
		'search_items'               => __( 'Search categories', 'rcpig' ),
		'add_or_remove_items'        => __( 'Add or remove Categories', 'rcpig' ),
		'choose_from_most_used'      => __( 'Choose from the most used categories', 'rcpig' ),
		'not_found'                  => __( 'Not Found', 'rcpig' ),
	);
	$rewrite = array(
		'slug'                       => 'portfolio-category',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'rcpig-category', array( 'rcpig' ), $args );
}
add_action( 'init', 'rcp_image_gallery_taxonomies', 0 );
?>