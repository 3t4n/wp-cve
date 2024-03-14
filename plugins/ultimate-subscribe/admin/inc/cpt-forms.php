<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
 
// Register Custom Post Type
function ultimate_subscribe_forms_register() {

	$labels = array(
		'name'                  => _x( 'Forms', 'Post Type General Name', 'ultimate-subscribe' ),
		'singular_name'         => _x( 'Form', 'Post Type Singular Name', 'ultimate-subscribe' ),
		'menu_name'             => __( 'Forms', 'ultimate-subscribe' ),
		'name_admin_bar'        => __( 'Forms', 'ultimate-subscribe' ),
		'all_items'             => __( 'All Forms', 'ultimate-subscribe' ),
		'add_new_item'          => __( 'Add New Form', 'ultimate-subscribe' ),
		'add_new'               => __( 'Add Form', 'ultimate-subscribe' ),
		'new_item'              => __( 'New Form', 'ultimate-subscribe' ),
		'edit_item'             => __( 'Edit Form', 'ultimate-subscribe' ),
		'update_item'           => __( 'Update Form', 'ultimate-subscribe' ),
		'view_item'             => __( 'View Form', 'ultimate-subscribe' ),
		'search_items'          => __( 'Search Form', 'ultimate-subscribe' ),
		'not_found'             => __( 'No Form found', 'ultimate-subscribe' ),
		'not_found_in_trash'    => __( 'No Form found in Trash', 'ultimate-subscribe' ),
		'items_list'            => __( 'Forms list', 'ultimate-subscribe' ),
		'items_list_navigation' => __( 'Forms list navigation', 'ultimate-subscribe' ),		
	);
	$args = array(
		'label'                 => __( 'Form', 'ultimate-subscribe' ),
		'description'           => __( 'Form Description', 'ultimate-subscribe' ),
		'labels'                => $labels,
		'supports'              => array('title'),
		'hierarchical'          => true,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'u_subscribe_forms', $args );

}
add_action( 'init', 'ultimate_subscribe_forms_register', 0 );

?>