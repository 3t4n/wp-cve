<?php
/**
 * Plugin Name: Search Block
 * Plugin URI: https://github.com/WordPress/blockons
 * Description: An Search Block.
 * Version: 1.1.0
 * Author: Kaira
 *
 * @package blockons
 */
defined( 'ABSPATH' ) || exit;

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 */
function blockons_search_register_block() {
	// Register the block by passing the location of block.json.
	register_block_type( __DIR__ );

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'blockons-search-editor-script', 'blockons', BLOCKONS_PLUGIN_DIR . 'lang' );
	}
}
add_action( 'init', 'blockons_search_register_block' );
