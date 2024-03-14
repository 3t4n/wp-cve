<?php
/**
 * Server-side rendering of the Magazine Column Block
 *
 * @package ThemeZee Magazine Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Fetch posts from cache or database.
$posts_query = ThemeZee_Magazine_Blocks_Cache::query_posts( $attributes );

// Set up markup variables.
$highlight_post = '';
$list_posts     = '';

// Check if there are posts.
if ( $posts_query->have_posts() ) :

	// Get Posts Markup.
	while ( $posts_query->have_posts() ) :
		$posts_query->the_post();

		// Display first post differently.
		if ( 0 === $posts_query->current_post ) :

			$highlight_post .= ThemeZee_Magazine_Blocks_Template::get_grid_post( $attributes, $attributes['imageSize'] );

		else :

			$list_posts .= ThemeZee_Magazine_Blocks_Template::get_list_post( $attributes, $attributes['thumbnailSize'], false );

		endif;

	endwhile;

endif;

// Reset Postdata.
wp_reset_postdata();

// Wrap Highlight Posts.
$highlight_post = sprintf( '<div class="tz-magazine-highlight-post">%s</div>', $highlight_post );

// Wrap Thumbnail Posts.
$list_posts = sprintf( '<div class="tz-magazine-thumbnail-list">%s</div>', $list_posts );

// Set Posts Markup.
$posts_markup = $highlight_post . $list_posts;

// Define Block Content.
$block_content = sprintf( '<div class="tz-magazine-column">%s</div>', $posts_markup );

// Get Block Classes.
$block_classes = sprintf( 'wp-block-themezee-magazine-blocks-column tz-magazine-block %s', $attributes['className'] ?? '' );

// Wrap Block Content.
$block = sprintf(
	'<div class="%1$s">%2$s</div>',
	esc_attr( $block_classes ),
	$block_content
);

echo wp_kses_post( $block );
