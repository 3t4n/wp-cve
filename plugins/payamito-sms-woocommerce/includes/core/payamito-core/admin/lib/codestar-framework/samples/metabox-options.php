<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Metabox of the PAGE
// Set a unique slug-like ID
//
$prefix_page_opts = '_prefix_page_options';

//
// Create a metabox
//
KIANFR::createMetabox( $prefix_page_opts, [
	'title'        => 'Custom Page Options',
	'post_type'    => 'page',
	'show_restore' => true,
] );

//
// Create a section
//
KIANFR::createSection( $prefix_page_opts, [
	'title'  => 'Overview',
	'icon'   => 'fas fa-rocket',
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
			'id'      => 'opt-color',
			'type'    => 'color',
			'title'   => 'Color',
			'default' => '#3498db',
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

//
// Create a section
//
KIANFR::createSection( $prefix_page_opts, [
	'title'  => 'More Fields',
	'icon'   => 'fas fa-tint',
	'fields' => [

		[
			'id'      => 'opt-image-select',
			'type'    => 'image_select',
			'title'   => 'Image Select',
			'options' => [
				'opt-1' => 'http://codestarframework.com/assets/images/placeholder/100x80-2ecc71.gif',
				'opt-2' => 'http://codestarframework.com/assets/images/placeholder/100x80-e74c3c.gif',
				'opt-3' => 'http://codestarframework.com/assets/images/placeholder/100x80-ffbc00.gif',
				'opt-4' => 'http://codestarframework.com/assets/images/placeholder/100x80-3498db.gif',
				'opt-5' => 'http://codestarframework.com/assets/images/placeholder/100x80-555555.gif',
			],
			'default' => 'opt-1',
		],

		[
			'id'    => 'opt-background',
			'type'  => 'background',
			'title' => 'Background',
		],

		[
			'type'    => 'notice',
			'style'   => 'success',
			'content' => 'A <strong>notice</strong> field with <strong>success</strong> style.',
		],

		[
			'id'    => 'opt-icon',
			'type'  => 'icon',
			'title' => 'Icon',
		],

		[
			'id'    => 'opt-alt-text',
			'type'  => 'text',
			'title' => 'Text',
		],

		[
			'id'         => 'opt-alt-textarea',
			'type'       => 'textarea',
			'title'      => 'Textarea',
			'subtitle'   => 'A textarea with shortcoder.',
			'shortcoder' => 'kianfr_demo_shortcodes',
		],

	],
] );

//
// Metabox of the POST
// Set a unique slug-like ID
//
$prefix_post_opts = '_prefix_post_options';

//
// Create a metabox
//
KIANFR::createMetabox( $prefix_post_opts, [
	'title'        => 'Custom Post Options',
	'post_type'    => 'post',
	'show_restore' => true,
] );

//
// Create a section
//
KIANFR::createSection( $prefix_post_opts, [
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

//
// Metabox of the PAGE and POST both.
// Set a unique slug-like ID
//
$prefix_meta_opts = '_prefix_meta_options';

//
// Create a metabox
//
KIANFR::createMetabox( $prefix_meta_opts, [
	'title'     => 'Custom Options',
	'post_type' => [ 'post', 'page' ],
	'context'   => 'side',
] );

//
// Create a section
//
KIANFR::createSection( $prefix_meta_opts, [
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
			'id'    => 'opt-switcher',
			'type'  => 'switcher',
			'title' => 'Switcher',
			'label' => 'The label of the switcher.',
		],

		[
			'id'    => 'opt-color',
			'type'  => 'color',
			'title' => 'Color',
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
