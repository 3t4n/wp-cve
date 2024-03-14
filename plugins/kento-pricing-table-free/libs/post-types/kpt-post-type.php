<?php 

# Register Post Types
function wpt_register() {
	$labels = array(
		'name' => _x('KPT Free', 'post type general name'),
		'singular_name' => _x('KPT', 'post type singular name'),              
		'edit_item' => __('Edit KPT'),
		'view_item' => __('View KPT'),
		'search_items' => __('Search KPT'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => null,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title'),
		'menu_icon' => WPT_PLUGIN_PATH.'/css/kpt-menu.jpg',
	  );
	register_post_type( 'wpt' , $args );
}
add_action('init', 'wpt_register');



