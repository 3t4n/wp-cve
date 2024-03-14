<?php

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the WP eBay Product Feeds Gutenberg editor JavaScript assets.
 *
 * @return void
 */
function ebay_feeds_for_wordpress_enqueue_block_editor_assets() {

	wp_enqueue_script(
		'ebay-feeds-for-wordpress-gutenberg',
		EBAYFEEDSFORWORDPRESS_PLUGIN_URL . '/block-editor-plugin.js',
		array( 'wp-blocks', 'wp-i18n', 'wp-element', /* 'underscore', */ 'wp-editor'  ),
		filemtime( EBAYFEEDSFORWORDPRESS_PLUGIN_PATH . '/block-editor-plugin.js' )
	);

}


/**
 * Wrapper for rendering the block on the front end.
 *
 * @return void
 */
function ebay_feeds_for_wordpress_init_gutenberg_block() {
	register_block_type( 'ebay-feeds-for-wordpress/ebay-feeds-for-wordpress-form', array(
		'render_callback' => 'ebay_feeds_for_wordpress_on_render_block',
	) );
}


/**
 * Render the block properly
 *
 * When building it, shortcodes don't properly render correctly when exporting using the el element, but the actual text widget does.
 *
 * So we add the form using PHP, it may not be right, but it's easy.
 *
 * @param  array 		$attributes 			All attributes saved in the block.
 * @return string 								The eBay Feed
 */
function ebay_feeds_for_wordpress_on_render_block( $attributes ) {

	$extrastring = "";

	if ( is_array( $attributes ) ) {
		if ( isset( $attributes['feedurl'] ) ) {
			$extrastring .= ' feed="' . $attributes['feedurl'] . '" ';
		}

		if ( isset( $attributes['items'] ) ) {
			$extrastring .= ' items="' . $attributes['items'] . '" ';
		}

		if ( isset( $attributes['header'] ) ) {
			$extrastring .= ' header="' . $attributes['header'] . '" ';
		}
	}

	return '[ebayfeedsforwordpress ' . $extrastring . ']';

}
