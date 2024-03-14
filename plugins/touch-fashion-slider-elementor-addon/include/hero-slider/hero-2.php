<?php if ( $settings['tfsel_hero_2'] ) { ?>
			<div class="tfsel-hero-2 swiper-container" style="height:<?php echo esc_html( $settings['slider_height'] ); ?>">
			<div class="swiper-wrapper">

				<?php foreach ( $settings['tfsel_hero_2'] as $item ) { ?>

				<div class="swiper-slide">
					<div class="slide-inner" style="background-image: url( <?php echo esc_html( $item['hero_2_image']['url'] ); ?> )"></div>

					<div class="hero-2-slide-text">
					<h1><?php echo esc_html( $item['slide_title'] ); ?></h1>
					<p><?php echo esc_html( $item['slide_subtitle'] ); ?></p>

					<?php
					if ( 'Default' === $item['color_mod'] ) {
						$color_mod = $item['button_color'];
					} else {
						$color_mod = $item['button_custom_color'];
					}
					?>

					<a 
						href="<?php echo esc_url( $item['slide_url']['url'] ); ?>" 
						style="background:<?php echo esc_html( $color_mod ); ?> !important"
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
				<?php if ( $show_arrows ) : ?>
					<div class="tfsel-swiper-button-next"><span></span></div>
					<div class="tfsel-swiper-button-prev"><span></span></div>
				<?php endif; ?>
			</div>
<?php } ?>
