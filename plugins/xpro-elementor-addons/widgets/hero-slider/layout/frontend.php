<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Icons_Manager;

$settings = $this->get_settings_for_display();

$slider_settings = wp_json_encode(
	array_filter(
		array(
			'slide_effect'     => $settings['slide_animation'],
			'slide_speed'      => $settings['slide_speed']['size'],
			'loop'             => 'yes' === $settings['loop'],
			'mouse_drag'       => 'yes' === $settings['mouse_drag'],
			'autoplay'         => 'yes' === $settings['autoplay'],
			'autoplay_timeout' => 'yes' === $settings['autoplay'] && $settings['autoplay_timeout'] ? $settings['autoplay_timeout']['size'] : '',
		)
	)
);

?>
<div class="xpro-hero-slider-wrapper xpro-swiper-slider-theme xpro-swiper-navigation-horizontal-<?php echo esc_attr( $settings['nav_layout'] ?? 'style-1' ); ?> xpro-swiper-dots-horizontal-<?php echo esc_attr( $settings['dots_layout'] ?? 'style-1' ); ?>">
<div id="xpro-hero-slider-<?php echo esc_attr( $this->get_id() ); ?>" class="swiper xpro-hero-slider" data-xpro-hero-slider-setting="<?php echo esc_attr( $slider_settings ); ?>">
	<div class="swiper-wrapper">
		<?php
		foreach ( $settings['slide_items'] as $i => $item ) {
			?>
			<!-- Slides -->
			<div class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> swiper-slide">
				<!--Slide BG-->
				<div <?php echo esc_attr( 'slide' === $settings['slide_animation'] ? 'data-swiper-parallax=50%' : '' ); ?> class="xpro-hero-slider-slide-bg"></div>
				<!--Slide Content-->
				<div class="xpro-hero-slider-slide-content-wrapper">
					<div class="xpro-hero-slider-slide-content-area">
						<?php echo wp_kses_post( 'before_title' === $item['subtitle_position'] ? $this->render_subtitle( $item ) : '' ); ?>
						<?php echo wp_kses_post( $this->render_title( $item ) ); ?>
						<?php echo wp_kses_post( 'after_title' === $item['subtitle_position'] ? $this->render_subtitle( $item ) : '' ); ?>
						<?php if ( 'yes' === $item['enable_description'] && ! empty( $item['description'] ) ) { ?>
						<div class="xpro-hero-slider-description-wrapper">
							<div class="xpro-hero-slider-description" data-animation="<?php echo esc_attr( $item['description_animation_effect'] ); ?>">
								<?php echo wp_kses_post( $item['description'] ); ?>
							</div>
						</div>
						<?php } ?>

						<?php

						if ( 'yes' === $item['enable_primary_button'] && ! empty( $item['primary_button_title'] ) || 'yes' === $item['enable_secondary_button'] && ! empty( $item['secondary_button_title'] ) ) {
							?>

							<div class="xpro-hero-slider-slide-button-wrapper">

							<?php

							if ( 'yes' === $item['enable_primary_button'] ) {
								$primary_btn_tag   = ( $item['primary_button_link']['url'] ) ? 'a' : 'button';
								$primary_btn_attr  = ( $item['primary_button_css_id'] ) ? ' id="' . $item['primary_button_css_id'] . '"' : '';
								$primary_btn_attr .= $item['primary_button_link']['is_external'] ? ' target="_blank"' : '';
								$primary_btn_attr .= $item['primary_button_link']['nofollow'] ? ' rel="nofollow"' : '';
								$primary_btn_attr .= $item['primary_button_link']['url'] ? ' href="' . $item['primary_button_link']['url'] . '"' : '';

								if ( $item['primary_button_link'] && $item['primary_button_link']['custom_attributes'] ) {
									$primary_attributes = explode( ',', $item['primary_button_link']['custom_attributes'] );

									foreach ( $primary_attributes as $primary_attribute ) {
										if ( ! empty( $primary_attribute ) ) {
											$primary_custom_attr = explode( '|', $primary_attribute, 2 );
											if ( ! isset( $primary_custom_attr[1] ) ) {
												$primary_custom_attr[1] = '';
											}
											$primary_btn_attr .= ' ' . $primary_custom_attr[0] . '="' . $primary_custom_attr[1] . '"';
										}
									}
								}
								?>
								<<?php echo esc_attr( $primary_btn_tag ); ?> <?php echo $primary_btn_attr; ?> class="xpro-hero-slider-button-primary xpro-hero-slider-button-default" data-animation="<?php echo esc_attr( $item['primary_button_animation_effect'] ); ?>">
								<span class="xpro-hero-slider-button-text"><?php echo esc_html( $item['primary_button_title'] ); ?></span>
								<?php if ( $item['primary_button_icon']['value'] ) { ?>
                                    <span class="xpro-hero-slider-button-media"><?php Icons_Manager::render_icon( $item['primary_button_icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
								<?php } ?>
								</<?php echo esc_attr( $primary_btn_tag ); ?>>
								<?php
							}

							if ( 'yes' === $item['enable_secondary_button'] ) {
								$secondary_btn_tag   = ( $item['secondary_button_link']['url'] ) ? 'a' : 'button';
								$secondary_btn_attr  = ( $item['secondary_button_css_id'] ) ? ' id="' . $item['secondary_button_css_id'] . '"' : '';
								$secondary_btn_attr .= $item['secondary_button_link']['is_external'] ? ' target="_blank"' : '';
								$secondary_btn_attr .= $item['secondary_button_link']['nofollow'] ? ' rel="nofollow"' : '';
								$secondary_btn_attr .= $item['secondary_button_link']['url'] ? ' href="' . $item['secondary_button_link']['url'] . '"' : '';

								if ( $item['secondary_button_link'] && $item['secondary_button_link']['custom_attributes'] ) {
									$secondary_attributes = explode( ',', $item['secondary_button_link']['custom_attributes'] );

									foreach ( $secondary_attributes as $secondary_attribute ) {
										if ( ! empty( $secondary_attribute ) ) {
											$secondary_custom_attr = explode( '|', $secondary_attribute, 2 );
											if ( ! isset( $secondary_custom_attr[1] ) ) {
												$secondary_custom_attr[1] = '';
											}
											$secondary_btn_attr .= ' ' . $secondary_custom_attr[0] . '="' . $secondary_custom_attr[1] . '"';
										}
									}
								}

								?>
								<<?php echo esc_attr( $secondary_btn_tag ); ?> <?php echo $secondary_btn_attr; ?> class="xpro-hero-slider-button-secondary xpro-hero-slider-button-default" data-animation="<?php echo esc_attr( $item['secondary_button_animation_effect'] ); ?>">
								<span class="xpro-hero-slider-button-text"><?php echo esc_html( $item['secondary_button_title'] ); ?></span>
                                <?php if ( $item['secondary_button_icon']['value'] ) { ?>
                                    <span class="xpro-hero-slider-button-media"><?php Icons_Manager::render_icon( $item['secondary_button_icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
                                <?php } ?>
								</<?php echo esc_attr( $secondary_btn_tag ); ?>>
								<?php
							}

							?>
							</div>
						<?php } ?>

					</div>
				</div>
			</div>
			<?php } ?>
	</div>
</div>

<?php if ( $settings['dots'] ) : ?>
	<div class="swiper-pagination"></div>
<?php endif; ?>
<!-- Navigation Arrows -->
<?php if ( $settings['nav'] ) : ?>
	<button type="button" class="swiper-button-prev"></button>
	<button type="button" class="swiper-button-next"></button>
<?php endif; ?>
</div>
