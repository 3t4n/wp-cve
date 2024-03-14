<?php
if ( ! function_exists( 'hester_section_products' ) ) {
	function hester_section_products() {
		$show_section = hester()->options->get( 'hester_enable_products' );

		$section_style = '';
		if ( (bool) $show_section === false ) {
			if ( is_customize_preview() ) {
				$section_style .= 'display: none;';
			} else {
				return;
			}
		}

		$section_style = 'style="' . $section_style . '"';
		$sub_heading   = hester()->options->get( 'hester_products_sub_heading' );
		$heading       = hester()->options->get( 'hester_products_heading' );
		$description   = hester()->options->get( 'hester_products_description' );

		do_action( 'hester_before_products_start' ); ?>

		<section id="hester-products" class="hester_home_section hester_section_products" <?php echo wp_kses_post( $section_style ); ?>>
			<?php hester_display_customizer_shortcut( 'hester_enable_products', true ); ?>
			<div class="hester_bg hester-py-default">
				<div class="hester-container">
					<?php if ( $heading != '' || $sub_heading != '' || $description != '' ) { ?>
						<div class="hester-flex-row">
							<div class="col-md-7 col-xs-12 mx-md-auto mb-h center-xs">
								<div class="starter__heading-title">
								<?php
								if ( $sub_heading != '' ) {
									?>
										<div id="hester-products-sub-heading" class="h6 sub-title text-primary">
											<?php echo esc_html( $sub_heading ); ?>
										</div>
										<?php
								}

								if ( $heading != '' ) {
									?>
										<div id="hester-products-heading" class="h2 title">
											<?php echo esc_html( $heading ); ?>
										</div>
										<?php
								}

								if ( $description != '' ) {
									?>
										<div id="hester-products-description" class="description">
											<?php echo wp_kses_post( $description ); ?>
										</div>
										<?php
								}
								?>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="hester-flex-row">
						<div class="col-xs-12">
							<?php echo do_shortcode( '[products limit="6" columns="3"]' ); ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- .starter__products-section -->
		<?php
		do_action( 'hester_after_products_end' );
	}
}
