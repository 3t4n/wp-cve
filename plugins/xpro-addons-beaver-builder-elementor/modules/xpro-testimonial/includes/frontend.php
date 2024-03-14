<div class="xpro-testimonial-wrapper xpro-testimonial-align-<?php echo esc_attr( $settings->alignment ); ?>  xpro-testimonial-align-tablet-<?php echo esc_attr( $settings->alignment_medium ); ?> xpro-testimonial-align-mobile-<?php echo esc_attr( $settings->alignment_responsive ); ?> xpro-testimonial-layout-<?php echo esc_attr( $settings->layout ); ?>">

	<?php
	$items_count = count( $settings->items );
	for ( $i = 0; $i < $items_count; $i++ ) {

		$item = $settings->items[ $i ];
		?>

		<div class="xpro-testimonial-item">
			<?php

			if ( '' !== $item->author_link ) {
				$title_tag   = 'a';
				$title_attr  = $item->author_link_target ? ' target=_blank' : '';
				$title_attr .= $item->author_link_nofollow ? ' rel=nofollow' : '';
				$title_attr .= $item->author_link ? ' href=' . $item->author_link . '' : '';
			} else {
                $title_attr = '';
				$title_tag = 'h2';
			}

			?>

			<?php if ( ( '4' === $settings->layout ) || ( '5' === $settings->layout ) || '10' === $settings->layout ) { ?>
				<?php if ( $item->author_image ) : ?>
					<div class="xpro-testimonial-image">
						<?php echo wp_get_attachment_image( $item->author_image, $item->image_size ); ?>
					</div>
				<?php endif; ?>
			<?php } ?>

			<?php echo ( '4' === $settings->layout || '5' === $settings->layout || '6' === $settings->layout ) ? '<div class="xpro-testimonial-inner-wrapper">' : ''; ?>
			<div class="xpro-testimonial-content">

				<?php if ( '6' !== $settings->layout && '9' !== $settings->layout && '10' !== $settings->layout ) : ?>
					<span class="xpro-testimonial-quote">
						<i class="fas fa-quote-left" aria-hidden="true"></i>
					</span>
				<?php endif; ?>

				<?php if ( '2' === $settings->layout || '6' === $settings->layout || '10' === $settings->layout ) { ?>
					<div class="xpro-testimonial-rating xpro-rating-layout-<?php echo esc_attr( $settings->rating_style ); ?>">
						<?php
						if ( 'num' === $settings->rating_style ) {
							echo esc_attr( $item->rating ) . '<i class="fas fa-star" aria-hidden="true"></i>';
						} else {
							for ( $x = 1; $x <= 5; $x++ ) {
								if ( $x <= $item->rating ) {
									echo '<i class="fas fa-star xpro-rating-filled" aria-hidden="true"></i>';
								} else {
									echo '<i class="fas fa-star" aria-hidden="true"></i>';
								}
							}
						}
						?>

					</div>
				<?php } ?>

				<?php if ( $item->description ) : ?>
					<div class="xpro-testimonial-description">
						<?php echo esc_attr( $item->description ); ?>
					</div>
				<?php endif; ?>

				<?php if ( '1' === $settings->layout || '3' === $settings->layout || '7' === $settings->layout || '8' === $settings->layout || '9' === $settings->layout ) { ?>
					<div class="xpro-testimonial-rating xpro-rating-layout-<?php echo esc_attr( $settings->rating_style ); ?>">
						<?php
						if ( 'num' === $settings->rating_style ) {
							echo esc_attr( $item->rating ) . '<i class="fas fa-star" aria-hidden="true"></i>';
						} else {
							for ( $x = 1; $x <= 5; $x++ ) {
								if ( $x <= $item->rating ) {
									echo '<i class="fas fa-star xpro-rating-filled" aria-hidden="true"></i>';
								} else {
									echo '<i class="fas fa-star" aria-hidden="true"></i>';
								}
							}
						}
						?>

					</div>
				<?php } ?>
			</div>
			<div class="xpro-testimonial-author">
				<?php if ( '4' !== $settings->layout && '5' !== $settings->layout && '10' !== $settings->layout ) { ?>
					<?php if ( $item->author_image ) : ?>
						<div class="xpro-testimonial-image">
							<?php echo wp_get_attachment_image( $item->author_image, $item->image_size ); ?>
						</div>
					<?php endif; ?>
				<?php } ?>
				<?php if ( $item->author_name || $item->designation ) { ?>
				<div class="xpro-testimonial-author-bio">
					<?php if ( $item->author_name ) : ?>
					<<?php echo esc_attr( $title_tag ); ?><?php echo esc_attr( $title_attr ); ?> class="xpro-testimonial-title"><?php echo esc_attr( $item->author_name ); ?></<?php echo esc_attr( $title_tag ); ?>>
			<?php endif; ?>
					<?php if ( $item->designation ) : ?>
					<h4 class="xpro-testimonial-designation"><?php echo esc_attr( $item->designation ); ?></h4>
				<?php endif; ?>
			</div>
		<?php } ?>
			<?php if ( '4' === $settings->layout || '5' === $settings->layout ) { ?>
				<div class="xpro-testimonial-rating xpro-rating-layout-<?php echo esc_attr( $settings->rating_style ); ?>">
					<?php
					if ( 'num' === $settings->rating_style ) {
						echo esc_attr( $item->rating ) . '<i class="fas fa-star" aria-hidden="true"></i>';
					} else {
						for ( $x = 1; $x <= 5; $x++ ) {
							if ( $x <= $item->rating ) {
								echo '<i class="fas fa-star xpro-rating-filled" aria-hidden="true"></i>';
							} else {
								echo '<i class="fas fa-star" aria-hidden="true"></i>';
							}
						}
					}
					?>

				</div>
			<?php } ?>
		</div>
		<?php echo ( '4' === $settings->layout || '5' === $settings->layout || '6' === $settings->layout ) ? '</div>' : ''; ?>

		</div>

	<?php } ?>

</div>
