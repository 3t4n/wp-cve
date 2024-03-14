<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'kianfr_demo_customizer';

//
// Create customize options
//
KIANFR::createCustomizeOptions( $prefix );

//
// Create a section
//
KIANFR::createSection( $prefix, [
	'title'    => 'KIANFR - Overview',
	'priority' => 1,
	'fields'   => [

		//
		// A text field
		//
		[
			'id'    => 'opt-overview-text',
			'type'  => 'text',
			'title' => 'Text',
		],

		[
			'id'    => 'opt-overview-textarea',
			'type'  => 'textarea',
			'title' => 'Textarea',
			'help'  => 'The help text of the field.',
		],

		[
			'id'    => 'opt-upload',
			'type'  => 'upload',
			'title' => 'Upload',
		],

		[
			'id'    => 'opt-overview-switcher',
			'type'  => 'switcher',
			'title' => 'Switcher',
			'label' => 'The label text of the switcher.',
		],

		[
			'id'      => 'opt-overview-color',
			'type'    => 'color',
			'title'   => 'Color',
			'default' => '#3498db',
		],

		[
			'id'    => 'opt-overview-checkbox',
			'type'  => 'checkbox',
			'title' => 'Checkbox',
			'label' => 'The label text of the checkbox.',
		],

		[
			'id'      => 'opt-overview-radio',
			'type'    => 'radio',
			'title'   => 'Radio',
			'options' => [
				'yes' => 'Yes, Please.',
				'no'  => 'No, Thank you.',
			],
			'default' => 'yes',
		],

		[
			'id'          => 'opt-overview-select',
			'type'        => 'select',
			'title'       => 'Select',
			'placeholder' => 'Select an option',
			'options'     => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

	],
] );

//
// Create a section
//
KIANFR::createSection( $prefix, [
	'id'       => 'fields',
	'title'    => 'KIANFR - Fields',
	'priority' => 2,
] );

//
// Field: text
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Text',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=text" target="_blank">Field: text</a>',
	'fields'      => [

		[
			'id'    => 'opt-text-1',
			'type'  => 'text',
			'title' => 'Text',
		],

		[
			'id'      => 'opt-text-2',
			'type'    => 'text',
			'title'   => 'Text with default',
			'default' => 'This is default value bla bla bla',
		],

		[
			'id'       => 'opt-text-3',
			'type'     => 'text',
			'title'    => 'Text field ingenuity',
			'subtitle' => 'The field of subtitle text.',
			'help'     => 'The field of help text.',
			'before'   => '<p>The field of before text.</p>',
			'after'    => '<p>The field of after text.</p>',
		],

		[
			'id'          => 'opt-text-4',
			'type'        => 'text',
			'title'       => 'Text with placeholder',
			'placeholder' => 'Typed something...',
		],

		[
			'id'         => 'opt-text-5',
			'type'       => 'text',
			'title'      => 'Text readonly',
			'attributes' => [
				'readonly' => 'readonly',
			],
			'default'    => 'readonly text field, can not be changed',
		],

		[
			'id'         => 'opt-text-6',
			'type'       => 'text',
			'title'      => 'Text with maxlength (5)',
			'attributes' => [
				'maxlength' => '5',
			],
			'default'    => 'abc',
		],

		[
			'id'         => 'opt-text-7',
			'type'       => 'text',
			'title'      => 'Text usign custom styles',
			'attributes' => [
				'style' => 'width: 100%; height: 40px; border-color: #93C054;',
			],
		],

		[
			'id'    => 'opt-text-8',
			'type'  => 'text',
			'after' => '<p>It shows full width if there is no field of title.</p>',
		],

	],
] );

//
// Field: textarea
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Textarea',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=textarea" target="_blank">Field: textrea</a>',
	'fields'      => [

		[
			'id'    => 'opt-textarea-1',
			'type'  => 'textarea',
			'title' => 'Textarea',
		],

		[
			'id'      => 'opt-textarea-2',
			'type'    => 'textarea',
			'title'   => 'Textarea wtih default',
			'default' => 'This is default value bla bla bla',
		],

		[
			'id'          => 'opt-textarea-3',
			'type'        => 'textarea',
			'title'       => 'Text with placeholder',
			'placeholder' => 'Typed something...',
		],

		[
			'id'         => 'opt-textarea-4',
			'type'       => 'textarea',
			'title'      => 'Textarea with shortcoder',
			'shortcoder' => 'kianfr_demo_shortcodes',
		],

		[
			'id'       => 'opt-textarea-5',
			'type'     => 'textarea',
			'title'    => 'Textarea field ingenuity',
			'subtitle' => 'The field of subtitle text.',
			'help'     => 'The field of help text.',
			'before'   => '<p>The field of before text.</p>',
			'after'    => '<p>The field of after text.</p>',
		],

		[
			'id'    => 'opt-textarea-6',
			'type'  => 'textarea',
			'after' => '<p>It shows full width if there is no field of title.</p>',
		],

	],
] );

//
// Field: select
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Select',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=select" target="_blank">Field: select</a>',
	'fields'      => [

		[
			'id'          => 'opt-select-1',
			'type'        => 'select',
			'title'       => 'Select',
			'placeholder' => 'Select an option',
			'options'     => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

		[
			'id'          => 'opt-select-2',
			'type'        => 'select',
			'title'       => 'Select with default',
			'placeholder' => 'Select an option',
			'options'     => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
			'default'     => 'opt-2',
		],

		[
			'id'          => 'opt-select-3',
			'type'        => 'select',
			'title'       => 'Select with group related options',
			'placeholder' => 'Select an option',
			'options'     => [
				'Group 1' => [
					'opt-1' => 'Option 1',
					'opt-2' => 'Option 2',
					'opt-3' => 'Option 3',
				],
				'Group 2' => [
					'opt-4' => 'Option 4',
					'opt-5' => 'Option 5',
					'opt-6' => 'Option 6',
				],
				'Group 3' => [
					'opt-7' => 'Option 7',
					'opt-8' => 'Option 8',
					'opt-9' => 'Option 9',
				],
			],
		],

		[
			'id'         => 'opt-select-4',
			'type'       => 'select',
			'title'      => 'Select with multiple choice',
			'multiple'   => true,
			'attributes' => [
				'style' => 'min-width: 200px;',
			],
			'options'    => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
				'opt-4' => 'Option 4',
				'opt-5' => 'Option 5',
				'opt-6' => 'Option 6',
			],
			'default'    => [ 'opt-2', 'opt-3' ],
		],

		[
			'type'    => 'notice',
			'style'   => 'info',
			'content' => 'Select with <strong>chosen</strong> style.',
		],

		[
			'id'          => 'opt-select-5',
			'type'        => 'select',
			'title'       => 'Select with Chosen',
			'chosen'      => true,
			'placeholder' => 'Select an option',
			'options'     => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
				'opt-4' => 'Option 4',
				'opt-5' => 'Option 5',
				'opt-6' => 'Option 6',
			],
		],

		[
			'id'          => 'opt-select-6',
			'type'        => 'select',
			'title'       => 'Select with multiple Chosen',
			'chosen'      => true,
			'multiple'    => true,
			'placeholder' => 'Select an option',
			'options'     => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
				'opt-4' => 'Option 4',
				'opt-5' => 'Option 5',
				'opt-6' => 'Option 6',
			],
		],

		[
			'id'          => 'opt-select-7',
			'type'        => 'select',
			'title'       => 'Select with multiple Chosen and Sortable',
			'chosen'      => true,
			'multiple'    => true,
			'sortable'    => true,
			'placeholder' => 'Select an option',
			'options'     => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
				'opt-4' => 'Option 4',
				'opt-5' => 'Option 5',
				'opt-6' => 'Option 6',
			],
			'default'     => [ 'opt-1', 'opt-2', 'opt-3' ],
		],

		[
			'id'          => 'opt-select-8',
			'type'        => 'select',
			'title'       => 'Select with multiple AJAX search Pages',
			'chosen'      => true,
			'multiple'    => true,
			'sortable'    => true,
			'ajax'        => true,
			'options'     => 'pages',
			'placeholder' => 'Select pages',
		],

		[
			'id'          => 'opt-select-9',
			'type'        => 'select',
			'title'       => 'Select with multiple AJAX search Posts',
			'chosen'      => true,
			'multiple'    => true,
			'sortable'    => true,
			'ajax'        => true,
			'options'     => 'posts',
			'placeholder' => 'Select posts',
		],

		[
			'id'          => 'opt-select-10',
			'type'        => 'select',
			'title'       => 'Select with AJAX search Category',
			'chosen'      => true,
			'ajax'        => true,
			'options'     => 'category',
			'placeholder' => 'Select a category',
		],

		[
			'type'    => 'notice',
			'style'   => 'info',
			'content' => 'Select with <strong>predefined wp query</strong> options.',
		],

		[
			'id'          => 'opt-select-11',
			'type'        => 'select',
			'title'       => 'Select with pages',
			'placeholder' => 'Select a page',
			'options'     => 'pages',
		],

		[
			'id'          => 'opt-select-12',
			'type'        => 'select',
			'title'       => 'Select with posts',
			'placeholder' => 'Select a post',
			'options'     => 'posts',
		],

		[
			'id'          => 'opt-select-13',
			'type'        => 'select',
			'title'       => 'Select with categories',
			'placeholder' => 'Select a category',
			'options'     => 'categories',
		],

		[
			'id'          => 'opt-select-14',
			'type'        => 'select',
			'title'       => 'Select with menus',
			'placeholder' => 'Select a menu',
			'options'     => 'menus',
		],

		[
			'id'          => 'opt-select-15',
			'type'        => 'select',
			'title'       => 'Select with locations',
			'placeholder' => 'Select a location',
			'options'     => 'locations',
		],

		[
			'id'          => 'opt-select-16',
			'type'        => 'select',
			'title'       => 'Select with sidebars',
			'placeholder' => 'Select a sidebar',
			'options'     => 'sidebars',
		],

		[
			'id'          => 'opt-select-17',
			'type'        => 'select',
			'title'       => 'Select with wp roles',
			'placeholder' => 'Select a role',
			'options'     => 'roles',
		],

		[
			'id'          => 'opt-select-18',
			'type'        => 'select',
			'title'       => 'Select with users',
			'placeholder' => 'Select a user',
			'options'     => 'users',
		],

		[
			'id'          => 'opt-select-19',
			'type'        => 'select',
			'title'       => 'Select with post type',
			'placeholder' => 'Select a post type',
			'options'     => 'post_types',
		],

		[
			'id'          => 'opt-select-20',
			'type'        => 'select',
			'title'       => 'Select with CPT (custom post type) posts',
			'placeholder' => 'Select a post',
			'options'     => 'posts',
			'query_args'  => [
				'post_type' => 'your_post_type_name',
			],
		],

		[
			'id'          => 'opt-select-21',
			'type'        => 'select',
			'title'       => 'Select with CPT (custom post type) categories',
			'placeholder' => 'Select a category',
			'options'     => 'categories',
			'query_args'  => [
				'taxonomy' => 'your_taxonomy_name',
			],
		],

	],
] );

//
// Field: checkbox
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Checkbox',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=checkbox" target="_blank">Field: checkbox</a>',
	'fields'      => [

		[
			'id'    => 'opt-checkbox-1',
			'type'  => 'checkbox',
			'title' => 'Checkbox',
			'label' => 'The label text of the checkbox.',
		],

		[
			'id'      => 'opt-checkbox-2',
			'type'    => 'checkbox',
			'title'   => 'Checkbox with default',
			'label'   => 'The label text of the checkbox.',
			'default' => true,
		],

		[
			'id'      => 'opt-checkbox-3',
			'type'    => 'checkbox',
			'title'   => 'Checkbox with multiple choice',
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

		[
			'id'      => 'opt-checkbox-4',
			'type'    => 'checkbox',
			'title'   => 'Checkbox inline with multiple choice',
			'inline'  => true,
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

		[
			'id'      => 'opt-checkbox-5',
			'type'    => 'checkbox',
			'title'   => 'Checkbox multiple choice with default',
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
			'default' => [ 'opt-1', 'opt-2' ],
		],

		[
			'id'      => 'opt-checkbox-6',
			'type'    => 'checkbox',
			'title'   => 'Checkbox with group related options',
			'options' => [
				'Group 1' => [
					'opt-1' => 'Option 1',
					'opt-2' => 'Option 2',
					'opt-3' => 'Option 3',
				],
				'Group 2' => [
					'opt-4' => 'Option 4',
					'opt-5' => 'Option 5',
					'opt-6' => 'Option 6',
				],
			],
		],

		[
			'id'      => 'opt-checkbox-7',
			'type'    => 'checkbox',
			'title'   => 'Checkbox testing on many items',
			'options' => [
				'opt-1'  => 'Option 1',
				'opt-2'  => 'Option 2',
				'opt-3'  => 'Option 3',
				'opt-4'  => 'Option 4',
				'opt-5'  => 'Option 5',
				'opt-6'  => 'Option 6',
				'opt-7'  => 'Option 7',
				'opt-8'  => 'Option 8',
				'opt-9'  => 'Option 9',
				'opt-10' => 'Option 10',
				'opt-11' => 'Option 11',
				'opt-12' => 'Option 12',
				'opt-13' => 'Option 13',
				'opt-14' => 'Option 14',
				'opt-15' => 'Option 15',
			],
			'after'   => 'Vertical scroll showing automatically after add many items',
		],

		[
			'type'    => 'notice',
			'style'   => 'info',
			'content' => 'Checkbox with <strong>predefined wp query</strong> options similar like <strong>select</strong> field. (see select field for all options models.)',
		],

		[
			'id'      => 'opt-checkbox-8',
			'type'    => 'checkbox',
			'title'   => 'Checkbox with categories',
			'options' => 'categories',
		],

	],
] );

//
// Field: radio
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Radio',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=radio" target="_blank">Field: radio</a>',
	'fields'      => [

		[
			'id'      => 'opt-radio-1',
			'type'    => 'radio',
			'title'   => 'Radio',
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

		[
			'id'      => 'opt-radio-2',
			'type'    => 'radio',
			'title'   => 'Radio with default',
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
			'default' => 'opt-2',
		],

		[
			'id'      => 'opt-radio-3',
			'type'    => 'radio',
			'title'   => 'Radio with inline style',
			'inline'  => true,
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

		[
			'id'      => 'opt-radio-4',
			'type'    => 'radio',
			'title'   => 'Radio with group related options',
			'options' => [
				'Group 1' => [
					'opt-1' => 'Option 1',
					'opt-2' => 'Option 2',
					'opt-3' => 'Option 3',
				],
				'Group 2' => [
					'opt-4' => 'Option 4',
					'opt-5' => 'Option 5',
					'opt-6' => 'Option 6',
				],
			],
		],

		[
			'id'      => 'opt-radio-5',
			'type'    => 'radio',
			'title'   => 'Radio testing on many items',
			'options' => [
				'opt-1'  => 'Option 1',
				'opt-2'  => 'Option 2',
				'opt-3'  => 'Option 3',
				'opt-4'  => 'Option 4',
				'opt-5'  => 'Option 5',
				'opt-6'  => 'Option 6',
				'opt-7'  => 'Option 7',
				'opt-8'  => 'Option 8',
				'opt-9'  => 'Option 9',
				'opt-10' => 'Option 10',
				'opt-11' => 'Option 11',
				'opt-12' => 'Option 12',
				'opt-13' => 'Option 13',
				'opt-14' => 'Option 14',
				'opt-15' => 'Option 15',
			],
			'desc'    => 'Vertical scroll showing automatically after add many items',
		],

		[
			'type'    => 'notice',
			'style'   => 'info',
			'content' => 'Radio with <strong>predefined wp query</strong> options similar like <strong>select</strong> field. (see select field for all options models.)',
		],

		[
			'id'      => 'opt-radio-6',
			'type'    => 'radio',
			'title'   => 'Radio with categories',
			'options' => 'categories',
		],

	],
] );

//
// Field: repeater
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Repeater',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=repeater" target="_blank">Field: repeater</a>',
	'fields'      => [

		[
			'id'     => 'opt-repeater-1',
			'type'   => 'repeater',
			'title'  => 'Repeater',
			'fields' => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
			],
		],

		[
			'id'      => 'opt-repeater-2',
			'type'    => 'repeater',
			'title'   => 'Repeater with default',
			'fields'  => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
			],
			'default' => [
				[
					'opt-text' => 'Text default 1',
				],
				[
					'opt-text' => 'Text default 2',
				],
			],
		],

		[
			'id'      => 'opt-repeater-3',
			'type'    => 'repeater',
			'title'   => 'Repeater with multiple fields',
			'fields'  => [
				[
					'id'    => 'opt-switcher',
					'type'  => 'switcher',
					'title' => 'Switcher',
				],
				[
					'id'    => 'opt-color',
					'type'  => 'color',
					'title' => 'Color',
				],
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
			],
			'default' => [
				[
					'opt-switcher' => false,
					'opt-color'    => '#3498db',
					'opt-text'     => 'Text default 1',
				],
			],
		],

		[
			'id'           => 'opt-repeater-4',
			'type'         => 'repeater',
			'title'        => 'Repeater with limited (min - max items)',
			'subtitle'     => 'The maximum/minimum number of items the user can add. (In this example min:1, max:3)',
			'button_title' => 'Add Text',
			'min'          => 1,
			'max'          => 3,
			'fields'       => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
			],
			'default'      => [
				[
					'opt-text' => 'Text default 1',
				],
				[
					'opt-text' => 'Text default 2',
				],
			],
		],

		[
			'id'       => 'opt-repeater-6',
			'type'     => 'repeater',
			'title'    => 'Repeater nested repeater',
			'subtitle' => 'Can be added unlimited nested repeater',
			'fields'   => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'     => 'opt-repeater-6-nested-1',
					'type'   => 'repeater',
					'title'  => 'Repeater',
					'fields' => [
						[
							'id'    => 'opt-text',
							'type'  => 'text',
							'title' => 'Text',
						],
					],
				],
			],
			'default'  => [
				[
					'opt-text'                => 'Text default 1',
					'opt-repeater-6-nested-1' => [
						[
							'opt-text' => 'Text default 1',
						],
						[
							'opt-text' => 'Text default 2',
						],
					],
				],
			],
		],

	],
] );

//
// Field: group
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Group',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=group" target="_blank">Field: group</a>',
	'fields'      => [

		[
			'id'     => 'opt-group-1',
			'type'   => 'group',
			'title'  => 'Group',
			'fields' => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'    => 'opt-switcher',
					'type'  => 'switcher',
					'title' => 'Switcher',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
		],

		[
			'id'      => 'opt-group-2',
			'type'    => 'group',
			'title'   => 'Group with default',
			'fields'  => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'    => 'opt-switcher',
					'type'  => 'switcher',
					'title' => 'Switcher',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
			'default' => [
				[
					'opt-text'     => 'Some text 1',
					'opt-switcher' => true,
					'opt-textarea' => 'Some textarea content 1',
				],
				[
					'opt-text'     => 'Some text 2',
					'opt-switcher' => false,
					'opt-textarea' => 'Some textarea content 2',
				],
			],
		],

		[
			'id'       => 'opt-group-3',
			'type'     => 'group',
			'title'    => 'Group with limited (min - max items)',
			'subtitle' => 'The maximum/minimum number of items the user can add. (In this example min:1, max:3)',
			'min'      => 1,
			'max'      => 3,
			'fields'   => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
			'default'  => [
				[
					'opt-text'     => 'Limited text 1',
					'opt-textarea' => 'Limited textarea content 1',
				],
				[
					'opt-text'     => 'Limited text 2',
					'opt-textarea' => 'Limited textarea content 2',
				],
			],
		],

		[
			'id'       => 'opt-group-4',
			'type'     => 'group',
			'title'    => 'Group with WP Editor',
			'subtitle' => 'WP Editor integrated for Ajax Call.',
			'fields'   => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'    => 'opt-editor',
					'type'  => 'wp_editor',
					'title' => 'WP Editor',
				],
			],
			'default'  => [
				[
					'opt-text'   => 'WP Editor 1',
					'opt-editor' => 'Editor content 1',
				],
				[
					'opt-text'   => 'WP Editor 2',
					'opt-editor' => 'Editor content 2',
				],
			],
		],

		[
			'id'       => 'opt-group-5',
			'type'     => 'group',
			'title'    => 'Group nested',
			'subtitle' => 'Can be added unlimited nested groups',
			'fields'   => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'     => 'opt-group-5-sublevel-1',
					'type'   => 'group',
					'title'  => 'Group Nested',
					'fields' => [
						[
							'id'    => 'opt-text',
							'type'  => 'text',
							'title' => 'Text',
						],
						[
							'id'     => 'opt-group-5-sublevel-2',
							'type'   => 'group',
							'title'  => 'Group Nested',
							'fields' => [
								[
									'id'    => 'opt-text',
									'type'  => 'text',
									'title' => 'Text',
								],
								[
									'id'    => 'opt-switcher',
									'type'  => 'switcher',
									'title' => 'Switcher',
								],
								[
									'id'    => 'opt-textarea',
									'type'  => 'textarea',
									'title' => 'Textarea',
								],
							],
						],
						[
							'id'    => 'opt-switcher',
							'type'  => 'switcher',
							'title' => 'Switcher',
						],
						[
							'id'    => 'opt-textarea',
							'type'  => 'textarea',
							'title' => 'Textarea',
						],
					],
				],
				[
					'id'    => 'opt-switcher',
					'type'  => 'switcher',
					'title' => 'Switcher',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
			'default'  => [

				// top level defaults
				[
					'opt-text'               => 'Top Level 1',

					// sub level 1 defaults
					'opt-group-5-sublevel-1' => [
						[
							'opt-text'               => 'Sub Level 1',

							// sub level 2 defaults
							'opt-group-5-sublevel-2' => [
								[
									'opt-text' => 'Sub Sub Level 1',
								],
								[
									'opt-text' => 'Sub Sub Level 2',
								],
							],
						],
						[
							'opt-text' => 'Sub Level 2',
						],
					],
				],

				// top level defaults
				[
					'opt-text' => 'Top Level 2',
				],
			],
		],

		[
			'id'      => 'opt-group-6',
			'type'    => 'group',
			'title'   => 'Group with Repeater Field',
			'fields'  => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'     => 'opt-group-6-repeater',
					'type'   => 'repeater',
					'title'  => 'Repeater',
					'fields' => [
						[
							'id'    => 'opt-text',
							'type'  => 'text',
							'title' => 'Text',
						],
					],
				],
				[
					'id'    => 'opt-switcher',
					'type'  => 'switcher',
					'title' => 'Switcher',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
			'default' => [
				[
					'opt-text'             => 'Some text 1',
					'opt-group-6-repeater' => [
						[
							'opt-text' => 'Some text 1',
						],
						[
							'opt-text' => 'Some text 2',
						],
					],
				],
			],
		],

		[
			'id'                     => 'opt-group-7',
			'type'                   => 'group',
			'title'                  => 'Group with static prefix of title',
			'subtitle'               => 'accordion_title_prefix => "Static Prefix:"',
			'accordion_title_prefix' => 'Static Prefix:',
			'fields'                 => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'    => 'opt-switcher',
					'type'  => 'switcher',
					'title' => 'Switcher',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
			'default'                => [
				[
					'opt-text'     => 'Some text 1',
					'opt-switcher' => true,
					'opt-textarea' => 'Some textarea content 1',
				],
				[
					'opt-text'     => 'Some text 2',
					'opt-switcher' => false,
					'opt-textarea' => 'Some textarea content 2',
				],
			],
		],

		[
			'id'                     => 'opt-group-8',
			'type'                   => 'group',
			'title'                  => 'Group with title numbers',
			'subtitle'               => 'accordion_title_number => true',
			'accordion_title_number' => true,
			'fields'                 => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'    => 'opt-switcher',
					'type'  => 'switcher',
					'title' => 'Switcher',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
			'default'                => [
				[
					'opt-text'     => 'Some text 1',
					'opt-switcher' => true,
					'opt-textarea' => 'Some textarea content 1',
				],
				[
					'opt-text'     => 'Some text 2',
					'opt-switcher' => false,
					'opt-textarea' => 'Some textarea content 2',
				],
			],
		],

		[
			'id'                 => 'opt-group-9',
			'type'               => 'group',
			'title'              => 'Group with custom titles',
			'subtitle'           => 'accordion_title_by => array( \'opt-name\', \'opt-surname\' )',
			'accordion_title_by' => [ 'opt-name', 'opt-surname' ],
			'fields'             => [
				[
					'id'    => 'opt-name',
					'type'  => 'text',
					'title' => 'Name',
				],
				[
					'id'    => 'opt-surname',
					'type'  => 'text',
					'title' => 'Surname',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
			'default'            => [
				[
					'opt-name'     => 'John',
					'opt-surname'  => 'Doe',
					'opt-textarea' => 'Textarea 1',
				],
				[
					'opt-name'     => 'Jane',
					'opt-surname'  => 'Doe',
					'opt-textarea' => 'Textarea 1',
				],
			],
		],

		[
			'id'                        => 'opt-group-10',
			'type'                      => 'group',
			'title'                     => 'Group with custom titles and prefix',
			'subtitle'                  => 'accordion_title_by => array( \'opt-text\', \'opt-textarea\' )<br />accordion_title_by_prefix => \' | \'',
			'accordion_title_by'        => [ 'opt-text', 'opt-textarea' ],
			'accordion_title_by_prefix' => ' | ',
			'fields'                    => [
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Title',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
			'default'                   => [
				[
					'opt-text'     => 'Text 1',
					'opt-textarea' => 'Textarea 1',
				],
				[
					'opt-text'     => 'Text 2',
					'opt-textarea' => 'Textarea 1',
				],
			],
		],

	],
] );

//
// Field: accordion
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Accordion',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=accordion" target="_blank">Field: accordion</a>',
	'fields'      => [

		[
			'id'         => 'opt-accordion-1',
			'type'       => 'accordion',
			'title'      => 'Accordion',
			'accordions' => [

				[
					'title'  => 'Accordion 1',
					'fields' => [
						[
							'id'    => 'opt-text-1',
							'type'  => 'text',
							'title' => 'Text',
						],
						[
							'id'    => 'opt-switcher-1',
							'type'  => 'switcher',
							'title' => 'Switcher',
						],
						[
							'id'    => 'opt-textarea-1',
							'type'  => 'textarea',
							'title' => 'Textarea',
						],
					],
				],

				[
					'title'  => 'Accordion 2',
					'fields' => [
						[
							'id'    => 'opt-text-2',
							'type'  => 'text',
							'title' => 'Text',
						],
						[
							'id'    => 'opt-color-1',
							'type'  => 'color',
							'title' => 'Color',
						],
					],
				],

			],
		],

		[
			'id'         => 'opt-accordion-2',
			'type'       => 'accordion',
			'title'      => 'Accordion with default',
			'accordions' => [

				[
					'title'  => 'Fields 1',
					'fields' => [
						[
							'id'    => 'opt-text-1',
							'type'  => 'text',
							'title' => 'Text 1',
						],
						[
							'id'    => 'opt-text-2',
							'type'  => 'text',
							'title' => 'Text 2',
						],
					],
				],

				[
					'title'  => 'Fields 2',
					'fields' => [
						[
							'id'    => 'opt-color-1',
							'type'  => 'color',
							'title' => 'Color 1',
						],
						[
							'id'    => 'opt-color-2',
							'type'  => 'color',
							'title' => 'Color 2',
						],
					],
				],

				[
					'title'  => 'Fields 3',
					'fields' => [
						[
							'id'    => 'opt-textarea-1',
							'type'  => 'textarea',
							'title' => 'Textarea 3',
						],
						[
							'id'    => 'opt-textarea-2',
							'type'  => 'textarea',
							'title' => 'Textarea 4',
						],
					],
				],

			],
			'default'    => [
				'opt-text-1'     => 'This is text 1 default value',
				'opt-text-2'     => 'This is text 2 default value',
				'opt-color-1'    => '#1e73be',
				'opt-color-2'    => '#ffbc00',
				'opt-textarea-1' => 'This is textarea 1 default value',
				'opt-textarea-2' => 'This is textarea 2 default value',
			],
		],

		[
			'id'         => 'accordion_3',
			'type'       => 'accordion',
			'title'      => 'Accordion with custom icons',
			'accordions' => [

				[
					'title'  => 'Other 1',
					'icon'   => 'fas fa-check',
					'fields' => [
						[
							'id'    => 'opt-text-1',
							'type'  => 'text',
							'title' => 'Text 1',
						],
					],
				],

				[
					'title'  => 'Other 2',
					'icon'   => 'fas fa-star',
					'fields' => [
						[
							'id'    => 'opt-text-2',
							'type'  => 'text',
							'title' => 'Text 2',
						],
					],
				],

			],
		],

	],
] );

//
// Field: tabbed
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Tabbed',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=tabbed" target="_blank">Field: tabbed</a>',
	'fields'      => [

		[
			'id'    => 'opt-tabbed-1',
			'type'  => 'tabbed',
			'title' => 'Tabbed',
			'tabs'  => [

				[
					'title'  => 'Tab 1',
					'fields' => [
						[
							'id'    => 'opt-text-1',
							'type'  => 'text',
							'title' => 'Text 1',
						],
						[
							'id'    => 'opt-textarea-1',
							'type'  => 'textarea',
							'title' => 'Textarea 1',
						],
					],
				],

				[
					'title'  => 'Tab 2',
					'fields' => [
						[
							'id'    => 'opt-text-2',
							'type'  => 'text',
							'title' => 'Text 2',
						],
						[
							'id'    => 'opt-textarea-2',
							'type'  => 'textarea',
							'title' => 'Textarea 2',
						],
					],
				],

			],
		],

		[
			'id'      => 'opt-tabbed-2',
			'type'    => 'tabbed',
			'title'   => 'Tabbed with default and icons',
			'tabs'    => [
				[
					'title'  => 'Fields 1',
					'icon'   => 'fas fa-check',
					'fields' => [
						[
							'id'    => 'opt-text-1',
							'type'  => 'text',
							'title' => 'Text 1',
						],
						[
							'id'    => 'opt-text-2',
							'type'  => 'text',
							'title' => 'Text 2',
						],
					],
				],
				[
					'title'  => 'Fields 2',
					'icon'   => 'fas fa-star',
					'fields' => [
						[
							'id'    => 'opt-color-1',
							'type'  => 'color',
							'title' => 'Color 1',
						],
						[
							'id'    => 'opt-color-2',
							'type'  => 'color',
							'title' => 'Color 2',
						],
					],
				],
				[
					'title'  => 'Fields 3',
					'icon'   => 'fas fa-cog',
					'fields' => [
						[
							'id'    => 'opt-textarea-1',
							'type'  => 'textarea',
							'title' => 'Textarea 1',
						],
						[
							'id'    => 'opt-textarea-2',
							'type'  => 'textarea',
							'title' => 'Textarea 2',
						],
					],
				],
			],
			'default' => [
				'opt-text-1'     => 'This is text 1 default value',
				'opt-text-2'     => 'This is text 2 default value',
				'opt-color-1'    => '#1e73be',
				'opt-color-2'    => '#ffbc00',
				'opt-textarea-1' => 'This is textarea 1 default value',
				'opt-textarea-2' => 'This is textarea 2 default value',
			],
		],

	],
] );

//
// Field: fieldset
//
KIANFR::createSection( $prefix, [
	'parent' => 'fields',
	'title'  => 'Fieldset',
	'fields' => [

		[
			'id'     => 'opt-fieldset-1',
			'type'   => 'fieldset',
			'title'  => 'Fieldset',
			'fields' => [
				[
					'id'    => 'opt-color',
					'type'  => 'color',
					'title' => 'Color',
				],
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
		],

		[
			'id'      => 'opt-fieldset-2',
			'type'    => 'fieldset',
			'title'   => 'Fieldset with default',
			'fields'  => [
				[
					'type'    => 'subheading',
					'content' => 'Title of the fieldset',
				],
				[
					'id'    => 'opt-color',
					'type'  => 'color',
					'title' => 'Color',
				],
				[
					'id'    => 'opt-text',
					'type'  => 'text',
					'title' => 'Text',
				],
				[
					'id'    => 'opt-textarea',
					'type'  => 'textarea',
					'title' => 'Textarea',
				],
			],
			'default' => [
				'opt-color'    => '#1e73be',
				'opt-text'     => 'This is text default value',
				'opt-textarea' => 'This is textarea default value',
			],
		],

	],
] );

//
// Field: media
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Media',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=media" target="_blank">Field: media</a>',
	'fields'      => [

		[
			'id'    => 'opt-media-1',
			'type'  => 'media',
			'title' => 'Media',
		],

		[
			'id'      => 'opt-media-2',
			'type'    => 'media',
			'title'   => 'Media without preview',
			'preview' => false,
		],

		[
			'id'    => 'opt-media-3',
			'type'  => 'media',
			'title' => 'Media without url',
			'url'   => false,
		],

		[
			'id'      => 'opt-media-4',
			'type'    => 'media',
			'title'   => 'Media with only image type',
			'library' => 'image',
		],

		[
			'id'      => 'opt-media-5',
			'type'    => 'media',
			'title'   => 'Media with only video type',
			'library' => 'video',
		],

		[
			'id'      => 'opt-media-6',
			'type'    => 'media',
			'title'   => 'Media with only audio type',
			'library' => 'audio',
		],

	],
] );

//
// Field: upload
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Upload',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=upload" target="_blank">Field: upload</a>',
	'fields'      => [

		[
			'id'    => 'opt-upload-1',
			'type'  => 'upload',
			'title' => 'Upload',
		],

		[
			'id'      => 'opt-upload-2',
			'type'    => 'upload',
			'title'   => 'Upload with preview',
			'preview' => true,
		],

		[
			'id'          => 'opt-upload-3',
			'type'        => 'upload',
			'title'       => 'Upload with placeholder',
			'placeholder' => 'http://',
		],

		[
			'id'           => 'opt-upload-4',
			'type'         => 'upload',
			'title'        => 'Upload with only image type',
			'library'      => 'image',
			'button_title' => 'Upload Image',
		],

		[
			'id'           => 'opt-upload-5',
			'type'         => 'upload',
			'title'        => 'Upload with only video type',
			'library'      => 'video',
			'button_title' => 'Upload Video',
		],

		[
			'id'           => 'opt-upload-6',
			'type'         => 'upload',
			'title'        => 'Upload with only audio type',
			'library'      => 'audio',
			'button_title' => 'Upload Audio',
		],
	],
] );

//
// Field: gallery
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Gallery',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=gallery" target="_blank">Field: gallery</a>',
	'fields'      => [

		[
			'id'    => 'opt-gallery-1',
			'type'  => 'gallery',
			'title' => 'Gallery',
		],

		[
			'id'          => 'opt-gallery-2',
			'type'        => 'gallery',
			'title'       => 'Gallery with custom button names',
			'add_title'   => 'Add Image(s)',
			'edit_title'  => 'Edit Images',
			'clear_title' => 'Remove Images',
		],

	],
] );

//
// Field: code_editor
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Code Editor',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=code-editor" target="_blank">Field: code_editor</a>',
	'fields'      => [

		[
			'id'       => 'opt-code-editor-1',
			'type'     => 'code_editor',
			'title'    => 'Code Editor',
			'subtitle' => '<strong>Default Editor</strong> Using: theme: default and mode: htmlmixed',
		],

		[
			'id'       => 'code_editor_2',
			'type'     => 'code_editor',
			'title'    => 'Code Editor',
			'subtitle' => '<strong>HTML Editor</strong> Using: theme: shadowfox and mode: htmlmixed',
			'settings' => [
				'theme' => 'shadowfox',
				'mode'  => 'htmlmixed',
			],
			'default'  => '<div class="wrapper">
  <h1>Hello world</h1>
  <p>Lorem <strong>ipsum</strong> dollar.</p>
</div>',
		],

		[
			'id'       => 'opt-code-editor-2',
			'type'     => 'code_editor',
			'title'    => 'Code Editor',
			'subtitle' => '<strong>JS Editor</strong> Using: theme: dracula and mode: javascript',
			'settings' => [
				'theme' => 'dracula',
				'mode'  => 'javascript',
			],
			'default'  => ';(function( $, window, document, undefined ) {
  "use strict";

  $(document).ready( function() {

    // do stuff

  });

})( jQuery, window, document );',
		],

		[
			'id'       => 'opt-code-editor-3',
			'type'     => 'code_editor',
			'desc'     => '<strong>CSS Editor</strong> It shows full width if there is no field of title and using: theme: mbo and mode: css',
			'settings' => [
				'theme' => 'mbo',
				'mode'  => 'css',
			],
			'default'  => '.wrapper {
  font-family: "Open Sans";
  font-size: 13px;
  width: 250px;
  height: 100px;
  color: #fff;
  background-color: #555;
}',
		],

	],
] );

//
// Field: wp_editor
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'WP Editor',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=wp-editor" target="_blank">Field: wp_editor</a>',
	'fields'      => [

		[
			'id'    => 'opt-wp-editor-1',
			'type'  => 'wp_editor',
			'title' => 'WP Editor',
		],

		[
			'id'            => 'opt-wp-editor-2',
			'type'          => 'wp_editor',
			'title'         => 'WP Editor with Custom Height and No Media Buttons',
			'subtitle'      => 'Settings: height => 100px, media_buttons => false',
			'height'        => '100px',
			'media_buttons' => false,
		],

		[
			'id'            => 'opt-wp-editor-3',
			'type'          => 'wp_editor',
			'title'         => 'WP Editor without QuickTags and Media Buttons',
			'subtitle'      => 'Settings: height => 100px, media_buttons => false, quicktags => false',
			'height'        => '100px',
			'media_buttons' => false,
			'quicktags'     => false,
		],

		[
			'id'            => 'opt-wp-editor-4',
			'type'          => 'wp_editor',
			'title'         => 'WP Editor without Tinymce and Media Buttons',
			'subtitle'      => 'Settings: height => 100px, media_buttons => false, tinymce => false',
			'height'        => '100px',
			'media_buttons' => false,
			'tinymce'       => false,
		],

	],
] );

//
// Field: color
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Color',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=color" target="_blank">Field: color</a>',
	'fields'      => [

		[
			'id'    => 'opt-color-1',
			'type'  => 'color',
			'title' => 'Color',
		],

		[
			'id'      => 'opt-color-2',
			'type'    => 'color',
			'title'   => 'Color with default (hex)',
			'default' => '#3498db',
		],

		[
			'id'      => 'opt-color-3',
			'type'    => 'color',
			'title'   => 'Color with default (rgba)',
			'default' => 'rgba(255,255,0,0.25)',
		],

		[
			'id'      => 'opt-color-4',
			'type'    => 'color',
			'title'   => 'Color with default (transparent)',
			'default' => 'transparent',
		],

	],
] );

//
// Field: link_color
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Link Color',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=link-color" target="_blank">Field: link_color</a>',
	'fields'      => [

		[
			'id'    => 'opt-link-color-1',
			'type'  => 'link_color',
			'title' => 'Link Color',
		],

		[
			'id'      => 'opt-link-color-2',
			'type'    => 'link_color',
			'title'   => 'Link Color with default',
			'default' => [
				'color' => '#1e73be',
				'hover' => '#259ded',
			],
		],

		[
			'id'      => 'opt-link-color-3',
			'type'    => 'link_color',
			'title'   => 'Link Color with more color options',
			'color'   => true,
			'hover'   => true,
			'visited' => true,
			'active'  => true,
			'focus'   => true,
		],

	],
] );

//
// Field: color_group
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Color Group',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=color-group" target="_blank">Field: color_group</a>',
	'fields'      => [

		[
			'id'      => 'opt-color-group-1',
			'type'    => 'color_group',
			'title'   => 'Color Group',
			'options' => [
				'color-1' => 'Color 1',
				'color-2' => 'Color 2',
			],
		],

		[
			'id'      => 'opt-color-group-2',
			'type'    => 'color_group',
			'title'   => 'Color Group',
			'options' => [
				'color-1' => 'Color 1',
				'color-2' => 'Color 2',
				'color-3' => 'Color 3',
			],
		],

		[
			'id'       => 'opt-color-group-3',
			'type'     => 'color_group',
			'title'    => 'Color Group with default',
			'subtitle' => 'Can be add unlimited color options.',
			'options'  => [
				'color-1' => 'Color 1',
				'color-2' => 'Color 2',
				'color-3' => 'Color 3',
				'color-4' => 'Color 4',
				'color-5' => 'Color 5',
			],
			'default'  => [
				'color-1' => '#000100',
				'color-2' => '#002642',
				'color-3' => '#ffce4b',
				'color-4' => '#ff595e',
				'color-5' => '#0052cc',
			],
		],

	],
] );

//
// Field: palette
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Color Palette',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=palette" target="_blank">Field: palette</a>',
	'fields'      => [

		[
			'id'       => 'opt-palette-1',
			'type'     => 'palette',
			'title'    => 'Palette',
			'subtitle' => 'Three set colors',
			'options'  => [
				'set-1' => [ '#f36e27', '#f3d430', '#ed1683' ],
				'set-2' => [ '#4153ab', '#6e86c7', '#211f27' ],
				'set-3' => [ '#162526', '#508486', '#C8C6CE' ],
				'set-4' => [ '#ccab5e', '#fff55f', '#197c5d' ],
			],
			'default'  => 'set-1',
		],

		[
			'id'       => 'opt-palette-1',
			'type'     => 'palette',
			'title'    => 'Palette',
			'subtitle' => 'Four set colors',
			'options'  => [
				'set-1' => [ '#f04e36', '#f36e27', '#f3d430', '#ed1683' ],
				'set-2' => [ '#f9ca06', '#b5b546', '#2f4d48', '#212b2f' ],
				'set-3' => [ '#4153ab', '#6e86c7', '#211f27', '#d69762' ],
				'set-4' => [ '#162526', '#508486', '#C8C6CE', '#B45F1A' ],
				'set-5' => [ '#bbd5ff', '#ccab5e', '#fff55f', '#197c5d' ],
			],
			'default'  => 'set-3',
		],

		[
			'id'       => 'opt-palette-2',
			'type'     => 'palette',
			'title'    => 'Palette',
			'subtitle' => 'Five set colors',
			'options'  => [
				'set-1' => [ '#bbd5ff', '#ccab5e', '#fff55f', '#197c5d', '#bce2c4' ],
				'set-2' => [ '#6d3264', '#edf7f6', '#fde8e9', '#006675', '#e49ab0' ],
				'set-3' => [ '#000100', '#002642', '#ffce4b', '#ff595e', '#0052cc' ],
			],
			'default'  => 'set-1',
		],

	],
] );

//
// Field: background
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Background',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=background" target="_blank">Field: background</a>',
	'fields'      => [

		[
			'id'    => 'opt-background-1',
			'type'  => 'background',
			'title' => 'Background',
		],

		[
			'id'      => 'opt-background-2',
			'type'    => 'background',
			'title'   => 'Background with default',
			'default' => [
				'background-color'      => '#e80000',
				'background-position'   => 'center center',
				'background-repeat'     => 'repeat-x',
				'background-attachment' => 'fixed',
				'background-size'       => 'cover',
			],
		],

		[
			'id'                    => 'opt-background-3',
			'type'                  => 'background',
			'title'                 => 'Background with all features',
			'background_color'      => true,
			'background_image'      => true,
			'background-position'   => true,
			'background_repeat'     => true,
			'background_attachment' => true,
			'background_size'       => true,
			'background_origin'     => true,
			'background_clip'       => true,
			'background_blend_mode' => true,
			'background_gradient'   => true,
			'default'               => [
				'background-color'              => '#009e44',
				'background-gradient-color'     => '#81d742',
				'background-gradient-direction' => '135deg',
				'background-position'           => 'center center',
				'background-repeat'             => 'repeat-x',
				'background-attachment'         => 'fixed',
				'background-size'               => 'cover',
				'background-origin'             => 'border-box',
				'background-clip'               => 'padding-box',
				'background-blend-mode'         => 'normal',
			],
		],

	],
] );

//
// Field: typography
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Typography',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=typography" target="_blank">Field: typography</a>',
	'fields'      => [

		[
			'id'    => 'opt-typography-1',
			'type'  => 'typography',
			'title' => 'Typography',
		],

		[
			'id'      => 'opt-typography-2',
			'type'    => 'typography',
			'title'   => 'Typography with default',
			'default' => [
				'font-family'    => 'Barlow',
				'font-weight'    => '600',
				'subset'         => 'latin-ext',
				'type'           => 'google',
				'text-align'     => 'center',
				'text-transform' => 'capitalize',
				'text-transform' => 'capitalize',
				'font-size'      => '18',
				'line-height'    => '20',
				'letter-spacing' => '-1',
				'color'          => '#009e44',
			],
		],

		[
			'id'             => 'opt-typography-3',
			'type'           => 'typography',
			'title'          => 'Typography with few features',
			'text_align'     => false,
			'text_transform' => false,
			'font_size'      => false,
			'line_height'    => false,
			'letter_spacing' => false,
			'color'          => false,
			'default'        => [
				'font-family' => 'Lato',
				'font-weight' => '900',
				'subset'      => 'latin',
				'type'        => 'google',
			],
		],

		[
			'id'                 => 'opt-typography-4',
			'type'               => 'typography',
			'title'              => 'Typography with all features',
			'font_family'        => true,
			'font_weight'        => true,
			'font_style'         => true,
			'font_size'          => true,
			'line_height'        => true,
			'letter_spacing'     => true,
			'text_align'         => true,
			'text-transform'     => true,
			'color'              => true,
			'subset'             => true,
			'backup_font_family' => true,
			'font_variant'       => true,
			'word_spacing'       => true,
			'text_decoration'    => true,
			'default'            => [
				'font-family' => 'Old Standard TT',
				'type'        => 'google',
			],
		],

	],
] );

//
// Field: dimensions
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Dimensions',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=dimensions" target="_blank">Field: dimensions</a>',
	'fields'      => [

		[
			'id'    => 'opt-dimensions-1',
			'type'  => 'dimensions',
			'title' => 'Dimensions',
		],

		[
			'id'      => 'opt-dimensions-2',
			'type'    => 'dimensions',
			'title'   => 'Dimensions with default',
			'default' => [
				'width'  => '100',
				'height' => '250',
				'unit'   => 'px',
			],
		],

		[
			'id'          => 'opt-dimensions-3',
			'type'        => 'dimensions',
			'title'       => 'Dimensions with custom text and units',
			'width_icon'  => 'width',
			'height_icon' => 'height',
			'units'       => [ 'px', '%', 'em', 'rem', 'pt' ],
			'default'     => [
				'width'  => '100',
				'height' => '50',
				'unit'   => '%',
			],
		],

		[
			'id'    => 'opt-dimensions-4',
			'type'  => 'dimensions',
			'title' => 'Dimensions with single unit',
			'units' => [ 'px' ],
		],

		[
			'id'    => 'opt-dimensions-5',
			'type'  => 'dimensions',
			'title' => 'Dimensions without unit selector',
			'unit'  => false,
		],

		[
			'id'     => 'opt-dimensions-6',
			'type'   => 'dimensions',
			'title'  => 'Dimensions with only width',
			'height' => false,
		],

		[
			'id'     => 'opt-dimensions-7',
			'type'   => 'dimensions',
			'title'  => 'Dimensions with only width and single unit',
			'height' => false,
			'units'  => [ 'px' ],
		],

	],
] );

//
// Field: spacing
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Spacing',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=spacing" target="_blank">Field: spacing</a>',
	'fields'      => [

		[
			'id'    => 'opt-spacing-1',
			'type'  => 'spacing',
			'title' => 'Spacing',
		],

		[
			'id'      => 'opt-spacing-2',
			'type'    => 'spacing',
			'title'   => 'Spacing with default',
			'default' => [
				'top'    => '50',
				'right'  => '100',
				'bottom' => '50',
				'left'   => '100',
				'unit'   => 'px',
			],
		],

		[
			'id'      => 'opt-spacing-2',
			'type'    => 'spacing',
			'title'   => 'Spacing without unit selector',
			'units'   => [ 'px' ],
			'default' => [
				'top'    => '50',
				'right'  => '100',
				'bottom' => '50',
				'left'   => '100',
				'unit'   => 'px',
			],
		],

		[
			'id'     => 'opt-spacing-3',
			'type'   => 'spacing',
			'title'  => 'Spacing with only left and right',
			'top'    => false,
			'bottom' => false,
		],

		[
			'id'    => 'opt-spacing-4',
			'type'  => 'spacing',
			'title' => 'Spacing with only top and bottom',
			'left'  => false,
			'right' => false,
		],

		[
			'id'    => 'opt-spacing-5',
			'type'  => 'spacing',
			'title' => 'Spacing with all directions',
			'all'   => true,
		],

	],
] );

//
// Field: border
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Border',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=border" target="_blank">Field: border</a>',
	'fields'      => [

		[
			'id'    => 'opt-border-1',
			'type'  => 'border',
			'title' => 'Border',
		],

		[
			'id'      => 'opt-border-2',
			'type'    => 'border',
			'title'   => 'Border with default',
			'default' => [
				'top'    => '4',
				'right'  => '8',
				'bottom' => '4',
				'left'   => '8',
				'style'  => 'dashed',
				'color'  => '#1e73be',
			],
		],

		[
			'id'     => 'opt-border-3',
			'type'   => 'border',
			'title'  => 'Border with only left and right',
			'top'    => false,
			'bottom' => false,
		],

		[
			'id'    => 'opt-border-4',
			'type'  => 'border',
			'title' => 'Border with only top and bottom',
			'left'  => false,
			'right' => false,
		],

		[
			'id'    => 'opt-border-5',
			'type'  => 'border',
			'title' => 'Border with all directions',
			'all'   => true,
		],

	],
] );

//
// Field: spinner
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Spinner',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=spinner" target="_blank">Field: spinner</a>',
	'fields'      => [

		[
			'id'       => 'opt-spinner-1',
			'type'     => 'spinner',
			'title'    => 'Spinner',
			'subtitle' => 'max:100 | min:0 | step:1',
			'max'      => 100,
			'min'      => 0,
			'step'     => 1,
			'default'  => 25,
		],

		[
			'id'       => 'opt-spinner-2',
			'type'     => 'spinner',
			'title'    => 'Spinner',
			'subtitle' => 'max:200 | min:100 | step:10',
			'max'      => 200,
			'min'      => 100,
			'step'     => 10,
			'default'  => 100,
		],

		[
			'id'       => 'opt-spinner-3',
			'type'     => 'spinner',
			'title'    => 'Spinner',
			'subtitle' => 'max:1 | min:0 | step:0.1 | unit:px',
			'max'      => 1,
			'min'      => 0,
			'step'     => 0.1,
			'unit'     => 'px',
			'default'  => 0.5,
		],

	],
] );

//
// Field: number
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Number',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=number" target="_blank">Field: number</a>',
	'fields'      => [

		[
			'id'    => 'opt-number-1',
			'type'  => 'number',
			'title' => 'Number',
		],
		[
			'id'    => 'opt-number-2',
			'type'  => 'number',
			'title' => 'Number with unit',
			'unit'  => 'px',
		],
		[
			'id'      => 'opt-number-3',
			'type'    => 'number',
			'title'   => 'Number with default',
			'unit'    => 'width',
			'default' => 100,
		],

	],
] );

//
// Field: slider
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Slider',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=slider" target="_blank">Field: slider</a>',
	'fields'      => [

		[
			'id'    => 'opt-slider-1',
			'type'  => 'slider',
			'title' => 'Slider',
		],

		[
			'id'      => 'opt-slider-2',
			'type'    => 'slider',
			'title'   => 'Slider with default',
			'default' => 50,
		],

		[
			'id'      => 'opt-slider-3',
			'type'    => 'slider',
			'title'   => 'Slider with unit text',
			'unit'    => '%',
			'default' => 75,
		],

		[
			'id'       => 'opt-slider-4',
			'type'     => 'slider',
			'title'    => 'Slider with min/max allowed value',
			'subtitle' => 'Min: 1 | Max: 10 | Step: 0.1 | Default: 5.5',
			'unit'     => 'px',
			'min'      => 1,
			'max'      => 10,
			'step'     => 0.1,
			'default'  => 5.5,
		],

	],
] );

//
// Field: sorter
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Sorter',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=sorter" target="_blank">Field: sorter</a>',
	'fields'      => [

		[
			'id'      => 'opt-sorter-1',
			'type'    => 'sorter',
			'title'   => 'Sorter',
			'default' => [
				'enabled'  => [
					'opt-1' => 'Option 1',
					'opt-2' => 'Option 2',
					'opt-3' => 'Option 3',
				],
				'disabled' => [
					'opt-4' => 'Option 4',
					'opt-5' => 'Option 5',
				],
			],
		],

		[
			'id'             => 'opt-sorter-2',
			'type'           => 'sorter',
			'title'          => 'Sorter with custom title',
			'enabled_title'  => 'Activated',
			'disabled_title' => 'Deactivated',
			'default'        => [
				'enabled'  => [
					'opt-1' => 'Option 1',
					'opt-2' => 'Option 2',
					'opt-3' => 'Option 3',
				],
				'disabled' => [
					'opt-4' => 'Option 4',
					'opt-5' => 'Option 5',
				],
			],
		],

		[
			'id'            => 'opt-sorter-3',
			'type'          => 'sorter',
			'title'         => 'Sorter with use only enabled section and without title',
			'enabled_title' => false,
			'disabled'      => false,
			'default'       => [
				'enabled' => [
					'opt-1' => 'Option 1',
					'opt-2' => 'Option 2',
					'opt-3' => 'Option 3',
				],
			],
		],

	],
] );

//
// Field: sortable
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Sortable',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=sortable" target="_blank">Field: sortable</a>',
	'fields'      => [

		[
			'id'     => 'opt-sortable-1',
			'type'   => 'sortable',
			'title'  => 'Sortable',
			'fields' => [
				[
					'id'    => 'opt-text-1',
					'type'  => 'text',
					'title' => 'Text 1',
				],
				[
					'id'    => 'opt-text-2',
					'type'  => 'text',
					'title' => 'Text 2',
				],
				[
					'id'    => 'opt-text-3',
					'type'  => 'text',
					'title' => 'Text 3',
				],
			],
		],

		[
			'id'      => 'opt-sortable-2',
			'type'    => 'sortable',
			'title'   => 'Sortable with default',
			'fields'  => [
				[
					'id'    => 'opt-text-1',
					'type'  => 'text',
					'title' => 'Text 1',
				],
				[
					'id'    => 'opt-text-2',
					'type'  => 'text',
					'title' => 'Text 2',
				],
				[
					'id'    => 'opt-text-3',
					'type'  => 'text',
					'title' => 'Text 3',
				],
			],
			'default' => [
				'opt-text-1' => 'This is text 1 default',
				'opt-text-2' => 'This is text 2 default',
				'opt-text-3' => 'This is text 3 default',
			],
		],

	],
] );

//
// Field: switcher
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Switcher',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=switcher" target="_blank">Field: switcher</a>',
	'fields'      => [

		[
			'id'    => 'opt-switcher-1',
			'type'  => 'switcher',
			'title' => 'Switcher',
		],

		[
			'id'      => 'opt-switcher-2',
			'type'    => 'switcher',
			'title'   => 'Switcher with default',
			'default' => true,
		],

		[
			'id'    => 'opt-switcher-3',
			'type'  => 'switcher',
			'title' => 'Switcher with label',
			'label' => 'The label text of the switcher.',
		],

		[
			'id'       => 'opt-switcher-4',
			'type'     => 'switcher',
			'title'    => 'Switcher with Yes/No',
			'text_on'  => 'Yes',
			'text_off' => 'No',
		],

		[
			'id'         => 'opt-switcher-4',
			'type'       => 'switcher',
			'title'      => 'Switcher with custom text Enabled/Disabled',
			'text_on'    => 'Enabled',
			'text_off'   => 'Disabled',
			'text_width' => '100',
		],

	],
] );

//
// Field: icons
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Icons',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=icon" target="_blank">Field: icon</a>',
	'fields'      => [

		[
			'id'    => 'opt-icon-1',
			'type'  => 'icon',
			'title' => 'Icon',
		],

		[
			'id'      => 'opt-icon-2',
			'type'    => 'icon',
			'title'   => 'Icon with default',
			'default' => 'fas fa-check',
		],

	],
] );

//
// Field: map
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Map',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=map" target="_blank">Field: map</a>',
	'fields'      => [

		[
			'id'    => 'opt-map-1',
			'type'  => 'map',
			'title' => 'Map',
		],

		[
			'id'      => 'opt-map-2',
			'type'    => 'map',
			'title'   => 'Map with Default',
			'default' => [
				'address'   => 'New York, United States of America',
				'latitude'  => '40.7127281',
				'longitude' => '-74.0060152',
				'zoom'      => '12',
			],
		],

		[
			'type'    => 'submessage',
			'style'   => 'info',
			'content' => 'Using custom <strong>address_field</strong> field in below example.',
		],

		[
			'id'    => 'my-address-text',
			'type'  => 'text',
			'title' => 'Address',
		],

		[
			'id'            => 'opt-map-3',
			'type'          => 'map',
			'title'         => 'Map',
			'desc'          => 'Using custom <strong>address_field</strong> field',
			'address_field' => 'my-address-text',
		],

	],
] );

//
// Field: link
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Link',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=link" target="_blank">Field: link</a>',
	'fields'      => [

		[
			'id'    => 'opt-link-1',
			'type'  => 'link',
			'title' => 'Link',
		],

		[
			'id'      => 'opt-link-2',
			'type'    => 'link',
			'title'   => 'Link with default',
			'default' => [
				'url'    => 'http://codestarframework.com/',
				'text'   => 'Codestar Framework',
				'target' => '_blank',
			],
		],

	],
] );

//
// Field: date
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Date',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=date" target="_blank">Field: date</a>',
	'fields'      => [

		[
			'id'    => 'opt-date-1',
			'type'  => 'date',
			'title' => 'Date',
		],

		[
			'id'       => 'opt-date-2',
			'type'     => 'date',
			'title'    => 'Date with custom settings',
			'settings' => [
				'dateFormat'      => 'mm/dd/yy',
				'changeMonth'     => true,
				'changeYear'      => true,
				'showWeek'        => true,
				'showButtonPanel' => true,
				'weekHeader'      => 'Week',
				'monthNamesShort' => [
					'January',
					'February',
					'March',
					'April',
					'May',
					'June',
					'July',
					'August',
					'September',
					'October',
					'November',
					'December',
				],
				'dayNamesMin'     => [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ],
			],
		],

		[
			'id'      => 'opt-date-3',
			'type'    => 'date',
			'title'   => 'Date with From &amp; To',
			'from_to' => true,
		],

		[
			'id'        => 'opt-date-4',
			'type'      => 'date',
			'title'     => 'Date with custom texts Begin &amp; End',
			'from_to'   => true,
			'text_from' => 'Begin',
			'text_to'   => 'End',
		],

	],
] );

//
// Field: datetime
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Date and Time',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=date-time" target="_blank">Field: datetime</a>',
	'fields'      => [

		[
			'id'    => 'opt-datetime-1',
			'type'  => 'datetime',
			'title' => 'Date',
		],

		[
			'id'       => 'opt-datetime-2',
			'type'     => 'datetime',
			'title'    => 'Date',
			'subtitle' => 'Human-friendly Dates',
			'settings' => [
				'altFormat'  => 'F j, Y',
				'dateFormat' => 'Y-m-d',
			],
		],

		[
			'id'       => 'opt-datetime-3',
			'type'     => 'datetime',
			'title'    => 'Date',
			'subtitle' => 'Start week on Monday',
			'settings' => [
				'locale' => [
					'firstDayOfWeek' => 1,
				],
			],
		],

		[
			'id'       => 'opt-datetime-4',
			'type'     => 'datetime',
			'title'    => 'Date and Time',
			'subtitle' => 'Date and Time Both',
			'settings' => [
				'enableTime' => true,
			],
		],

		[
			'id'       => 'opt-datetime-5',
			'type'     => 'datetime',
			'title'    => 'Time',
			'subtitle' => 'Only Time',
			'settings' => [
				'noCalendar' => true,
				'enableTime' => true,
			],
		],

		[
			'id'       => 'opt-datetime-6',
			'type'     => 'datetime',
			'title'    => 'Time',
			'subtitle' => '24-hour Time without PM:AM',
			'settings' => [
				'noCalendar' => true,
				'enableTime' => true,
				'dateFormat' => 'H:i',
				'time_24hr'  => true,
			],
		],

		[
			'id'       => 'opt-datetime-7',
			'type'     => 'datetime',
			'title'    => 'Date Range',
			'subtitle' => 'Range Mode',
			'settings' => [
				'mode' => 'range',
			],
		],

		[
			'id'       => 'opt-datetime-8',
			'type'     => 'datetime',
			'title'    => 'Date From - To',
			'subtitle' => 'Date with "From" and "To"',
			'from_to'  => true,
		],

		[
			'id'        => 'opt-datetime-9',
			'type'      => 'datetime',
			'title'     => 'Date Begin - End',
			'subtitle'  => 'Date with "Begin" and "End" Custom Text',
			'from_to'   => true,
			'text_from' => 'Begin',
			'text_to'   => 'End',
		],

		[
			'id'       => 'opt-datetime-10',
			'type'     => 'datetime',
			'title'    => 'Date',
			'subtitle' => 'Localize Example (Spanish)',
			'settings' => [
				'locale' => [
					'time_24hr'      => true,
					'firstDayOfWeek' => 1,
					'weekdays'       => [
						'shorthand' => [ 'Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb' ],
						'longhand'  => [
							'Domingo',
							'Lunes',
							'Martes',
							'Miércoles',
							'Jueves',
							'Viernes',
							'Sábado',
						],
					],
					'months'         => [
						'shortland' => [
							'Ene',
							'Feb',
							'Mar',
							'Abr',
							'May',
							'Jun',
							'Jul',
							'Ago',
							'Sep',
							'Oct',
							'Nov',
							'Dic',
						],
						'longhand'  => [
							'Enero',
							'Febrero',
							'Marzo',
							'Abril',
							'Mayo',
							'Junio',
							'Julio',
							'Agosto',
							'Septiembre',
							'Octubre',
							'Noviembre',
							'Diciembre',
						],
					],
				],
			],
		],

		[
			'id'       => 'opt-datetime-11',
			'type'     => 'datetime',
			'title'    => 'Date',
			'subtitle' => 'Ready-Only Input',
			'settings' => [
				'allowInput' => false,
			],
		],

	],
] );

//
// Field: image_select
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Image Select',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=image-select" target="_blank">Field: image_select</a>',
	'fields'      => [

		[
			'id'      => 'opt-image-select-1',
			'type'    => 'image_select',
			'title'   => 'Image Select',
			'options' => [
				'opt-1' => 'http://codestarframework.com/assets/images/placeholder/80x80-e74c3c.gif',
				'opt-2' => 'http://codestarframework.com/assets/images/placeholder/80x80-ffbc00.gif',
				'opt-3' => 'http://codestarframework.com/assets/images/placeholder/80x80-3498db.gif',
			],
		],

		[
			'id'      => 'opt-image-select-2',
			'type'    => 'image_select',
			'title'   => 'Image Select with default',
			'options' => [
				'opt-1' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
				'opt-2' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
				'opt-3' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
			],
			'default' => 'opt-2',
		],

		[
			'id'       => 'opt-image-select-3',
			'type'     => 'image_select',
			'title'    => 'Image Select with multiple choice',
			'multiple' => true,
			'options'  => [
				'opt-1' => 'http://codestarframework.com/assets/images/placeholder/80x80-e74c3c.gif',
				'opt-2' => 'http://codestarframework.com/assets/images/placeholder/80x80-ffbc00.gif',
				'opt-3' => 'http://codestarframework.com/assets/images/placeholder/80x80-3498db.gif',
			],
		],

		[
			'id'       => 'opt-image-select-4',
			'type'     => 'image_select',
			'title'    => 'Image Select with multiple choice and default',
			'multiple' => true,
			'options'  => [
				'opt-1' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
				'opt-2' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
				'opt-3' => 'http://codestarframework.com/assets/images/placeholder/80x80-e74c3c.gif',
				'opt-4' => 'http://codestarframework.com/assets/images/placeholder/80x80-ffbc00.gif',
				'opt-5' => 'http://codestarframework.com/assets/images/placeholder/80x80-3498db.gif',
				'opt-6' => 'http://codestarframework.com/assets/images/placeholder/80x80-2ecc71.gif',
				'opt-7' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
				'opt-8' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
				'opt-9' => 'http://codestarframework.com/assets/images/placeholder/80x80-2c3e50.gif',
			],
			'default'  => [ 'opt-3', 'opt-4', 'opt-5', 'opt-6' ],
		],

		[
			'id'      => 'opt-image-select-5',
			'type'    => 'image_select',
			'title'   => 'Image Select inline style',
			'inline'  => true,
			'options' => [
				'opt-1' => 'http://codestarframework.com/assets/images/placeholder/80x80-e74c3c.gif',
				'opt-2' => 'http://codestarframework.com/assets/images/placeholder/80x80-ffbc00.gif',
				'opt-3' => 'http://codestarframework.com/assets/images/placeholder/80x80-3498db.gif',
				'opt-4' => 'http://codestarframework.com/assets/images/placeholder/80x80-2ecc71.gif',
			],
			'default' => 'opt-1',
		],

	],
] );

//
// Field: button_set
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Button Set',
	'description' => 'Visit documentation for more details on this field: <a href="http://codestarframework.com/documentation/#/fields?id=button-set" target="_blank">Field: button_set</a>',
	'fields'      => [

		[
			'id'      => 'opt-button-set-1',
			'type'    => 'button_set',
			'title'   => 'Button Set',
			'options' => [
				'enabled'  => 'Enabled',
				'disabled' => 'Disabled',
			],
		],

		[
			'id'      => 'opt-button-set-2',
			'type'    => 'button_set',
			'title'   => 'Button Set with default',
			'options' => [
				'enabled'  => 'Enabled',
				''         => 'Default',
				'disabled' => 'Disabled',
			],
		],

		[
			'id'      => 'opt-button-set-3',
			'type'    => 'button_set',
			'title'   => 'Button Set',
			'options' => [
				'activate'   => 'Activate',
				'deactivate' => 'Deactivate',
			],
			'default' => 'activate',
		],

		[
			'id'      => 'opt-button-set-4',
			'type'    => 'button_set',
			'title'   => 'Button Set',
			'options' => [
				'on'  => 'ON',
				'off' => 'OFF',
			],
			'default' => 'on',
		],

		[
			'id'       => 'opt-button-set-5',
			'type'     => 'button_set',
			'title'    => 'Button Set with multiple choice',
			'multiple' => true,
			'options'  => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
				'opt-4' => 'Option 4',
				'opt-5' => 'Option 5',
			],
		],

		[
			'id'       => 'opt-button-set-6',
			'type'     => 'button_set',
			'title'    => 'Button Set with multiple choice and default',
			'multiple' => true,
			'options'  => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
				'opt-4' => 'Option 4',
				'opt-5' => 'Option 5',
			],
			'default'  => [ 'opt-2', 'opt-4' ],
		],

	],
] );

//
// Dependencies
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Dependencies',
	'description' => 'Visit documentation for more details: <a href="http://codestarframework.com/documentation/#/faq?id=how-to-use-dependency" target="_blank">How to use dependencies</a>',
	'fields'      => [

		[
			'type'    => 'subheading',
			'content' => 'Basic Dependencies',
		],

		//
		// Dependency example 1
		[
			'id'    => 'opt-depend-switcher',
			'type'  => 'switcher',
			'title' => 'If switched to (ON)',
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Success: Switched to (ON).',
			'dependency' => [ 'opt-depend-switcher', '==', 'true' ],
		],

		//
		// Dependency example 2
		[
			'id'    => 'opt-depend-text',
			'type'  => 'text',
			'title' => 'If typed something to field',
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Success: You typed something.',
			'dependency' => [ 'opt-depend-text', '!=', '' ],
		],

		//
		// Dependency example 3
		[
			'id'          => 'opt-depend-select',
			'type'        => 'select',
			'title'       => 'If selected to (Blue) or (Black)',
			'placeholder' => 'Select a color',
			'options'     => [
				'blue'   => 'Blue',
				'yellow' => 'Yellow',
				'green'  => 'Green',
				'black'  => 'Black',
				'white'  => 'White',
			],
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Success: Selected to (Blue) or (Black).',
			'dependency' => [ 'opt-depend-select', 'any', 'blue,black' ],
		],

		//
		// Dependency example 4
		[
			'id'      => 'opt-depend-radio',
			'type'    => 'radio',
			'title'   => 'If selected to (Yes, Please)',
			'inline'  => true,
			'options' => [
				'no'  => 'No, Thanks',
				'yes' => 'Yes, Please',
				'any' => 'I am not sure!',
			],
			'default' => 'no',
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Success: Selected to (Yes, Please).',
			'dependency' => [ 'opt-depend-radio', '==', 'yes' ],
		],

		//
		// Dependency example 5
		[
			'id'      => 'opt-depend-checkbox',
			'type'    => 'checkbox',
			'title'   => 'If selected to (Green) or (Black)',
			'inline'  => true,
			'options' => [
				'blue'   => 'Blue',
				'yellow' => 'Yellow',
				'green'  => 'Green',
				'black'  => 'Black',
				'white'  => 'White',
			],
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Success: Selected to (Green).',
			'dependency' => [ 'opt-depend-checkbox', 'any', 'green,black' ],
		],

		//
		// Dependency example 6
		[
			'id'      => 'opt-depend-image-select',
			'type'    => 'image_select',
			'title'   => 'If selected to (Blue) box',
			'options' => [
				'green'  => 'http://codestarframework.com/assets/images/placeholder/100x80-2ecc71.gif',
				'red'    => 'http://codestarframework.com/assets/images/placeholder/100x80-e74c3c.gif',
				'yellow' => 'http://codestarframework.com/assets/images/placeholder/100x80-ffbc00.gif',
				'blue'   => 'http://codestarframework.com/assets/images/placeholder/100x80-3498db.gif',
				'gray'   => 'http://codestarframework.com/assets/images/placeholder/100x80-555555.gif',
			],
			'default' => 'green',
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Success: Selected to (Blue) box.',
			'dependency' => [ 'opt-depend-image-select', '==', 'blue' ],
		],

		//
		// Dependency example 6
		[
			'id'      => 'opt-depend-image-select-any',
			'type'    => 'image_select',
			'title'   => 'If selected to (Red) or (Blue) box',
			'options' => [
				'green'  => 'http://codestarframework.com/assets/images/placeholder/100x80-2ecc71.gif',
				'red'    => 'http://codestarframework.com/assets/images/placeholder/100x80-e74c3c.gif',
				'yellow' => 'http://codestarframework.com/assets/images/placeholder/100x80-ffbc00.gif',
				'blue'   => 'http://codestarframework.com/assets/images/placeholder/100x80-3498db.gif',
				'gray'   => 'http://codestarframework.com/assets/images/placeholder/100x80-555555.gif',
			],
			'default' => 'green',
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Success: Selected to (Red) or (Blue) box.',
			'dependency' => [ 'opt-depend-image-select-any', 'any', 'red,blue' ],
		],

		[
			'type'    => 'subheading',
			'content' => 'Visible Dependencies',
		],

		//
		// Dependency example 7
		[
			'id'    => 'opt-depend-visible-switcher',
			'type'  => 'switcher',
			'title' => 'Switched to (ON)',
			'label' => 'Below fields are visibling instead of hiding. Switched to (ON) for use them.',
		],

		[
			'id'         => 'opt-depend-visible-text',
			'type'       => 'text',
			'title'      => 'Visible Text',
			'dependency' => [ 'opt-depend-visible-switcher', '==', 'true', '', 'visible' ],
		],

		[
			'id'          => 'opt-depend-visible-select',
			'type'        => 'select',
			'title'       => 'Visible Select',
			'placeholder' => 'Select an option',
			'options'     => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
			'dependency'  => [ 'opt-depend-visible-switcher', '==', 'true', '', 'visible' ],
		],

		//
		// Dependency example 8
		[
			'type'    => 'subheading',
			'content' => 'Nested Dependencies',
		],

		[
			'id'    => 'opt-depend-switcher-1',
			'type'  => 'switcher',
			'title' => 'If switched to (ON) --->',
		],

		[
			'id'          => 'opt-depend-select-1',
			'type'        => 'select',
			'title'       => '---> and selected to (Blue)',
			'placeholder' => 'Select a color',
			'options'     => [
				'blue'   => 'Blue',
				'yellow' => 'Yellow',
				'green'  => 'Green',
				'black'  => 'Black',
				'white'  => 'White',
			],
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Success: Switched to (ON) and selected to (Blue).',
			'dependency' => [ 'opt-depend-switcher-1|opt-depend-select-1', '==|==', 'true|blue' ],
		],

		//
		// Dependency example 9
		[
			'type'    => 'subheading',
			'content' => 'Another Nested Dependencies',
		],

		[
			'id'          => 'opt-nested-select-1',
			'type'        => 'select',
			'title'       => 'If selected to (Black) or (White) --->',
			'placeholder' => 'Select a color',
			'options'     => [
				'blue'   => 'Blue',
				'yellow' => 'Yellow',
				'green'  => 'Green',
				'black'  => 'Black',
				'white'  => 'White',
			],
		],

		[
			'id'          => 'opt-nested-select-2',
			'type'        => 'select',
			'title'       => '---> and selected to (Large) --->',
			'placeholder' => 'Select a size',
			'options'     => [
				'small'  => 'Small',
				'middle' => 'Middle',
				'large'  => 'Large',
				'xlage'  => 'XLarge',
				'xxlage' => 'XXLarge',
			],
			'dependency'  => [ 'opt-nested-select-1', 'any', 'black,white' ],
		],

		[
			'id'          => 'opt-nested-select-3',
			'type'        => 'select',
			'title'       => '---> and selected to (Hello)',
			'placeholder' => 'Select a word',
			'options'     => [
				'hello' => 'Hello',
				'world' => 'World',
			],
			'dependency'  => [ 'opt-nested-select-1|opt-nested-select-2', 'any|==', 'black,white|large' ],
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Congratulations, You are here now!',
			'dependency' => [
				'opt-nested-select-1|opt-nested-select-2|opt-nested-select-3',
				'any|==|==',
				'black,white|large|hello',
			],
		],

		[
			'type'    => 'subheading',
			'content' => 'Contains Dependencies',
		],

		[
			'id'      => 'opt-depend-checkbox-1',
			'type'    => 'checkbox',
			'title'   => 'Select a color or more.',
			'inline'  => true,
			'options' => [
				'blue'   => 'Blue',
				'yellow' => 'Yellow',
				'green'  => 'Green',
			],
		],

		[
			'type'       => 'notice',
			'style'      => 'info',
			'content'    => 'Selected (blue) color.',
			'dependency' => [ 'opt-depend-checkbox-1', 'contains', 'blue' ],
		],

		[
			'type'       => 'notice',
			'style'      => 'warning',
			'content'    => 'Selected (yellow) color.',
			'dependency' => [ 'opt-depend-checkbox-1', 'contains', 'yellow' ],
		],

		[
			'type'       => 'notice',
			'style'      => 'success',
			'content'    => 'Selected (green) color.',
			'dependency' => [ 'opt-depend-checkbox-1', 'contains', 'green' ],
		],

	],
] );

//
// Validate
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Validate',
	'description' => 'Visit documentation for more details: <a href="http://codestarframework.com/documentation/#/faq?id=how-to-use-validate" target="_blank">How to use validate</a>',
	'fields'      => [

		[
			'id'       => 'opt-validate-1',
			'type'     => 'text',
			'title'    => 'Email validate',
			'subtitle' => 'This text field only allows validated email address.',
			'default'  => 'info@domain.com',
			'validate' => 'kianfr_customize_validate_email',
		],

		[
			'id'       => 'opt-validate-2',
			'type'     => 'text',
			'title'    => 'Numeric validate',
			'subtitle' => 'This text field only allows numbers',
			'default'  => '123456',
			'validate' => 'kianfr_customize_validate_numeric',
		],

		[
			'id'       => 'opt-validate-3',
			'type'     => 'text',
			'title'    => 'Required validate',
			'subtitle' => 'This text field is required, cannot be pass empty.',
			'default'  => 'Lorem ipsum value',
			'validate' => 'kianfr_customize_validate_required',
		],

		[
			'id'       => 'opt-validate-4',
			'type'     => 'text',
			'title'    => 'URL validate',
			'subtitle' => 'This text field only allows validated url address.',
			'default'  => 'http://codestarframework.com',
			'validate' => 'kianfr_customize_validate_url',
		],

	],
] );

//
// Sanitize
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Sanitize',
	'description' => 'Visit documentation for more details: <a href="http://codestarframework.com/documentation/#/faq?id=how-to-use-sanitize" target="_blank">How to use sanitize</a>',
	'fields'      => [

		[
			'id'       => 'opt-sanitize-1',
			'type'     => 'text',
			'title'    => 'Sanitize (a) to (b)',
			'subtitle' => 'Replacing letter (a) to letter (b). for eg. apple to bpple',
			'sanitize' => 'kianfr_sanitize_replace_a_to_b',
		],

		[
			'id'       => 'opt-sanitize-2',
			'type'     => 'text',
			'title'    => 'Sanitize Title',
			'subtitle' => 'Converting (space) to (-) and (uppercase) letters to (lowercase) letters. for eg. Hello World to hello-world',
			'sanitize' => 'kianfr_sanitize_title',
		],

	],
] );

//
// Others
//
KIANFR::createSection( $prefix, [
	'parent'      => 'fields',
	'title'       => 'Others',
	'description' => 'Visit documentation for more details: <a href="http://codestarframework.com/documentation/#/fields?id=others" target="_blank">Others</a>',
	'fields'      => [

		[
			'type'    => 'heading',
			'content' => 'This is a heading field',
		],

		[
			'type'    => 'subheading',
			'content' => 'This is a subheading field',
		],

		[
			'type'    => 'content',
			'content' => 'This is a content field',
		],

		[
			'type'    => 'submessage',
			'style'   => 'success',
			'content' => 'This is a <strong>submessage</strong> field. And using style <strong>success</strong>',
		],

		[
			'type'    => 'content',
			'content' => 'This is a content field',
		],
		[
			'type'    => 'submessage',
			'style'   => 'info',
			'content' => 'This is a <strong>submessage</strong> field. And using style <strong>info</strong>',
		],

		[
			'type'    => 'submessage',
			'style'   => 'warning',
			'content' => 'This is a <strong>submessage</strong> field. And using style <strong>warning</strong>',
		],

		[
			'type'    => 'submessage',
			'style'   => 'danger',
			'content' => 'This is a <strong>submessage</strong> field. And using style <strong>danger</strong>',
		],

		[
			'type'    => 'notice',
			'style'   => 'success',
			'content' => 'This is a <strong>notice</strong> field. And using style <strong>success</strong>',
		],

		[
			'type'    => 'notice',
			'style'   => 'info',
			'content' => 'This is a <strong>notice</strong> field. And using style <strong>info</strong>',
		],

		[
			'type'    => 'notice',
			'style'   => 'warning',
			'content' => 'This is a <strong>notice</strong> field. And using style <strong>warning</strong>',
		],

		[
			'type'    => 'notice',
			'style'   => 'danger',
			'content' => 'This is a <strong>notice</strong> field. And using style <strong>danger</strong>',
		],

		[
			'type'    => 'content',
			'content' => 'This is a <strong>content</strong> field. You can write some contents here.',
		],

	],
] );

//
// Create a section
//
KIANFR::createSection( $prefix, [
	'title'    => 'KIANFR - Reset & Backup',
	'priority' => 3,
	'fields'   => [

		[
			'type' => 'backup',
		],

	],
] );
