<?php
/**
 * List layout template
 *
 * @var     $attributes - block attributes
 * @var     $options - layout options
 * @var     $posts - all available posts
 *
 * @package Canvas
 */

// when layout is not selected, used list.php
// but we don't need to print any html in this situation.
if ( ! isset( $attributes['layout'] ) || ! $attributes['layout'] ) {
	return;
}
?>

<div class="<?php echo esc_attr( $attributes['className'] ); ?>">
	<div class="cnvs-block-posts-inner">
		<?php

		while ( $posts->have_posts() ) {
			$posts->the_post();
			?>
			<article <?php post_class( 'masonry' === $attributes['layout'] ? 'cnvs-block-post-grid-item' : '' ); ?>>
				<div class="cnvs-block-post-single-outer">
					<?php if ( has_post_thumbnail() ) { ?>
						<div class="cnvs-block-post-single-inner">
							<div class="entry-thumbnail">
								<div class="cnvs-overlay cnvs-overlay-ratio cnvs-ratio-landscape">
									<div class="cnvs-overlay-background">
										<a href="<?php the_permalink(); ?>" class="cnvs-overlay-link">
											<?php the_post_thumbnail( $attributes['imageSize'] ); ?>
										</a>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<div class="cnvs-block-post-single-inner">
						<header>
							<?php
							// Post Meta.
							cnvs_block_post_meta( $attributes, 'category' );

							// Post Title.
							$tag = apply_filters( 'canvas_block_posts_title_tag', 'h3', $attributes, $options );
							the_title( '<' . $tag . ' class="cnvs-block-posts-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></' . $tag . '>' );

							// Post Meta.
							cnvs_block_post_meta( $attributes, cnvs_allowed_post_meta( true, 'category' ) );
							?>

							<?php
							if ( isset( $attributes['showExcerpt'] ) && $attributes['showExcerpt'] ) {
								?>
								<div class="cnvs-block-post-single-excerpt">
									<?php
									the_excerpt();
									?>
								</div>
								<?php
							}
							?>

							<?php
							if ( isset( $attributes['showViewPostButton'] ) && $attributes['showViewPostButton'] ) {
								?>
								<div class="cnvs-block-post-single-view-post-button">
									<?php cnvs_print_gutenberg_blocks_button( $attributes['buttonLabel'], get_permalink(), '', 'button', $attributes ); ?>
								</div>
								<?php
							}
							?>

							<?php
							if ( function_exists( 'powerkit_share_buttons_exists' ) && powerkit_share_buttons_exists( 'block-posts' ) ) {
								powerkit_share_buttons_location( 'block-posts' );
							}
							?>
						</header><!-- .entry-header -->
					</div><!-- .post-inner -->

				</div><!-- .post-outer -->
			</article>
			<?php
		}
		?>

	</div>

	<?php
	if ( isset( $attributes['showPagination'] ) && $attributes['showPagination'] ) {
		$total_pages = $posts->max_num_pages;

		if ( $total_pages > 1 ) {
			$current_page = max( 1, get_query_var( 'paged' ) );

			$base_url = cnvs_get_block_posts_page_url( $attributes );

			if ( $base_url ) {
				echo '<nav class="navigation pagination" role="navigation">';
					echo '<h2 class="screen-reader-text">' . esc_html__( 'Posts navigation', 'canvas' ) . '</h2>';
					echo '<div class="nav-links">';
						echo cnvs_paginate_links(
							array(
								'base'             => $base_url,
								'format'           => '%#%',
								'current'          => $current_page,
								'total'            => $total_pages,
								'merge_query_vars' => false,
							)
						); // XSS.
					echo '</div>';
				echo '</nav>';
			} else {
				cnvs_alert_warning( esc_html__( 'Please select a page for your blog posts in Settings &rarr; Reading to display pagination.', 'canvas' ) );
			}
		}
	}
	?>
</div>
