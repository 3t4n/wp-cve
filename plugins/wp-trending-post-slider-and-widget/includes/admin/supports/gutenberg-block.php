<?php
/**
 * Blocks Initializer
 * 
 * @package Trending/Popular Post Slider and Widget
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wtpsw_register_guten_block() {

	// Block Editor Script
	wp_register_script( 'wtpsw-block-js', WTPSW_URL.'assets/js/blocks.build.js', array( 'wp-blocks', 'wp-block-editor', 'wp-i18n', 'wp-element', 'wp-components' ), WTPSW_VERSION, true );
	wp_localize_script( 'wtpsw-block-js', 'Wtpsw_Block', array(
															'pro_demo_link'		=> 'https://demo.essentialplugin.com/prodemo/pro-featured-and-trending-post/',
															'free_demo_link'	=> 'https://demo.essentialplugin.com/trending-post-demo/',
															'pro_link'			=> WTPSW_PLUGIN_LINK_UNLOCK,
														));

	// Register block and explicit attributes for trending slider
	register_block_type( 'wtpsw-free/trending-slider', array(
		'attributes' => array(
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'showdate' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'showauthor' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_comment_count' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'hide_empty_comment_count' => array(
							'type'		=> 'boolean',
							'default'	=> false,
						),
			'showcontent' => array(
							'type'		=> 'boolean',
							'default'	=> false,
						),
			'words_limit' => array(
							'type'		=> 'number',
							'default'	=> 40,
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
			'autoplayinterval' => array(
							'type'		=> 'number',
							'default'	=> 3000,
						),
			'speed' => array(
							'type'		=> 'number',
							'default'	=> 300,
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> 10,
						),
			'post_type' => array(
							'type'		=> 'string',
							'default'	=> 'post',
						),
			'view_by' => array(
							'type'		=> 'string',
							'default'	=> 'views',
						),
			'order' => array(
							'type'		=> 'string',
							'default'	=> 'desc',
						),
		),
		'render_callback' => 'wtpsw_trending_post_slider',
	));


	// Register block and explicit attributes for trending carousel
	register_block_type( 'wtpsw-free/trending-carousel', array(
		'attributes' => array(
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'showdate' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'showauthor' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_comment_count' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'hide_empty_comment_count' => array(
							'type'		=> 'boolean',
							'default'	=> false,
						),
			'showcontent' => array(
							'type'		=> 'boolean',
							'default'	=> false,
						),
			'words_limit' => array(
							'type'		=> 'number',
							'default'	=> 40,
						),
			'slides_to_show' => array(
							'type'		=> 'number',
							'default'	=> 3,
						),
			'slides_to_scroll' => array(
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
			'autoplayinterval' => array(
							'type'		=> 'number',
							'default'	=> 3000,
						),
			'speed' => array(
							'type'		=> 'number',
							'default'	=> 300,
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> 10,
						),
			'post_type' => array(
							'type'		=> 'string',
							'default'	=> 'post',
						),
			'view_by' => array(
							'type'		=> 'string',
							'default'	=> 'views',
						),
			'order' => array(
							'type'		=> 'string',
							'default'	=> 'DESC',
						),

		),
		'render_callback' => 'wtpsw_popular_post_carousel',
	));

	// Register block and explicit attributes for trending gridbox
	register_block_type( 'wtpsw-free/trending-gridbox', array(
		'attributes' => array(
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'showdate' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'showauthor' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_comment_count' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'hide_empty_comment_count' => array(
							'type'		=> 'boolean',
							'default'	=> false,
						),
			'showcontent' => array(
							'type'		=> 'boolean',
							'default'	=> false,
						),
			'words_limit' => array(
							'type'		=> 'number',
							'default'	=> 40,
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> 5,
						),
			'post_type' => array(
							'type'		=> 'string',
							'default'	=> 'post',
						),
			'view_by' => array(
							'type'		=> 'string',
							'default'	=> 'views',
						),
			'order' => array(
							'type'		=> 'string',
							'default'	=> 'DESC',
						),
		),
		'render_callback' => 'wtpsw_trending_post_gridbox',
	));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'wtpsw-block-js', 'wtpsw', WTPSW_DIR . '/languages' );
	}
}
add_action( 'init', 'wtpsw_register_guten_block' );

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
function wtpsw_editor_assets() {

	// Block Editor CSS
	if( ! wp_style_is( 'wpos-guten-block-css', 'registered' ) ) {
		wp_register_style( 'wpos-guten-block-css', WTPSW_URL.'assets/css/blocks.editor.build.css', array( 'wp-edit-blocks' ), WTPSW_VERSION );
	}

	// Block Editor Script
	wp_enqueue_style( 'wpos-guten-block-css' );
	wp_enqueue_script( 'wtpsw-block-js' );
}
add_action( 'enqueue_block_editor_assets', 'wtpsw_editor_assets' );

/**
 * Adds an extra category to the block inserter
 *
 * @since 1.0
 */
function wtpsw_add_block_category( $categories ) {

	$guten_cats = wp_list_pluck( $categories, 'slug' );

	if( ! in_array( 'wpos_guten_block', $guten_cats ) ) {
		$categories[] = array(
							'slug'		=> 'wpos_guten_block',
							'title'		=> esc_html__( 'Essential Plugin Blocks', 'wtpsw' ),
							'icon'		=> null,
						);
	}

	return $categories;
}
add_filter( 'block_categories_all', 'wtpsw_add_block_category' );