<?php
use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
return [
	'template' => [
		'labels'             => [
			'name'          => esc_html__('My Email Templates', 'emailcreator'),
			'singular_name' => esc_html__('My Email Templates', 'emailcreator'),
			'menu_name'     => esc_html__('My Email Templates', 'emailcreator'),
		],
		'description'        => esc_html__('Description.', 'emailcreator'),
		'public'             => true,
		'menu_icon'          => 'dashicons-admin-site',
		'publicly_queryable' => true,
		'show_ui'            => false,
		'show_in_menu'       => false,
		'query_var'          => true,
		'show_in_rest'       => true,
		'postType'           => AutoPrefix::namePrefix('templates'),
		'rest_base'          => AutoPrefix::namePrefix('templates'),
		'rewrite'            => [
			'slug' => AutoPrefix::namePrefix('templates')
		],
		'map_meta_cap'       => true,
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => [
			'title',
			'thumbnail'
		]
	]
];
