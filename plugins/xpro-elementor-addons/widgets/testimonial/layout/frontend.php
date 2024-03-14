<?php
$title_tag   = ( $settings['name_link']['url'] ) ? 'a' : 'h3';
$title_attr  = $settings['name_link']['is_external'] ? ' target="_blank"' : '';
$title_attr .= $settings['name_link']['nofollow'] ? ' rel="nofollow"' : '';
$title_attr .= $settings['name_link']['url'] ? ' href="' . $settings['name_link']['url'] . '"' : '';
?>

<?php if ( '4' === $settings['layout'] || '5' === $settings['layout'] || '10' === $settings['layout'] ) { ?>
	<?php if ( $settings['image']['id'] || $settings['image']['url'] ) : ?>
		<div class="xpro-testimonial-image">
			<?php
			$image_markup = ( ! empty( $settings['image']['id'] ) ) ? wp_get_attachment_image( $settings['image']['id'], $settings['thumbnail_size'] ) : '';
			echo ! empty( $image_markup ) ? $image_markup : '<img src="' . esc_url( $settings['image']['url'] ) . '">';
			?>
		</div>
	<?php endif; ?>
<?php } ?>

<?php echo ( '4' === $settings['layout'] || '5' === $settings['layout'] || '6' === $settings['layout'] ) ? '<div class="xpro-testimonial-inner-wrapper">' : ''; ?>
<div class="xpro-testimonial-content">

	<?php if ( 'yes' === $settings['show_quote'] && $settings['quote_icon']['value'] && '6' !== $settings['layout'] && '9' !== $settings['layout'] && '10' !== $settings['layout'] ) : ?>
		<span class="xpro-testimonial-quote">
			<?php \Elementor\Icons_Manager::render_icon( $settings['quote_icon'], array( 'aria-hidden' => 'true' ) ); ?>
		</span>
	<?php endif; ?>

	<?php if ( 'none' !== $settings['ratting_style'] && ( '2' === $settings['layout'] || '6' === $settings['layout'] || '10' === $settings['layout'] ) ) { ?>
		<div class="xpro-testimonial-rating xpro-rating-layout-<?php echo esc_attr( $settings['ratting_style'] ); ?>">
			<?php
			if ( 'num' === $settings['ratting_style'] ) {
				echo esc_html( $settings['ratting']['size'] ) . '<i class="fas fa-star" aria-hidden="true"></i>';
			} else {
				for ( $x = 1; $x <= 5; $x ++ ) {
					if ( $x <= $settings['ratting']['size'] ) {
						echo '<i class="fas fa-star xpro-rating-filled" aria-hidden="true"></i>';
					} else {
						echo '<i class="fas fa-star" aria-hidden="true"></i>';
					}
				}
			}
			?>

		</div>
	<?php } ?>

	<?php if ( $settings['description'] ) : ?>
		<div class="xpro-testimonial-description">
			<?php xpro_elementor_kses( $settings['description'] ); ?>
		</div>
	<?php endif; ?>

	<?php if ( 'none' !== $settings['ratting_style'] && ( '1' === $settings['layout'] || '3' === $settings['layout'] || '7' === $settings['layout'] || '8' === $settings['layout'] || '9' === $settings['layout'] ) ) { ?>
		<div class="xpro-testimonial-rating xpro-rating-layout-<?php echo esc_attr( $settings['ratting_style'] ); ?>">
			<?php
			if ( 'num' === $settings['ratting_style'] ) {
				echo esc_html( $settings['ratting']['size'] ) . '<i class="fas fa-star" aria-hidden="true"></i>';
			} else {
				for ( $x = 1; $x <= 5; $x ++ ) {
					if ( $x <= $settings['ratting']['size'] ) {
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
	<?php if ( '4' !== $settings['layout'] && '5' !== $settings['layout'] && '10' !== $settings['layout'] ) { ?>
		<?php if ( $settings['image']['id'] || $settings['image']['url'] ) : ?>
			<div class="xpro-testimonial-image">
				<?php
				$image_markup = ( ! empty( $settings['image']['id'] ) ) ? wp_get_attachment_image( $settings['image']['id'], $settings['thumbnail_size'] ) : '';
				echo ! empty( $image_markup ) ? $image_markup : '<img src="' . esc_url( $settings['image']['url'] ) . '">';
				?>
			</div>
		<?php endif; ?>
	<?php } ?>
	<?php if ( $settings['name'] || $settings['designation'] ) { ?>
	<div class="xpro-testimonial-author-bio">
		<?php if ( $settings['name'] ) : ?>
		<<?php echo esc_attr( $title_tag ); ?><?php xpro_elementor_kses( $title_attr ); ?>
		class="xpro-testimonial-title"><?php echo esc_attr( $settings['name'] ); ?></<?php echo esc_attr( $title_tag ); ?>>
<?php endif; ?>
		<?php if ( $settings['designation'] ) : ?>
		<h4 class="xpro-testimonial-designation"><?php echo esc_attr( $settings['designation'] ); ?></h4>
	<?php endif; ?>
</div>
<?php } ?>
<?php if ( 'none' !== $settings['ratting_style'] && ('4' === $settings['layout'] || '5' === $settings['layout'] ) ) { ?>
	<div class="xpro-testimonial-rating xpro-rating-layout-<?php echo esc_attr( $settings['ratting_style'] ); ?>">
		<?php
		if ( 'num' === $settings['ratting_style'] ) {
			echo esc_html( $settings['ratting']['size'] ) . '<i class="fas fa-star" aria-hidden="true"></i>';
		} else {
			for ( $x = 1; $x <= 5; $x ++ ) {
				if ( $x <= $settings['ratting']['size'] ) {
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
<?php echo ( '4' === $settings['layout'] || '5' === $settings['layout'] || '6' === $settings['layout'] ) ? '</div>' : ''; ?>
