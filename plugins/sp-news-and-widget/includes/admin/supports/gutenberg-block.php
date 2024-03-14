<?php
/**
 * Blocks Initializer
 * 
 * @package WP News and Scrolling Widgets
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wpnw_register_guten_block() {

	// Block Editor Script
	wp_register_script( 'wpnw-block-js', WPNW_URL.'assets/js/blocks.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-components' ), WPNW_VERSION, true );
	wp_localize_script( 'wpnw-block-js', 'Wpnwf_Block', array(
																'pro_demo_link'		=> 'https://demo.essentialplugin.com/prodemo/news-plugin-pro/',
																'free_demo_link'	=> 'https://demo.essentialplugin.com/sp-news/',
																'pro_link'			=> WPNW_PLUGIN_LINK_UNLOCK,
															));

	// Register block and explicit attributes for grid
	register_block_type( 'wpnw/sp-news', array(
		'attributes' => array(
			'grid' => array(
							'type'		=> 'string',
							'default'	=> '1',
						),
			'show_date' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_category_name' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_content' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_full_content' => array(
							'type'		=> 'boolean',
							'default'	=> false,
						),
			'content_words_limit' => array(
							'type'		=> 'number',
							'default'	=> 20,
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> 10,
						),
			'orderby' => array(
							'type'		=> 'string',
							'default'	=> 'date',
						),
			'order' => array(
							'type'		=> 'string',
							'default'	=> 'desc',
						),
			'category' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'pagination' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'pagination_type' => array(
							'type'		=> 'string',
							'default'	=> 'numeric',
						),
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
		),
		'render_callback' => 'wpnw_get_news_shortcode',
	));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'wpnw-block-js', 'sp-news-and-widget', WPNW_DIR . '/languages' );
	}

}
add_action( 'init', 'wpnw_register_guten_block' );

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @since 1.0
 */
function wpnw_block_assets() {	
}
add_action( 'enqueue_block_assets', 'wpnw_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * 
 * @since 1.0
 */
function wpnw_editor_assets() {

	// Block Editor CSS
	if( ! wp_style_is( 'wpos-free-guten-block-css', 'registered' ) ) {
		wp_register_style( 'wpos-free-guten-block-css', WPNW_URL.'assets/css/blocks.editor.build.css', array( 'wp-edit-blocks' ), WPNW_VERSION );
	}

	// Block Editor Script
	wp_enqueue_style( 'wpos-free-guten-block-css' );
	wp_enqueue_script( 'wpnw-block-js' );

}
add_action( 'enqueue_block_editor_assets', 'wpnw_editor_assets' );

/**
 * Adds an extra category to the block inserter
 *
 * @since 1.0
 */
function wpnw_add_block_category( $categories ) {

	$guten_cats = wp_list_pluck( $categories, 'slug' );

	if( ! in_array( 'essp_guten_block', $guten_cats ) ) {
		$categories[] = array(
							'slug'	=> 'essp_guten_block',
							'title'	=> esc_html__('Essential Plugin Blocks', 'sp-news-and-widget'),
							'icon'	=> null,
						);
	}

	return $categories;
}
add_filter( 'block_categories_all', 'wpnw_add_block_category' );