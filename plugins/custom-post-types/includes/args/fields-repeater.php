<?php

defined( 'ABSPATH' ) || exit;

return array( // fields
	'key'      => 'fields',
	'label'    => __( 'Fields list', 'custom-post-types' ),
	'info'     => '',
	'required' => false,
	'type'     => 'repeater',
	'extra'    => array(
		'fields' => array(
			array( //label
				'key'      => 'label',
				'label'    => __( 'Label', 'custom-post-types' ),
				'info'     => false,
				'required' => true,
				'type'     => 'text',
				'extra'    => array(),
				'wrap'     => array(
					'width'  => '40',
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
			),
			array( //key
				'key'      => 'key',
				'label'    => __( 'Key', 'custom-post-types' ),
				'info'     => false,
				'required' => true,
				'type'     => 'text',
				'extra'    => array(),
				'wrap'     => array(
					'width'  => '40',
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
			),
			cpt_utils()->get_ui_yesno_field( //required
				'required',
				__( 'Required', 'custom-post-types' ),
				'',
				'NO',
				'',
				'20',
				''
			),
			array( //type
				'key'      => 'type',
				'label'    => __( 'Type', 'custom-post-types' ),
				'info'     => false,
				'required' => true,
				'type'     => 'select',
				'extra'    => array(
					'multiple' => false,
					'options'  => cpt_field_groups()->get_available_fields_label(),
				),
				'wrap'     => array(
					'width'  => '40',
					'class'  => 'cpt-repeater-field-type',
					'id'     => '',
					'layout' => '',
				),
			),
			array( //info
				'key'      => 'info',
				'label'    => __( 'Info', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'text',
				'extra'    => array(),
				'wrap'     => array(
					'width'  => '60',
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
			),
			array( //wrap_width
				'key'      => 'wrap_width',
				'label'    => __( 'Container width', 'custom-post-types' ) . ' (%)',
				'info'     => false,
				'required' => false,
				'type'     => 'number',
				'extra'    => array(
					'placeholder' => '100',
					'min'         => 1,
					'max'         => 100,
				),
				'wrap'     => array(
					'width'  => '25',
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
				'parent'   => '',
			),
			array( //wrap_layout
				'key'      => 'wrap_layout',
				'label'    => __( 'Container layout', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'select',
				'extra'    => array(
					'placeholder' => __( 'VERTICAL', 'custom-post-types' ) . ' - ' . __( 'Default', 'custom-post-types' ),
					'multiple'    => false,
					'options'     => array(
						'vertical'   => __( 'VERTICAL', 'custom-post-types' ) . ' - ' . __( 'Default', 'custom-post-types' ),
						'horizontal' => __( 'HORIZONTAL', 'custom-post-types' ),
					),
				),
				'wrap'     => array(
					'width'  => '25',
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
			),
			array( //wrap_class
				'key'      => 'wrap_class',
				'label'    => __( 'Container class', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'text',
				'extra'    => array(),
				'wrap'     => array(
					'width'  => '25',
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
			),
			array( //wrap_id
				'key'      => 'wrap_id',
				'label'    => __( 'Container id', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'text',
				'extra'    => array(),
				'wrap'     => array(
					'width'  => '25',
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
			),
		),
	),
	'wrap'     => array(
		'width' => '',
		'class' => '',
		'id'    => '',
	),
);
