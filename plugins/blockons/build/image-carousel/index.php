<?php
/**
 * Plugin Name: Image Carousel Block
 * Plugin URI: https://github.com/WordPress/blockons
 * Description: An Image Carousel Block.
 * Version: 1.1.0
 * Author: Kaira
 *
 * @package blockons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function blockons_image_carousel_register_block() {

	// Register the block by passing the location of block.json.
	register_block_type(
		__DIR__,
		array(
			'render_callback' => 'blockons_image_carousel_render_callback',
		)
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'blockons-image-carousel-editor-script', 'blockons', BLOCKONS_PLUGIN_DIR . 'lang' );
	}

}
add_action( 'init', 'blockons_image_carousel_register_block' );

/**
 * This function is called when the block is being rendered on the front end of the site
 *
 * @param array    $attributes     The array of attributes for this block.
 * @param string   $content        Rendered block output. ie. <InnerBlocks.Content />.
 * @param WP_Block $block_instance The instance of the WP_Block class that represents the block being rendered.
 */
function blockons_image_carousel_render_callback( $attributes, $content, $block_instance ) {
	ob_start();
	/**
	 * All of passed parameters are still accessible in the file.
	 */
	require plugin_dir_path( __FILE__ ) . 'template.php';
	return ob_get_clean();
}
