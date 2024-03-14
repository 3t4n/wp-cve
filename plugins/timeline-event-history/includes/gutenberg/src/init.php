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
function gutenberg_cgb_block_assets() { // phpcs:ignore
	// Register block styles for both frontend + backend.
	wp_register_style(
		'gutenberg-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'gutenberg-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array(  'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'gutenberg-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'gutenberg-cgb-block-js',
		'timelineGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'blockSrcUrl'   => plugins_url( '/block', __FILE__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'tablet_breakpoint' => '976',
			'mobile_breakpoint' => '767',
			'timelineItems' => get_timeline_items(),
			'assets' 		=> TIMELINE_WP_ASSETS,
			// Add more data here that you want to access from `cgbGlobal` object.
		]
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
		'cgb/block-gutenberg', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'gutenberg-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'gutenberg-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'gutenberg-cgb-block-editor-css',
		)
	);
}

// Hook: Block assets.
add_action( 'init', 'gutenberg_cgb_block_assets' );

// Add custom block category
add_filter( 'block_categories_all', function( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'timeline',
				'title' => __( 'Timeline WP', timeline_wp ),
			),
		)
	);
}, 10, 2 );


/**
 * Function to integrate Timeline.
 *
 * @since 1.10.0
 */
function get_timeline_items() {
	$field_options = array();
	if ( class_exists( 'Timeline_WP' ) ) {

		$args = array(
			'post_type'      => 'timeline_wp',
			'posts_per_page' => -1,
		);

		$items            = get_posts( $args );
		$field_options[0] = array(
			'value' => -1,
			'label' => __( 'Select Timeline', timeline_wp ),
		);

		if ( $items ) {
			foreach ( $items as $item ) {
				$field_options[] = array(
					'value' => $item->ID,
					'label' => $item->post_title,
				);
			}
		}
	}
	if ( empty( $field_options ) ) {
		$field_options = array(
			'-1' => __( 'You have not added any Timeline_wp yet.', timeline_wp ),
		);
	}
	return $field_options;
}

function timeline_shortcode() { 	// @codingStandardsIgnoreStart
    $id = intval($_POST['formId']);
    // @codingStandardsIgnoreEnd
	if ( $id && 0 !== $id && -1 !== $id ) {
		$data['html'] = do_shortcode( '[timeline_wp id="' . $id . '"]' );
	} else {
		$data['html'] = '<p>' . __( 'Please select a valid Timeline.', timeline_wp ) . '</p>';
	}
	wp_send_json_success( $data );
}

add_action( 'wp_ajax_timeline_shortcode', 'timeline_shortcode' );
add_action( 'wp_ajax_nopriv_timeline_shortcode', 'timeline_shortcode' );