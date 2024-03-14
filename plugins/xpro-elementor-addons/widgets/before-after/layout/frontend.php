<!--Compare Wrapper-->
<div class="xpro-compare-wrapper xpro-compare-handle-show-<?php echo esc_attr( $settings['display_handle'] ); ?> xpro-compare-label-show-<?php echo esc_attr( $settings['display_label'] ); ?>">
	<!-- Compare Item -->
	<div class="xpro-compare-item">
		<?php
		$before_image_markup = ( ! empty( $settings['before_image']['id'] ) ) ? wp_get_attachment_image( $settings['before_image']['id'], $settings['before_thumbnail_size'] ) : '';
		echo ! empty( $before_image_markup ) ? $before_image_markup : '<img src="' . esc_url( $settings['before_image']['url'] ) . '">';

		$after_image_markup = ( ! empty( $settings['after_image']['id'] ) ) ? wp_get_attachment_image( $settings['after_image']['id'], $settings['after_thumbnail_size'] ) : '';
		echo ! empty( $after_image_markup ) ? $after_image_markup : '<img src="' . esc_url( $settings['after_image']['url'] ) . '">';
		?>
		<!--Label-->
		<div class="xpro-compare-overlay">
			<?php if ( $settings['before_label'] ) : ?>
				<div class="xpro-compare-before-label"><?php echo esc_html( $settings['before_label'] ); ?></div>
			<?php endif; ?>
			<?php if ( $settings['after_label'] ) : ?>
				<div class="xpro-compare-after-label"><?php echo esc_html( $settings['after_label'] ); ?></div>
			<?php endif; ?>
		</div>

	</div>
</div>
