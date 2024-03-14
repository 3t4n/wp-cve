<?php

defined( 'ABSPATH' ) || exit;

return array(
	'description'       => sprintf( __( 'Taxonomy created with the "%s" plugin.', 'custom-post-types' ), CPT_NAME ),
	'public'            => true,
	'hierarchical'      => false,
	// 'publicly_queryable' => false,
	'show_ui'           => true,
	'show_in_menu'      => true,
	// 'show_in_nav_menus' => true,
	// 'show_in_admin_bar' => false,
	'show_in_rest'      => true,
	'show_admin_column' => true,
	'capabilities'      => array(
		'manage_terms' => 'edit_posts',
		'edit_terms'   => 'edit_posts',
		'delete_terms' => 'edit_posts',
		'assign_terms' => 'edit_posts',
	),
	'rewrite'           => array(
		'with_front'   => false,
		'hierarchical' => true,
	),
	// 'query_var' =>  true,
);
