<?php
/**
 * Plugin Name: Gutenberg Examples Dynamic Block
 * Plugin URI: https://github.com/WordPress/blockons
 * Description: This is a plugin demonstrating how to register new blocks for the Gutenberg editor.
 * Version: 1.1.0
 * Author: the Gutenberg Team
 *
 * @package blockons
 */

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 */
function blockons_wc_featured_product_register_block() {
	register_block_type(
		__DIR__,
		array(
			'render_callback' => 'blockons_wc_featured_product_render_callback',
		)
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'blockons-wc-featured-product-editor-script', 'blockons', BLOCKONS_PLUGIN_DIR . 'lang' );
	}
}
add_action( 'init', 'blockons_wc_featured_product_register_block' );


/**
 * This function is called when the block is being rendered on the front end of the site
 *
 * @param array    $attributes     The array of attributes for this block.
 * @param string   $content        Rendered block output. ie. <InnerBlocks.Content />.
 * @param WP_Block $block_instance The instance of the WP_Block class that represents the block being rendered.
 */
function blockons_wc_featured_product_render_callback( $attributes, $content, $block_instance ) {
	ob_start();
	/**
	 * All of passed parameters are still accessible in the file.
	 */
	require plugin_dir_path( __FILE__ ) . 'template.php';
	return ob_get_clean();
}
