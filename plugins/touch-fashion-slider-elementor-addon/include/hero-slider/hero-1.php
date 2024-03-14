<?php if ( $settings['tfsel_slide'] ) { ?>
			<style>
				.tfsel-hero-slider-speed{
					transition-duration:<?php echo esc_html( $settings['speed'] ); ?>ms !important;
				}
				.playText {
					animation: transitionText <?php echo esc_html( $settings['speed'] + 500 ); ?>ms ease !important;
				}
			</style>
			<div class="swiper-container tfsel-slider-container tfsel-hero-1" style="height:<?php echo esc_html( $settings['slider_height'] ); ?>">
				<div class="swiper-wrapper tfsel-hero-slider-speed">
				<?php foreach ( $settings['tfsel_slide'] as $item ) { ?>

					<?php
					if ( 'Default' === $item['color_mod'] ) {
						$color_mod = $item['slide_color'];
					} else {
						$color_mod = $item['slide_custom_color'];
					}
					?>

					<div class="swiper-slide" style="background-color: <?php echo esc_html( $color_mod ); ?>">
						<div class="swiper-item" style="background-image: url( <?php echo esc_html( $item['slide_image']['url'] ); ?> )"></div>
						<span class="number">
							<i class="<?php echo esc_html( $item['slide_icon']['value'] ); ?>"></i>
						</span>
						<div
							class="slide-text"
							style="background-color: <?php echo esc_html( $color_mod ); ?>;"
						>
							<h1><?php echo esc_html( $item['slide_title'] ); ?></h1>
							<p><?php echo esc_html( $item['slide_subtitle'] ); ?></p>
							<a 
								href="<?php echo esc_url( $item['slide_url']['url'] ); ?>" 
								class="btn"
								<?php echo esc_url( $item['slide_url']['is_external'] ? 'target="_blank"' : '' ); ?>
							>
								<?php echo esc_html( $item['slide_btn_text'] ); ?>
							</a>
						</div>
					</div>
				<?php } ?>
				</div>

				<?php
					$show_dots   = ( in_array( $settings['navigation'], array( 'dots', 'both' ) ) );
					$show_arrows = ( in_array( $settings['navigation'], array( 'arrows', 'both' ) ) );
				?>

				<?php if ( $show_dots ) : ?>
					<div class="swiper-pagination"></div>
				<?php endif; ?>
				<?php if ( $show_arrows ) : ?>
				<div class="swiper-arrows">
					<div class="swiper-button-prev"><span></span></div>
					<div class="swiper-button-next"><span></span></div>
				</div>
				<?php endif; ?>
			</div>
			<?php
}
