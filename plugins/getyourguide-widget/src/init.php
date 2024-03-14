<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * `wp-blocks`: includes block type registration and related functions.
 *
 * @since 1.0.0
 */
function gyg_wp_plugin_cgb_block_assets() {
	wp_enqueue_script(
		'gyg_wp_plugin-post-js', // Handle.
		plugins_url('dist/post.js', dirname( __FILE__ )),
		// plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks' ), // Dependencies, defined above.
		filemtime( plugin_dir_path( __DIR__ ) . 'dist/post.js' ), // Version: filemtime — Gets file modification time.
		true
	);
} // End function gyg_wp_plugin_cgb_block_assets().

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'gyg_wp_plugin_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function gyg_wp_plugin_cgb_editor_assets() {
	wp_enqueue_script(
		'gyg_wp_plugin-cgb-block-js',
		plugins_url('dist/main.js', dirname( __FILE__ )),
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ), // Dependencies, defined above.
		filemtime( plugin_dir_path( __DIR__ ) . 'dist/main.js' ), // Version: filemtime — Gets file modification time.
		true
	);
} // End function gyg_wp_plugin_cgb_editor_assets().

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'gyg_wp_plugin_cgb_editor_assets' );
