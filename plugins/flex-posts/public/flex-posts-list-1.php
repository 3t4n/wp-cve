<?php
/**
 * Flex posts widget template: List 1
 *
 * @package Flex Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="fp-row fp-list-1 fp-flex">

	<?php while ( $query->have_posts() ) : ?>

		<?php $query->the_post(); ?>

		<div class="fp-col fp-post">
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

			<?php if ( ! empty( $instance['show_excerpt'] ) ) : ?>
				<div class="fp-excerpt"><?php flex_posts_excerpt( $excerpt_length ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $instance['show_readmore'] ) ) : ?>
				<div class="fp-readmore">
					<a href="<?php the_permalink(); ?>" class="fp-readmore-link"><?php echo esc_html( $readmore_text ); ?></a>
				</div>
			<?php endif; ?>
		</div>

	<?php endwhile; ?>

	<div class="fp-col"></div>
	<div class="fp-col"></div>

</div>
