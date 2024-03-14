<?php

defined( 'ABSPATH' ) || exit;

return array(
	'id'         => CPT_UI_PREFIX,
	'label'      => __( 'Post type settings', 'custom-post-types' ),
	'supports'   => array(
		array(
			'type' => \CPT_Field_Groups::SUPPORT_TYPE_CPT,
			'id'   => CPT_UI_PREFIX,
		),
	),
	'position'   => 'normal',
	'order'      => 0,
	'admin_only' => true,
	'fields'     => array(
		cpt_utils()->get_ui_args_title_field(),
		array( //singular
			'key'      => 'singular',
			'label'    => __( 'Singular', 'custom-post-types' ),
			'info'     => __( 'Singular name.', 'custom-post-types' ),
			'required' => true,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Product', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => '',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //plural
			'key'      => 'plural',
			'label'    => __( 'Plural', 'custom-post-types' ),
			'info'     => __( 'Plural name.', 'custom-post-types' ),
			'required' => true,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Products', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => '',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //id
			'key'      => 'id',
			'label'    => __( 'ID', 'custom-post-types' ),
			'info'     => __( 'Post type ID.', 'custom-post-types' ),
			'required' => true,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: products', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => '',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //slug
			'key'      => 'slug',
			'label'    => __( 'Slug', 'custom-post-types' ),
			'info'     => __( 'Permalink base for posts (if empty, plural is used).', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: product', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'slug-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //supports
			'key'      => 'supports',
			'label'    => __( 'Supports', 'custom-post-types' ),
			'info'     => __( 'Set the available components when editing a post.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'select',
			'extra'    => array(
				'multiple' => true,
				'options'  => array(
					'title'           => __( 'Title', 'custom-post-types' ),
					'editor'          => __( 'Editor', 'custom-post-types' ),
					'comments'        => __( 'Comments', 'custom-post-types' ),
					'revisions'       => __( 'Revisions', 'custom-post-types' ),
					'trackbacks'      => __( 'Trackbacks', 'custom-post-types' ),
					'author'          => __( 'Author', 'custom-post-types' ),
					'excerpt'         => __( 'Excerpt', 'custom-post-types' ),
					'page-attributes' => __( 'Page attributes', 'custom-post-types' ),
					'thumbnail'       => __( 'Thumbnail', 'custom-post-types' ),
					'custom-fields'   => __( 'Custom fields', 'custom-post-types' ),
					'post-formats'    => __( 'Post formats', 'custom-post-types' ),
				),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => '',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //menu_icon
			'key'      => 'menu_icon',
			'label'    => __( 'Menu icon', 'custom-post-types' ),
			'info'     => __( 'Url to the icon, base64-encoded SVG using a data URI, name of a <a href="https://developer.wordpress.org/resource/dashicons" target="_blank" rel="nofolow">Dashicons</a> e.g. \'dashicons-chart-pie\'.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'dashicons-tag', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => '',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		cpt_utils()->get_ui_yesno_field( //public
			'public',
			__( 'Public', 'custom-post-types' ),
			__( 'If set to "YES" it will be shown in the frontend and will have a permalink and a single template.', 'custom-post-types' ),
			'YES',
			'advanced-field'
		),
		cpt_utils()->get_ui_yesno_field( //admin_only
			'admin_only',
			__( 'Administrators only', 'custom-post-types' ),
			__( 'If set to "YES" only the administrators can create / modify these contents, if "NO" all the roles with the minimum capacity of "edit_posts".', 'custom-post-types' )
		),
		cpt_utils()->get_ui_yesno_field( //hierarchical
			'hierarchical',
			__( 'Hierarchical', 'custom-post-types' ),
			__( 'If set to "YES" it will be possible to set a parent POST TYPE (as for pages).', 'custom-post-types' ),
			'NO',
			'advanced-field'
		),
		cpt_utils()->get_ui_yesno_field( //has_archive
			'has_archive',
			__( 'Has archive', 'custom-post-types' ),
			__( 'If set to "YES" the url of the post type archive will be reachable.', 'custom-post-types' ),
			'NO',
			'advanced-field'
		),
		cpt_utils()->get_ui_yesno_field( //exclude_from_search
			'exclude_from_search',
			__( 'Exclude from search', 'custom-post-types' ),
			__( 'If set to "YES" these posts will be excluded from the search results.', 'custom-post-types' ),
			'NO',
			'advanced-field'
		),
		cpt_utils()->get_ui_yesno_field( //show_in_rest
			'show_in_rest',
			__( 'Show in rest', 'custom-post-types' ),
			__( 'If set to "YES" API endpoints will be available (required for Gutenberg and other builders).', 'custom-post-types' ),
			'YES',
			'advanced-field'
		),
		cpt_utils()->get_ui_labels_title_field(),
		array( //labels_add_new_item
			'key'      => 'labels_add_new_item',
			'label'    => __( 'Add new item', 'custom-post-types' ),
			'info'     => __( 'The add new item text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Add new product', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_edit_item
			'key'      => 'labels_edit_item',
			'label'    => __( 'Edit item', 'custom-post-types' ),
			'info'     => __( 'The edit item text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Edit product', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_new_item
			'key'      => 'labels_new_item',
			'label'    => __( 'New item', 'custom-post-types' ),
			'info'     => __( 'The new item text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: New product', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_view_item
			'key'      => 'labels_view_item',
			'label'    => __( 'View item', 'custom-post-types' ),
			'info'     => __( 'The view item text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: View product', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_view_items
			'key'      => 'labels_view_items',
			'label'    => __( 'View items', 'custom-post-types' ),
			'info'     => __( 'The view items text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: View products', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_search_items
			'key'      => 'labels_search_items',
			'label'    => __( 'Search items', 'custom-post-types' ),
			'info'     => __( 'The search item text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Search products', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_not_found
			'key'      => 'labels_not_found',
			'label'    => __( 'Not found', 'custom-post-types' ),
			'info'     => __( 'The not found text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: No product found', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_not_found_in_trash
			'key'      => 'labels_not_found_in_trash',
			'label'    => __( 'Not found in trash', 'custom-post-types' ),
			'info'     => __( 'The not found in trash text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: No product found in trash', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_parent_item_colon
			'key'      => 'labels_parent_item_colon',
			'label'    => __( 'Parent item', 'custom-post-types' ),
			'info'     => __( 'The parent item text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Parent product', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_all_items
			'key'      => 'labels_all_items',
			'label'    => __( 'All items', 'custom-post-types' ),
			'info'     => __( 'The all items text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: All products', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_archives
			'key'      => 'labels_archives',
			'label'    => __( 'Archivies', 'custom-post-types' ),
			'info'     => __( 'The archives text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Product archives', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		cpt_utils()->get_ui_advanced_switch_field(),
	),
);
