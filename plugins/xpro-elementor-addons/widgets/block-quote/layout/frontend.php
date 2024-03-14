<div class="xpro-block-quote-wrapper xpro-block-quote-<?php echo esc_attr( $settings['quote_position'] ); ?>">
	<div class="xpro-block-quote-inner">
		<?php if ( !empty($settings['quote_icon']['value']) && ( 'layout-3' !== $settings['quote_position'] && 'layout-6' !== $settings['quote_position'] ) ) : ?>
			<span class="xpro-block-quote-icon">
			<?php
			if ( $settings['quote_icon'] ) {
				\Elementor\Icons_Manager::render_icon( $settings['quote_icon'], array( 'aria-hidden' => 'true' ) );
			}
			?>
			</span>
		<?php endif; ?>

		<div class="xpro-block-quote-content">
			<?php if ( $settings['image'] || 'layout-4' === $settings['quote_position'] ) : ?>
				<span class="xpro-block-quote-content-img">
				<?php
				if ( $settings['image'] ) {
					echo wp_kses_post( \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
				}
				?>
			</span>
			<?php endif; ?>

			<div class="xpro-block-quote-content-wrap">

				<?php if ( $settings['quote_description'] ) : ?>
					<!-- Text -->
					<p class="xpro-block-quote-text"><?php xpro_elementor_kses( $settings['quote_description'] ); ?></p>
				<?php endif; ?>

				<div class="xpro-block-quote-desc">
					<?php if ( $settings['quote_title'] ) : ?>
						<!-- Title -->
						<span class="xpro-block-quote-title"><?php echo esc_html( $settings['quote_title'] ); ?></span>
					<?php endif; ?>

					<?php if ( $settings['quote_designation'] ) : ?>
						<!-- Designation -->
						<span class="xpro-block-quote-designation"><?php echo esc_html( $settings['quote_designation'] ); ?></span>
					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</div>
