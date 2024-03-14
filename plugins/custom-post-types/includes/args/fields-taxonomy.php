<?php

defined( 'ABSPATH' ) || exit;

return array(
	'id'         => CPT_UI_PREFIX . '_tax',
	'label'      => __( 'Taxonomy settings', 'custom-post-types' ),
	'supports'   => array(
		array(
			'type' => \CPT_Field_Groups::SUPPORT_TYPE_CPT,
			'id'   => CPT_UI_PREFIX . '_tax',
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
				'placeholder' => __( 'ex: Partner', 'custom-post-types' ),
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
				'placeholder' => __( 'ex: Partners', 'custom-post-types' ),
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
			'info'     => __( 'Taxonomy ID.', 'custom-post-types' ),
			'required' => true,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: partner', 'custom-post-types' ),
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
			'info'     => __( 'Permalink base for terms (if empty, plural is used).', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: partners', 'custom-post-types' ),
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
			'label'    => __( 'Assignment', 'custom-post-types' ),
			'info'     => __( 'Choose for which POST TYPE use this taxonomy.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'select',
			'extra'    => array(
				'multiple' => true,
				'options'  => cpt_utils()->get_post_types_options(),
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
			__( 'If set to "YES" it will be shown in the frontend and will have a permalink and a archive template.', 'custom-post-types' ),
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
			__( 'If set to "YES" it will be possible to set a parent TAXONOMY (as for the posts categories).', 'custom-post-types' ),
			'NO',
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
				'placeholder' => __( 'ex: Add new partner', 'custom-post-types' ),
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
				'placeholder' => __( 'ex: Edit partner', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_new_item_name
			'key'      => 'labels_new_item_name',
			'label'    => __( 'New item name', 'custom-post-types' ),
			'info'     => __( 'The new item name text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Partner name', 'custom-post-types' ),
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
				'placeholder' => __( 'ex: View partner', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_update_item
			'key'      => 'labels_update_item',
			'label'    => __( 'Update item', 'custom-post-types' ),
			'info'     => __( 'The update item text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Update partner', 'custom-post-types' ),
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
				'placeholder' => __( 'ex: Search partners', 'custom-post-types' ),
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
				'placeholder' => __( 'ex: No partner found', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //labels_parent_item
			'key'      => 'labels_parent_item',
			'label'    => __( 'Parent item', 'custom-post-types' ),
			'info'     => __( 'The parent item text.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: Parent partner', 'custom-post-types' ),
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
				'placeholder' => __( 'ex: Parent partner', 'custom-post-types' ),
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
				'placeholder' => __( 'ex: All partners', 'custom-post-types' ),
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
