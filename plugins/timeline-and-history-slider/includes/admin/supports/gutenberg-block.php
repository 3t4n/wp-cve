<?php
/**
 * Blocks Initializer
 * 
 * @package Timeline and History Slider
 * @since 2.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wpostahs_register_guten_block() {

	// Block Editor Script
	wp_register_script( 'wpostahs-block-js', WPOSTAHS_URL.'assets/js/blocks.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-components' ), WPOSTAHS_VERSION, true );
	wp_localize_script( 'wpostahs-block-js', 'WposTahs_Block', array(
																'pro_demo_link'		=> 'https://demo.essentialplugin.com/prodemo/timeline-and-history-slider-pro/',
																'free_demo_link'	=> 'https://demo.essentialplugin.com/timeline-and-history-slider-demo/',
																'pro_link'			=> WPOSTAHS_PLUGIN_LINK_UNLOCK,
															));

	// Register block and explicit attributes for grid
	register_block_type( 'wpostahs/th-slider', array(
		'attributes' => array(
			'design' => array(
							'type'		=> 'string',
							'default'	=> 'design-1',
						),

			'slidestoshow' => array(
							'type'		=> 'number',
							'default'	=> 3,
						),
			'dots' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'arrows' => array(
							'type'		=> 'string',
							'default'	=> 'true',
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
			'fade' => array(
							'type'		=> 'string',
							'default'	=> 'false',
						),
			'loop' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'centermode' => array(
							'type'		=> 'string',
							'default'	=> 'false',
						),

			'limit' => array(
							'type'		=> 'number',
							'default'	=> 20,
						),
			'category' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'lazyload' => array(
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
		'render_callback' => 'wpostahs_timeline_slider',
	));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'wpostahs-block-js', 'timeline-and-history-slider', WPOSTAHS_DIR . '/languages' );
	}

}
add_action( 'init', 'wpostahs_register_guten_block' );

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @package Timeline and History Slider
 * @since 2.3
 */
function wpostahs_block_assets() {
}
add_action( 'enqueue_block_assets', 'wpostahs_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * 
 * @package Timeline and History Slider
 * @since 2.3
 */
function wpostahs_editor_assets() {

	// Block Editor CSS
	if( ! wp_style_is( 'wpos-free-guten-block-css', 'registered' ) ) {
		wp_register_style( 'wpos-free-guten-block-css', WPOSTAHS_URL.'assets/css/blocks.editor.build.css', array( 'wp-edit-blocks' ), WPOSTAHS_VERSION );
	}

	// Block Editor Script
	wp_enqueue_style( 'wpos-free-guten-block-css' );
	wp_enqueue_script( 'wpostahs-block-js' );

}
add_action( 'enqueue_block_editor_assets', 'wpostahs_editor_assets' );

/**
 * Adds an extra category to the block inserter
 *
 * @package Timeline and History Slider
 * @since 2.3
 */
function wpostahs_add_block_category( $categories ) {

	$guten_cats = wp_list_pluck( $categories, 'slug' );

	if( ! in_array( 'essp_guten_block', $guten_cats ) ) {
		$categories[] = array(
							'slug'	=> 'essp_guten_block',
							'title'	=> __('Essential Plugin Blocks', 'timeline-and-history-slider'),
							'icon'	=> null,
						);
	}

	return $categories;
}
add_filter( 'block_categories_all', 'wpostahs_add_block_category' );