<?php
/**
 * Blocks Initializer
 * 
 * @package WP Testimonials with rotator widget
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wtwp_register_guten_block() {

	// Block Editor Script
	wp_register_script( 'wtwp-block-js', WTWP_URL.'assets/js/blocks.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-components' ), WTWP_VERSION, true );
	wp_localize_script( 'wtwp-block-js', 'Wtwpf_Block', array(
																'pro_demo_link'		=> 'https://demo.essentialplugin.com/prodemo/pro-testimonials-with-rotator-widget/',
																'free_demo_link'	=> 'https://demo.essentialplugin.com/testimonial-demo/',
																'pro_link'			=> WTWP_PLUGIN_LINK_UNLOCK,
															));

	// Register block and explicit attributes for grid
	register_block_type( 'wtwp/sp-testimonials', array(
		'attributes' => array(
			'design' => array(
							'type'		=> 'string',
							'default'	=> 'design-1',
						),
			'per_row' => array(
							'type'		=> 'number',
							'default'	=> 3,
						),
			'display_client' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'display_job' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'display_company' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'display_quotes' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'display_avatar' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'image_style' => array(
							'type'		=> 'string',
							'default'	=> 'circle',
						),
			'size' => array(
							'type'		=> 'number',
							'default'	=> 100,
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> -1,
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
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
		),
		'render_callback' => 'wptww_get_testimonial',
	));

	//Register block, and explicitly define the attributes for slider
	register_block_type( 'wtwp/sp-testimonials-slider', array(
		'attributes' => array(
			'design' => array(
							'type'		=> 'string',
							'default'	=> 'design-1',
						),
			'display_client' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'display_job' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'display_company' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'display_quotes' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'display_avatar' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'image_style' => array(
							'type'		=> 'string',
							'default'	=> 'circle',
						),
			'size' => array(
							'type'		=> 'number',
							'default'	=> 100,
						),
			'dots' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'arrows' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'slides_column' => array(
							'type'		=> 'number',
							'default'	=> 1,
						),
			'slides_scroll' => array(
							'type'		=> 'number',
							'default'	=> 1,
						),
			'autoplay' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'autoplay_interval' => array(
							'type'		=> 'number',
							'default'	=> 3000,
						),
			'speed' => array(
							'type'		=> 'number',
							'default'	=> 300,
						),
			'adaptive_height' => array(
							'type'		=> 'string',
							'default'	=> 'false',
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> -1,
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
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
		),
		'render_callback' => 'wptww_get_testimonial_slider',
	));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'wtwp-block-js', 'wp-testimonial-with-widget', WTWP_DIR . '/languages' );
	}

}
add_action( 'init', 'wtwp_register_guten_block' );

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @since 1.0
 */
function wtwp_block_assets() {
}
add_action( 'enqueue_block_assets', 'wtwp_block_assets' );

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
function wtwp_editor_assets() {

	// Block Editor CSS
	if( ! wp_style_is( 'wpos-free-guten-block-css', 'registered' ) ) {
		wp_register_style( 'wpos-free-guten-block-css', WTWP_URL.'assets/css/blocks.editor.build.css', array( 'wp-edit-blocks' ), WTWP_VERSION );
	}

	// Block Editor Script
	wp_enqueue_style( 'wpos-free-guten-block-css' );
	wp_enqueue_script( 'wtwp-block-js' );

}
add_action( 'enqueue_block_editor_assets', 'wtwp_editor_assets' );

/**
 * Adds an extra category to the block inserter
 *
 * @since 1.0
 */
function wtwp_add_block_category( $categories ) {

	$guten_cats = wp_list_pluck( $categories, 'slug' );

	if( ! in_array( 'wpos_guten_block', $guten_cats ) ) {
		$categories[] = array(
							'slug'	=> 'wpos_guten_block',
							'title'	=> __('Essential Plugin Blocks', 'wp-testimonial-with-widget'),
							'icon'	=> null,
						);
	}

	return $categories;
}
add_filter( 'block_categories_all', 'wtwp_add_block_category' );