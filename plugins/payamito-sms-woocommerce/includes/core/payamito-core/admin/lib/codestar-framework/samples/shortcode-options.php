<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'kianfr_demo_shortcodes';

//
// Create a shortcoder
//
KIANFR::createShortcoder( $prefix, [
	// 'button_title'   => 'Add Shortcode',
	// 'select_title'   => 'Select a shortcode',
	// 'insert_title'   => 'Insert Shortcode',
	// 'show_in_editor' => true,
	// 'gutenberg'      => array(
	//   'title'        => 'KIANFR Shortcodes',
	//   'description'  => 'KIANFR Shortcode Block',
	//   'icon'         => 'screenoptions',
	//   'category'     => 'widgets',
	//   'keywords'     => array( 'shortcode', 'kianfr', 'insert' ),
	//   'placeholder'  => 'Write shortcode here...',
	// )
] );

//
// A shortcode [foo title=""]
//
KIANFR::createSection( $prefix, [
	'title'     => '[foo] view: normal',
	'view'      => 'normal',
	'shortcode' => 'foo',
	'fields'    => [

		[
			'id'    => 'opt_title',
			'type'  => 'text',
			'title' => 'Title',
		],

		[
			'id'    => 'opt_switcher',
			'type'  => 'switcher',
			'title' => 'Switcher',
			'label' => 'The label text of the switcher.',
		],

	],
] );

//
// A shortcode [foo title=""]content[/foo]
//
KIANFR::createSection( $prefix, [
	'title'     => '[foo] view: normal alternative',
	'view'      => 'normal',
	'shortcode' => 'foo',
	'fields'    => [

		[
			'id'    => 'opt_title',
			'type'  => 'text',
			'title' => 'Title',
		],

		[
			'id'      => 'opt_checkbox',
			'type'    => 'checkbox',
			'title'   => 'Options',
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

		[
			'id'      => 'opt_select',
			'type'    => 'select',
			'title'   => 'Select',
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

		[
			'id'    => 'content',
			'type'  => 'textarea',
			'title' => 'Content',
		],

	],
] );

//
// A shortcode [content]content[/content][content]content[/content]
//
KIANFR::createSection( $prefix, [
	'title'     => '[foo] view: contents',
	'view'      => 'contents',
	'shortcode' => 'content',
	'fields'    => [

		[
			'id'    => 'opt_content_1',
			'type'  => 'textarea',
			'title' => 'Content 1',
		],

		[
			'id'    => 'opt_content_2',
			'type'  => 'textarea',
			'title' => 'Content 2',
		],

	],
] );

//
// A shortcode [opt_content_1]content[/opt_content_1][opt_content_2]content[/opt_content_2]
//
KIANFR::createSection( $prefix, [
	'title'  => '[foo] view: contents alternative',
	'view'   => 'contents',
	'fields' => [

		[
			'id'    => 'opt_content_1',
			'type'  => 'textarea',
			'title' => 'Content 1',
		],

		[
			'id'    => 'opt_content_2',
			'type'  => 'textarea',
			'title' => 'Content 2',
		],

	],
] );

KIANFR::createSection( $prefix, [
	'title'           => '[foo] view: group',
	'view'            => 'group',
	'shortcode'       => 'foo',
	'group_shortcode' => 'nested_foo',
	'group_fields'    => [

		[
			'id'    => 'opt_title',
			'type'  => 'text',
			'title' => 'Title',
		],

		[
			'id'    => 'content',
			'type'  => 'textarea',
			'title' => 'Content',
		],

	],
] );

KIANFR::createSection( $prefix, [
	'title'           => '[foo] view: group alternative',
	'view'            => 'group',
	'shortcode'       => 'foo',
	'fields'          => [

		[
			'id'    => 'opt_switcher',
			'type'  => 'switcher',
			'title' => 'Switcher',
			'label' => 'The label text of the switcher.',
		],

		[
			'id'      => 'opt_select',
			'type'    => 'select',
			'title'   => 'Select',
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

	],
	'group_shortcode' => 'nested_foo',
	'group_fields'    => [

		[
			'id'    => 'title',
			'type'  => 'text',
			'title' => 'Title',
		],

		[
			'id'    => 'content',
			'type'  => 'textarea',
			'title' => 'Content',
		],

	],
] );

KIANFR::createSection( $prefix, [
	'title'     => '[foo] view: repeater',
	'view'      => 'repeater',
	'shortcode' => 'foo',
	'fields'    => [

		[
			'id'    => 'opt_title',
			'type'  => 'text',
			'title' => 'Title',
		],

		[
			'id'    => 'opt_switcher',
			'type'  => 'switcher',
			'title' => 'Switcher',
			'label' => 'The label text of the switcher.',
		],

		[
			'id'      => 'opt_select',
			'type'    => 'select',
			'title'   => 'Select',
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

	],
] );

KIANFR::createSection( $prefix, [
	'title'     => '[foo] view: repeater alternative',
	'view'      => 'repeater',
	'shortcode' => 'foo',
	'fields'    => [

		[
			'id'    => 'opt_title',
			'type'  => 'text',
			'title' => 'Title',
		],

		[
			'id'    => 'opt_switcher',
			'type'  => 'switcher',
			'title' => 'Switcher',
			'label' => 'The label text of the switcher.',
		],

		[
			'id'      => 'opt_select',
			'type'    => 'select',
			'title'   => 'Select',
			'options' => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

		[
			'id'    => 'content',
			'type'  => 'textarea',
			'title' => 'Content',
		],

	],
] );
