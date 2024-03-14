<?php
/**
 * Server-side rendering of the Magazine Grid Block
 *
 * @package ThemeZee Magazine Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Fetch posts from cache or database.
$posts_query = ThemeZee_Magazine_Blocks_Cache::query_posts( $attributes );

// Set up markup variable.
$posts_markup = '';

// Check if there are posts.
if ( $posts_query->have_posts() ) :

	// Get Posts Markup.
	while ( $posts_query->have_posts() ) :
		$posts_query->the_post();

		$posts_markup .= ThemeZee_Magazine_Blocks_Template::get_grid_post( $attributes, $attributes['imageSize'] );

	endwhile;

endif;

// Reset Postdata.
wp_reset_postdata();

// Set Columns class.
$columns_class = sanitize_key( 'tz-magazine-grid-columns-' . $attributes['columns'] );

// Define Block Content.
$block_content = sprintf( '<div class="tz-magazine-grid-columns %1$s">%2$s</div>', $columns_class, $posts_markup );

// Get Block Classes.
$block_classes = sprintf( 'wp-block-themezee-magazine-blocks-grid tz-magazine-block %s', $attributes['className'] ?? '' );

// Wrap Block Content.
$block = sprintf(
	'<div class="%1$s">%2$s</div>',
	esc_attr( $block_classes ),
	$block_content
);

echo wp_kses_post( $block );
