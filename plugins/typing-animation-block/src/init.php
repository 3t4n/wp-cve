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

// TODO put into own file
function typing_animation_render_callback($attributes) {
	$element = $attributes['element'];
	return sprintf('<%2$s class="typed-wrapper"><span class="typing-animation" data-options="%1$s" /></%2$s>',
		htmlspecialchars(json_encode($attributes)),
		 $element ? $element : 'h2'
	);
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function typing_animation_block_cgb_block_assets() { // phpcs:ignore
	// Register block editor script for backend.
	wp_register_script(
		'typing_animation_block-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'typing_animation_block-cgb-block-js',
		'cgbGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			// Add more data here that you want to access from `cgbGlobal` object.
		]
	);

	wp_register_script(
		'typed-js', // Handle.
		plugins_url( '/vendor/typed.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		null,
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	wp_register_style(
    		'typing_animation_block-cgb-block-editor-css', // Handle.
    		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
    		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
    		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
    );

	wp_register_script(
		'frontend-js', // Handle.
		plugins_url( '/src/frontend.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		null,
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

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
		'cgb/block-typing-animation-block', array(
			'script'			=>	array('typed-js', 'frontend-js'),
			// Enqueue blocks.build.js in the editor only.
			'editor_script' 	=> 'typing_animation_block-cgb-block-js',
			// The callback for rendering on server side.
			'render_callback' 	=> 'typing_animation_render_callback',
			// Enqueue blocks.editor.build.css in the editor only.
        	'editor_style'  	=> 'typing_animation_block-cgb-block-editor-css'
		)
	);
}

// Hook: Block assets.
add_action( 'init', 'typing_animation_block_cgb_block_assets' );
