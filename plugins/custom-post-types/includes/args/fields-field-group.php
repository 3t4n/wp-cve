<?php

defined( 'ABSPATH' ) || exit;

return array(
	'id'         => CPT_UI_PREFIX . '_field',
	'label'      => __( 'Field group settings', 'custom-post-types' ),
	'supports'   => array(
		array(
			'type' => \CPT_Field_Groups::SUPPORT_TYPE_CPT,
			'id'   => CPT_UI_PREFIX . '_field',
		),
	),
	'position'   => 'normal',
	'order'      => 0,
	'admin_only' => true,
	'fields'     => array(
		array( //id
			'key'      => 'id',
			'label'    => __( 'ID', 'custom-post-types' ),
			'info'     => __( 'Field group ID.', 'custom-post-types' ),
			'required' => true,
			'type'     => 'text',
			'extra'    => array(
				'placeholder' => __( 'ex: custom-options', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => '',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //position
			'key'      => 'position',
			'label'    => __( 'Position', 'custom-post-types' ),
			'info'     => __( 'If set to "NORMAL" it will be shown at the bottom of the central column, if "SIDEBAR" it will be shown in the sidebar.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'select',
			'extra'    => array(
				'placeholder' => __( 'NORMAL', 'custom-post-types' ) . ' - ' . __( 'Default', 'custom-post-types' ),
				'multiple'    => false,
				'options'     => array(
					'normal'   => __( 'NORMAL', 'custom-post-types' ) . ' - ' . __( 'Default', 'custom-post-types' ),
					'side'     => __( 'SIDEBAR', 'custom-post-types' ),
					'advanced' => __( 'ADVANCED', 'custom-post-types' ),
				),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => '',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //order
			'key'      => 'order',
			'label'    => __( 'Order', 'custom-post-types' ),
			'info'     => __( 'Field groups with a lower order will appear first', 'custom-post-types' ),
			'required' => false,
			'type'     => 'number',
			'extra'    => array(
				'placeholder' => __( 'ex: 10', 'custom-post-types' ),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => '',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		array( //supports
			'key'      => 'supports',
			'label'    => __( 'Assignment', 'custom-post-types' ),
			'info'     => __( 'Choose for which CONTENT TYPE use this field group.', 'custom-post-types' ),
			'required' => false,
			'type'     => 'select',
			'extra'    => array(
				'multiple' => true,
				'options'  => cpt_utils()->get_contents_options(),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => '',
				'id'     => '',
				'layout' => 'horizontal',
			),
		),
		cpt_utils()->get_ui_yesno_field( //admin_only
			'admin_only',
			__( 'Administrators only', 'custom-post-types' ),
			__( 'If set to "YES" only the administrators can create / modify these contents, if "NO" all the roles with the minimum capacity of "edit_posts".', 'custom-post-types' )
		),
		cpt_utils()->get_ui_yesno_field( //show_in_rest
			'show_in_rest',
			__( 'Show in rest', 'custom-post-types' ),
			__( 'If set to "YES" and the assigned content type is supported by REST API the meta values will be added to the response.', 'custom-post-types' )
		),
		cpt_utils()->get_args( 'fields-repeater' ),
	),
);
