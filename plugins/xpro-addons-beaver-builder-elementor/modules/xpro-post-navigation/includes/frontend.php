<?php
$prev_post = get_adjacent_post( false, '', true );
$next_post = get_adjacent_post( false, '', false );
?>
<div class="xpro-post-navigation">
	<div class="xpro-post-navigation-prev xpro-post-navigation-link">
		<?php if ( ! empty( $prev_post ) ) : ?>
		<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
			<?php if ( 'none' !== $settings->show_arrow ) : ?>
				<span class="xpro-post-navigation-arrow-wrapper xpro-post-navigation-arrow-prev">
				<i class="<?php echo esc_attr( $settings->show_arrow ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>

			<span class="xpro-post-navigation-link-prev">

				<?php if ( 'yes' === $settings->show_label ) : ?>
					<span class="xpro-post-navigation-prev-label"><?php echo esc_attr( $settings->prev_label ); ?></span>
				<?php endif; ?>

				<?php if ( $settings->show_title ) : ?>
					<span class="xpro-post-navigation-prev-title">
					<?php echo esc_attr( $prev_post->post_title ); ?>
				</span>
				<?php endif; ?>
			</span>
		</a>
		<?php endif; ?>
	</div>


	<?php if ( 'yes' === $settings->show_separator ) : ?>
		<div class="xpro-post-navigation-separator-wrapper">
			<div class="xpro-post-navigation-separator"></div>
		</div>
	<?php endif; ?>

	<div class="xpro-post-navigation-next xpro-post-navigation-link">
		<?php if ( ! empty( $next_post ) ) : ?>
		<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
			<span class="xpro-post-navigation-link-next">

				<?php if ( 'yes' === $settings->show_label ) : ?>
					<span class="xpro-post-navigation-next-label"><?php echo esc_attr( $settings->next_label ); ?></span>
				<?php endif; ?>

				<?php if ( $settings->show_title ) : ?>
					<span class="xpro-post-navigation-next-title">
					<?php echo esc_attr( $next_post->post_title ); ?>
				</span>
				<?php endif; ?>

			</span>

			<?php if ( 'none' !== $settings->show_arrow ) : ?>
				<span class="xpro-post-navigation-arrow-wrapper xpro-post-navigation-arrow-next">
				<i class="<?php echo str_replace( 'left', 'right', $settings->show_arrow ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
		</a>
		<?php endif; ?>
	</div>


</div>
