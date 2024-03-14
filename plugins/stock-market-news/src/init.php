<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

if (!function_exists( 'register_block_type')) exit;
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function stockdio_news_cgb_editor_assets() {
	//$version = stockdio_news_version;
	$version = date_timestamp_get(date_create());
	// Scripts.
	wp_enqueue_script(
		'stockdio-news-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js?v='.$version, dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

		//Scripts
		wp_enqueue_script(
			"EditcustomStockdioJs",
			plugins_url("/assets/stockdio-wp.js?v=".$version, dirname( __FILE__ )), 
			array(), 
			false );

		// Styles.
		wp_enqueue_style(
			'stockdio-news-cgb-block-editor-css', // Handle.
			plugins_url( 'dist/blocks.editor.build.css?v='.$version, dirname( __FILE__ ) ), // Block editor CSS.
			array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
			// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
		);
	
} // End function stockdio-news_cgb_editor_assets().

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'stockdio_news_cgb_editor_assets' );

if (!function_exists('stockdio_block_categories_function')) {
	add_filter( 'block_categories', function( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'stockdio-financial-blocks',
					'title' => __( 'Stockdio Financial Visualizations', 'stockdio-financial-blocks' ),
				),
			)
		);
	}, 10, 2 );
	function stockdio_block_categories_function() {
		return;
	}
}


function register_news_api_blocks() {
    register_block_type(
        'cgb/stockdio-company-news',
        array(
            'attributes' => array(
                'content' => array(
                    'type' => 'string',
                ),
                'className' => array(
                    'type' => 'string',
                ),
            ),
            'render_callback' => 'news_cgb_api_block_posts',
        )
    );
}

add_action('init', 'register_news_api_blocks');

function news_cgb_api_block_posts( $attributes ) {
	return stockdio_news_board_func($attributes);
}

