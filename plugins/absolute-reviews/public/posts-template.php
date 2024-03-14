<?php
/**
 * Posts Template
 *
 * @package    ABR
 * @subpackage ABR/public
 */

/**
 * Default Template
 *
 * @param array $posts    Array of posts.
 * @param array $params   Array of params.
 * @param array $instance Instance.
 */
function abr_reviews_posts_template( $posts, $params, $instance ) {

	$review_id = get_the_ID();

	$value = abr_get_review( false, $review_id );

	// Get indicators.
	$indicators = abr_list_indicators();

	// Get type.
	$type = abr_review_get_type( $review_id, 'none' );

	// Set value index.
	$val_index = abr_review_get_val_index( $type, $value );

	// Set mode.
	$mode = 'simple';

	// Set variation.
	$variation = 'default';

	if ( 'reviews-2' === $params['template'] || 'reviews-4' === $params['template'] ) {
		$mode = 'extended';
	}

	// Badges.
	$badge_block = sprintf( 'abr-badge abr-badge-primary abr-review-badge-%s', $val_index );
	$badge_text  = sprintf( 'abr-badge-text abr-badge-text-primary abr-review-badge-text-%s', $val_index );

	// Set variation by template.
	if ( in_array( $params['template'], array( 'reviews-6', 'reviews-7', 'reviews-8' ), true ) ) {
		$variation = 'overlay';
	}

	// Post Meta.
	if ( 'widget' === $params['output'] ) {
		$params['post_meta_list']    = $params['post_meta'];
		$params['post_meta_compact'] = $params['post_meta_compact'];
		$params['thumbnail']         = $params['thumbnail'];

		if ( in_array( $params['template'], array( 'reviews-3', 'reviews-4', 'reviews-5' ), true ) ) {
			if ( 1 === $params['counter'] ) {
				$params['post_meta_list']    = $params['post_meta_large'];
				$params['post_meta_compact'] = $params['post_meta_large_compact'];
				$params['thumbnail']         = $params['thumbnail_large'];
			} else {
				$params['post_meta_list']    = $params['post_meta_small'];
				$params['post_meta_compact'] = $params['post_meta_small_compact'];
				$params['thumbnail']         = $params['thumbnail_small'];
			}
		}
	}

	// Class Type.
	$class = sprintf( 'abr-type-%s', $type );

	// Class Variation.
	$class .= sprintf( ' abr-variation-%s', $variation );

	// Classes.
	$class_caption = $badge_block;
	$class_number  = null;

	if ( 'reviews-1' === $params['template'] || 'reviews-3' === $params['template'] ) {
		$class_caption = $badge_text;
		$class_number  = $badge_block;
	}

	if ( 'percentage' === $type ) {
		$class_caption = $badge_block;
		$class_number  = null;
	}

	$meta_settings = array(
		'abr-params' => $params,
	);
	?>
	<article <?php post_class( $class ); ?>>
		<div class="abr-post-outer">

			<?php if ( has_post_thumbnail() ) { ?>
				<div class="abr-post-inner abr-post-thumbnail">
					<a href="<?php the_permalink(); ?>" class="post-thumbnail">
						<?php the_post_thumbnail( $params['thumbnail'] ); ?>
					</a>
				</div>
			<?php } ?>

			<div class="abr-post-inner abr-post-data">

				<?php if ( 'overlay' === $variation ) { ?>
					<a class="abr-post-link" href="<?php the_permalink(); ?>"></a>
				<?php } ?>

				<div class="abr-post-headline">
					<?php
						abr_get_post_meta( 'category', (bool) $params['post_meta_compact'], true, $params['post_meta_list'], $meta_settings );
					?>

					<?php
					$tag = apply_filters( 'abr_reviews_posts_title_tag', 'h5', $params, $instance );
					?>

					<<?php echo esc_html( $tag ); ?> class="entry-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</<?php echo esc_html( $tag ); ?>>

					<?php
						abr_get_post_meta( abr_allowed_post_meta( true, 'category' ), (bool) $params['post_meta_compact'], true, $params['post_meta_list'], $meta_settings );
					?>
				</div>

				<?php if ( $value ) { ?>
					<?php if ( 'simple' === $mode ) { ?>
						<div class="abr-review-meta">
							<div class="abr-review-number <?php echo esc_attr( $class_number ); ?>">
								<?php
									echo abr_get_review( true, $review_id ); // XSS.
								?>
							</div>

							<?php if ( $indicators && $indicators[ $val_index ]['name'] ) { ?>
								<div class="abr-review-caption <?php echo esc_attr( $class_caption ); ?>">
									<?php echo esc_html( $indicators[ $val_index ]['name'] ); ?>
								</div>
							<?php } ?>
						</div>
					<?php } else { ?>
						<div class="abr-review-meta">
							<div class="abr-review-indicator abr-review-<?php echo esc_attr( $type ); ?>">
								<?php if ( 'star' === $type ) : ?>
									<div class="abr-review-stars">
										<?php
										abr_review_star_rating(
											array(
												'rating' => $value,
												'type'   => 'rating',
												'number' => 0,
											)
										);
										?>
									</div>
								<?php elseif ( 'point-5' === $type || 'point-10' === $type ) : ?>
									<div class="abr-review-line">
										<?php
										$max_slice = 'point-5' === $type ? 5 : 10;

										for ( $index = 1; $index <= $max_slice; $index++ ) {
											if ( $index <= $value ) {
												$class = 'abr-review-slice-active';
											} else {
												$class = 'abr-review-slice-no-active';
											}
											?>
												<span class="abr-review-slice <?php echo esc_attr( $class ); ?>"></span>
											<?php
										}
										?>
									</div>
								<?php elseif ( 'percentage' === $type ) : ?>
									<div class="abr-review-progress">
										<div class="abr-review-progressbar abr-review-progressbar-<?php echo esc_attr( $val_index ); ?>" style="width:<?php echo esc_attr( $value > 90 ? $value : 100 ); ?>%"></div>
									</div>
								<?php endif; ?>
							</div>

							<div class="abr-review-number <?php echo esc_attr( $class_number ); ?>">
								<?php
									echo abr_get_review( true, $review_id ); // XSS.
								?>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</article>
	<?php
}
