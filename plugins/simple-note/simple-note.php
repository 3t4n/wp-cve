<?php

/*
Plugin Name: Simple Note
Plugin URI: https://webliberty.ru/blockquote/
Description: The plugin allows to create simple text note.
Version: 1.7
Author: Webliberty
Author URI: https://webliberty.ru/
Text Domain: simple-note
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) || exit;

function simple_note_backend() {
	wp_enqueue_script(
		'simple-note-backend-script',
		plugins_url( 'js/block.min.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'js/block.min.js' )
	);

	wp_enqueue_style(
		'simple-note-backend-style',
		plugins_url( 'css/editor.min.css', __FILE__ ),
		array( 'wp-edit-blocks', 'dashicons' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'css/editor.min.css' )
	);

	register_block_type( 'simple-note/info', array(
		'editor_script' => 'simple-note-backend-script',
		'editor_style'  => 'simple-note-backend-style',
	) );

	register_block_type( 'simple-note/success', array(
		'editor_script' => 'simple-note-backend-script',
		'editor_style'  => 'simple-note-backend-style',
	) );

	register_block_type( 'simple-note/warning', array(
		'editor_script' => 'simple-note-backend-script',
		'editor_style'  => 'simple-note-backend-style',
	) );

	register_block_type( 'simple-note/error', array(
		'editor_script' => 'simple-note-backend-script',
		'editor_style'  => 'simple-note-backend-style',
	) );

	register_block_type( 'simple-note/quote', array(
		'editor_script' => 'simple-note-backend-script',
		'editor_style'  => 'simple-note-backend-style',
	) );

	wp_set_script_translations( 'simple-note-backend-script', 'simple-note' );
}
add_action( 'enqueue_block_editor_assets', 'simple_note_backend' );

function simple_note_frontend() {
	if ( has_block( 'simple-note/info' ) || has_block( 'simple-note/success' ) || has_block( 'simple-note/warning' ) || has_block( 'simple-note/error' ) || has_block( 'simple-note/quote' ) ) {
		wp_enqueue_style(
			'simple-note-frontend-style',
			plugins_url( 'css/style.min.css', __FILE__ ),
			filemtime( plugin_dir_path( __FILE__ ) . 'css/style.min.css' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'simple_note_frontend' );