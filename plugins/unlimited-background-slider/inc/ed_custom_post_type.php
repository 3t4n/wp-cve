<?php
/*
* Creating a function to create our CPT
*/

if( !function_exists('ed_bg_slider_post_type') ){
function ed_bg_slider_post_type() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'Background Slider', 'ed_ubs' ),
		'singular_name'       => _x( 'Background Slider', 'ed_ubs' ),
		'menu_name'           => __( 'Background Slider', 'ed_ubs' ),
		'parent_item_colon'   => __( 'Parent Slider', 'ed_ubs' ),
		'all_items'           => __( 'All Slides', 'ed_ubs' ),
		'view_item'           => __( 'View Slides', 'ed_ubs' ),
		'add_new_item'        => __( 'Add New Slides', 'ed_ubs' ),
		'add_new'             => __( 'Add New Slides', 'ed_ubs' ),
		'edit_item'           => __( 'Edit Slides', 'ed_ubs' ),
		'update_item'         => __( 'Update Slides', 'ed_ubs' ),
		'search_items'        => __( 'Search Slides', 'ed_ubs' ),
		'not_found'           => __( 'Not Found', 'ed_ubs' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'ed_ubs' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title'),
		
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'public' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'menu_position'       => 9999,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
	);
	
	// Registering your Custom Post Type
	register_post_type( 'ed_bg_slider', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action( 'init', 'ed_bg_slider_post_type', 0 );

}