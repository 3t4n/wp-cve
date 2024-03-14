<?php
/**
 * Flex Posts List Block
 *
 * @package Flex Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register block
 */
function flex_posts_register_block() {
	wp_register_script(
		'flex-posts',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-element', 'wp-i18n' ),
		FLEX_POSTS_VERSION,
		true
	);

	$categories[] = array(
		'label' => __( 'All Categories', 'flex-posts' ),
		'value' => '',
	);

	$cats = get_categories();

	foreach ( $cats as $cat ) {
		$categories[] = array(
			'label' => $cat->name,
			'value' => $cat->term_id,
		);
	}

	wp_localize_script(
		'flex-posts',
		'flex_posts',
		array(
			'categories'  => $categories,
			'post_types'  => flex_posts_get_post_types(),
			'layouts'     => flex_posts_get_layouts(),
			'order_by'    => flex_posts_get_order_by(),
			'image_sizes' => flex_posts_get_image_sizes(),
			'taxonomies'  => flex_posts_get_taxonomies(),
			'title_el'    => flex_posts_get_title_elements_options(),
		)
	);

	register_block_type(
		'flex-posts/list',
		array(
			'editor_script'   => 'flex-posts',
			'editor_style'    => 'flex-posts',
			'style'           => 'flex-posts',
			'render_callback' => 'flex_posts_render_block',
			'attributes'      => apply_filters(
				'flex_posts_attributes',
				array(
					'title'           => array(
						'type'    => 'string',
						'default' => '',
					),
					'title_url'       => array(
						'type'    => 'string',
						'default' => '',
					),
					'layout'          => array(
						'type'    => 'number',
						'default' => 1,
					),
					'post_type'       => array(
						'type'    => 'string',
						'default' => 'post',
					),
					'cat'             => array(
						'type'    => 'string',
						'default' => '',
					),
					'tag'             => array(
						'type'    => 'string',
						'default' => '',
					),
					'order_by'        => array(
						'type'    => 'string',
						'default' => 'newest',
					),
					'number'          => array(
						'type'    => 'number',
						'default' => 4,
					),
					'skip'            => array(
						'type'    => 'number',
						'default' => 0,
					),
					'exclude_current' => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'show_image'      => array(
						'type'    => 'string',
						'default' => 'all',
					),
					'image_size'      => array(
						'type'    => 'string',
						'default' => '',
					),
					'image_size2'     => array(
						'type'    => 'string',
						'default' => '',
					),
					'show_title'      => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'show_categories' => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'show_author'     => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'show_date'       => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'show_comments'   => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'show_excerpt'    => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'excerpt_length'  => array(
						'type'    => 'number',
						'default' => 15,
					),
					'show_readmore'   => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'readmore_text'   => array(
						'type'    => 'string',
						'default' => '',
					),
					'pagination'      => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'align'           => array(
						'type'    => 'string',
						'default' => '',
					),
					'block_title_el'  => array(
						'type'    => 'string',
						'default' => '',
					),
					'post_title_el'   => array(
						'type'    => 'string',
						'default' => '',
					),
					'className'       => array(
						'type'    => 'string',
						'default' => '',
					),
				)
			),
		)
	);
}
add_action( 'init', 'flex_posts_register_block' );

/**
 * Render block
 *
 * @param  array $attributes Attributes.
 * @return string
 */
function flex_posts_render_block( $attributes ) {
	return flex_posts_render( $attributes );
}
