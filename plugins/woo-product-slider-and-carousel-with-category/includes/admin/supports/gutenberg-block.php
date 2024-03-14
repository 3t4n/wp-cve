<?php
/**
 * Blocks Initializer
 * 
 * @package Product Slider and Carousel with Category for WooCommerce
 * @since 1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wcpscwc_register_guten_block() {

	wp_register_script( 'wcpscwc-block-js', WCPSCWC_URL.'assets/js/blocks.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-components' ), WCPSCWC_VERSION, true );
	wp_localize_script( 'wcpscwc-block-js', 'Wcpscwc_Block', array(
																	'pro_demo_link' 	=> 'https://demo.wponlinesupport.com/prodemo/woo-product-slider-and-carousel-with-category-pro/',
																	'free_demo_link'	=> 'https://demo.wponlinesupport.com/product-slider-and-carousel-demo/',
																	'pro_link'			=> WCPSCWC_PLUGIN_LINK_UNLOCK,
																));

	// Register block, and explicitly define the attributes for slider
	register_block_type( 'wcpscwc/wcpscwc-pdt-slider', array(
		'attributes' => array(
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'design' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'type' => array(
							'type'		=> 'string',
							'default'	=> 'products',
						),
			'slide_to_show' => array(
							'type'		=> 'number',
							'default'	=> 3,
						),
			'slide_to_scroll' => array(
							'type'		=> 'number',
							'default'	=> 3,
						),
			'arrows' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'dots' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'autoplay' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'autoplay_speed' => array(
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
			'slider_cls' => array(
							'type'		=> 'string',
							'default'	=> 'products',
						),
			'tax' => array(
							'type'		=> 'string',
							'default'	=> 'product_cat',
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
			'cats' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
		),
		'render_callback' => 'wcpscwc_product_slider',
	));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'wcpscwc-block-js', 'woo-product-slider-and-carousel-with-category', WCPSCWC_DIR . '/languages' );
	}
}
add_action( 'init', 'wcpscwc_register_guten_block' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * 
 * @since 1.1
 */
function wcpscwc_editor_assets() {	

	// Block Editor CSS
	if( ! wp_style_is( 'wpos-guten-block-css', 'registered' ) ) {
		wp_register_style( 'wpos-guten-block-css', WCPSCWC_URL.'assets/css/blocks.editor.build.css', array( 'wp-edit-blocks' ), WCPSCWC_VERSION );
	}

	// Block Editor Script
	wp_enqueue_style( 'wpos-guten-block-css' );
	wp_enqueue_script( 'wcpscwc-block-js' );
}
add_action( 'enqueue_block_editor_assets', 'wcpscwc_editor_assets' );

/**
 * Adds an extra category to the block inserter
 *
 * @since 1.1
 */
function wcpscwc_add_block_category( $categories ) {

	$guten_cats = wp_list_pluck( $categories, 'slug' );

	if( ! in_array( 'wpos_guten_block', $guten_cats ) ) {
		$categories[] = array(
							'slug'	=> 'wpos_guten_block',
							'title'	=> __('WPOS Blocks', 'woo-product-slider-and-carousel-with-category'),
							'icon'	=> null,
						);
	}

	return $categories;
}
add_filter( 'block_categories_all', 'wcpscwc_add_block_category' );