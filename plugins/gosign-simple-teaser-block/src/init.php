<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package GSTB
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
function gosign_simple_teaser_block_gstb_block_assets() {
	// Styles.
	wp_enqueue_style(
		'gosign_simple_teaser_block-gstb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ) // Block style CSS.
		// array( 'wp-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: filemtime — Gets file modification time.
	);
} // End function gosign_simple_teaser_block_gstb_block_assets().

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'gosign_simple_teaser_block_gstb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function gosign_simple_teaser_block_gstb_editor_assets() {
	// Scripts.
	wp_register_script(
		'gosign_simple_teaser_block-gstb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_register_style(
		'gosign_simple_teaser_block-gstb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
	);

	//checking matomo plugin is active or not.
	if(in_array( 'matomo/matomo.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		$block_types = is_plugin_active( 'matomo/matomo.php' );
		wp_localize_script(
			'gosign_simple_teaser_block-gstb-block-js',
			'MATOMOJSOBJECT_GST',
			['SimpleTeaserMatomo' => $block_types]
		);
		wp_enqueue_script('gosign_simple_teaser_block-gstb-block-js');

		// enable js for matomo evnet in FE
		wp_enqueue_script(
			'simple_teaser_block_matomo__js', // Handle.
			plugins_url( '/src/block/simpleMatomoEvent.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
			array( 'wp-i18n', 'wp-element', 'jquery' ), // Dependencies, defined above.
			// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
			true // Enqueue the script in the footer.
		);
	}else {
		wp_localize_script(
			'gosign_simple_teaser_block-gstb-block-js',
			'MATOMOJSOBJECT_GST',
			['SimpleTeaserMatomo' => 0]
		);
		wp_enqueue_script('gosign_simple_teaser_block-gstb-block-js');
	}

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'gstb/block-gosign-simple-teaser-block', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'full_button_block-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'gosign_simple_teaser_block-gstb-block-editor-css',
		)
	);

} // End function gosign_simple_teaser_block_gstb_editor_assets().

// Hook: Editor assets.
add_action( 'init', 'gosign_simple_teaser_block_gstb_editor_assets' );
