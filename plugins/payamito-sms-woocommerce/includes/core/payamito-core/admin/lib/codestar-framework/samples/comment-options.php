<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Comment Metabox
// Set a unique slug-like ID
//
$prefix_comment = '_prefix_comment';

//
// Create a comment metabox
//
KIANFR::createCommentMetabox( $prefix_comment, [
	'title' => 'Custom Comment Options',
] );

//
// Create a section
//
KIANFR::createSection( $prefix_comment, [

	'fields' => [

		//
		// A text field
		//
		[
			'id'    => 'opt-text',
			'type'  => 'text',
			'title' => 'Text',
		],

		[
			'id'    => 'opt-textarea',
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
			'id'    => 'opt-switcher',
			'type'  => 'switcher',
			'title' => 'Switcher',
			'label' => 'The label text of the switcher.',
		],

		[
			'id'    => 'opt-color',
			'type'  => 'color',
			'title' => 'Color',
		],

		[
			'id'    => 'opt-checkbox',
			'type'  => 'checkbox',
			'title' => 'Checkbox',
			'label' => 'The label text of the checkbox.',
		],

		[
			'id'      => 'opt-radio',
			'type'    => 'radio',
			'title'   => 'Radio',
			'options' => [
				'yes' => 'Yes, Please.',
				'no'  => 'No, Thank you.',
			],
			'default' => 'yes',
		],

		[
			'id'          => 'opt-select',
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
