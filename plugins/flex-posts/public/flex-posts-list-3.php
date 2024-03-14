<?php
/**
 * Flex posts widget template: List 3
 *
 * @package Flex Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="fp-row fp-list-3 fp-flex">

	<?php while ( $query->have_posts() ) : ?>

		<?php $query->the_post(); ?>

		<?php if ( 0 === $query->current_post ) : ?>

			<div class="fp-col fp-post fp-main">
				<?php flex_posts_thumbnail( $medium_size, $instance, $query->current_post ); ?>

				<div class="fp-body">
					<?php if ( ! empty( $instance['show_categories'] ) ) : ?>
						<?php flex_posts_categories_meta(); ?>
					<?php endif; ?>

					<?php flex_posts_title( $instance ); ?>

					<div class="fp-meta">
						<?php flex_posts_meta( $instance ); ?>
					</div>

					<?php if ( ! empty( $instance['show_excerpt'] ) ) : ?>
						<div class="fp-excerpt"><?php flex_posts_excerpt( $excerpt_length ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $instance['show_readmore'] ) ) : ?>
						<div class="fp-readmore">
							<a href="<?php the_permalink(); ?>" class="fp-readmore-link"><?php echo esc_html( $readmore_text ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>

		<?php else : ?>

			<?php if ( 1 === $query->current_post ) : ?>

				<div class="fp-col fp-extra">

			<?php endif; ?>

			<div class="fp-post">
				<div class="fp-flex">
					<?php flex_posts_thumbnail( $thumbnail_size, $instance, $query->current_post ); ?>

					<div class="fp-body">
						<?php if ( ! empty( $instance['show_categories'] ) ) : ?>
							<?php flex_posts_categories_meta(); ?>
						<?php endif; ?>

						<?php flex_posts_title( $instance ); ?>

						<div class="fp-meta">
							<?php flex_posts_meta( $instance ); ?>
						</div>
					</div>
				</div>
			</div>

		<?php endif; ?>

	<?php endwhile; ?>

	<?php if ( 1 < $query->post_count ) : ?>

		</div>

	<?php endif; ?>

</div>
