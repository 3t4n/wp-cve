<?php
/**
 * Server-side rendering of the Magazine List Block
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

		$posts_markup .= ThemeZee_Magazine_Blocks_Template::get_list_post( $attributes, $attributes['imageSize'] );

	endwhile;

endif;

// Reset Postdata.
wp_reset_postdata();

// Set List class.
$list_class = sanitize_key( 'tz-magazine-list-' . $attributes['layout'] );

// Define Block Content.
$block_content = sprintf( '<div class="tz-magazine-list %1$s">%2$s</div>', $list_class, $posts_markup );

// Get Block Classes.
$block_classes = sprintf( 'wp-block-themezee-magazine-blocks-list tz-magazine-block %s', $attributes['className'] ?? '' );

// Wrap Block Content.
$block = sprintf(
	'<div class="%1$s">%2$s</div>',
	esc_attr( $block_classes ),
	$block_content
);

echo wp_kses_post( $block );
