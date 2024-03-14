<?php

defined( 'ABSPATH' ) || exit;

return array(
	'description'         => sprintf( __( 'Post type created with the "%s" plugin.', 'custom-post-types' ), CPT_NAME ),
	'public'              => true,
	'hierarchical'        => false,
	'exclude_from_search' => false,
	'show_ui'             => true,
	'show_in_menu'        => true,
	'show_in_rest'        => true,
	'menu_icon'           => 'dashicons-tag',
	'capabilities'        => array(
		'edit_post'          => 'edit_posts',
		'read_post'          => 'edit_posts',
		'delete_post'        => 'edit_posts',
		'edit_posts'         => 'edit_posts',
		'edit_others_posts'  => 'edit_posts',
		'delete_posts'       => 'edit_posts',
		'publish_posts'      => 'edit_posts',
		'read_private_posts' => 'edit_posts',
	),
	'supports'            => array( 'title' ),
	'has_archive'         => false,
	'rewrite'             => array(
		'with_front' => false,
	),
);
