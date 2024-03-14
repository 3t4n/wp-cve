<?php
/**
 * Blocks Initializer
 * 
 * @package WP Slick Slider and Image Carousel
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wpsisac_register_guten_block() {

	// Block Editor Script
	wp_register_script( 'wpsisac-free-block-js', WPSISAC_URL.'assets/js/blocks.build.js', array( 'wp-block-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ), WPSISAC_VERSION, true );

	wp_localize_script( 'wpsisac-free-block-js', 'Wpsisac_free_Block', array(
																'pro_demo_link'		=> 'https://demo.essentialplugin.com/prodemo/pro-wp-slick-slider-and-carousel-demo/',
																'free_demo_link'	=> 'https://demo.essentialplugin.com/slick-slider-demo/',
																'pro_link' 			=> WPSISAC_PLUGIN_LINK_UNLOCK,
															));

	// Register block and explicit attributes for slick slider
	register_block_type( 'wpsisac/slick-slider', array(
		'attributes' => array(
			'design' => array(
							'type'		=> 'string',
							'default'	=> 'design-1',
						),
			'show_content' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'image_size' => array(
							'type'		=> 'string',
							'default'	=> 'full',
						),
			'image_fit' => array(
							'type'		=> 'string',
							'default'	=> 'false',
						),
			'sliderheight' => array(
							'type'		=> 'number',
							'default'	=> '',
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
			'loop' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'fade' => array(
							'type'		=> 'string',
							'default'	=> 'false',
						),
			'hover_pause' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'lazyload' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> -1,
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
		'render_callback' => 'wpsisac_get_slick_slider',
	));

	// Register block and explicit attributes for slick carousel
	register_block_type( 'wpsisac/slick-carousel-slider', array(
		'attributes' => array(
			'design' => array(
							'type'		=> 'string',
							'default'	=> 'design-1',
						),
			'image_size' => array(
							'type'		=> 'string',
							'default'	=> 'full',
						),
			'image_fit' => array(
							'type'		=> 'string',
							'default'	=> 'false',
						),
			'sliderheight' => array(
							'type'		=> 'number',
							'default'	=> '',
						),
			'slidestoshow' => array(
							'type'		=> 'number',
							'default'	=> 3,
						),
			'slidestoscroll' => array(
							'type'		=> 'number',
							'default'	=> 1,
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
			'loop' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'centermode' => array(
							'type'		=> 'string',
							'default'	=> 'false',
						),
			'variablewidth' => array(
							'type'		=> 'string',
							'default'	=> 'false',
						),
			'hover_pause' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'lazyload' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> -1,
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
		'render_callback' => 'wpsisac_get_carousel_slider',
	));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'wpsisac-free-block-js', 'wp-slick-slider-and-image-carousel', WPSISAC_DIR . '/languages' );
	}
}
add_action( 'init', 'wpsisac_register_guten_block' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * 
 * @since 2.0
 */
function wpsisac_editor_assets() {

	// Block Editor CSS
	if( ! wp_style_is( 'wpos-free-guten-block-css', 'registered' ) ) {
		wp_register_style( 'wpos-free-guten-block-css', WPSISAC_URL.'assets/css/blocks.editor.build.css', array( 'wp-edit-blocks' ), WPSISAC_VERSION );
	}

	// Block Editor Script - Style
	wp_enqueue_style( 'wpos-free-guten-block-css' );
	wp_enqueue_script( 'wpsisac-free-block-js' );
}
add_action( 'enqueue_block_editor_assets', 'wpsisac_editor_assets' );

/**
 * Adds an extra category to the block inserter
 *
 * @since 2.0
 */
function wpsisac_add_block_category( $categories ) {

	$guten_cats = wp_list_pluck( $categories, 'slug' );

	if( ! in_array( 'essp_guten_block', $guten_cats ) ) {
		$categories[] = array(
							'slug'	=> 'essp_guten_block',
							'title'	=> esc_html__('Essential Plugin Blocks', 'wp-slick-slider-and-image-carousel'),
							'icon'	=> null,
						);
	}

	return $categories;
}
add_filter( 'block_categories_all', 'wpsisac_add_block_category' );