<?php

if ( ! function_exists( 'hester_section_slider' ) ) {

	function hester_section_slider( $enable_section = false ) {
		$show_section  = $enable_section || hester()->options->get( 'hester_enable_slider' );
		$section_style = '';
		if ( (bool) $show_section === false ) {
			if ( is_customize_preview() ) {
				$section_style .= 'display: none;';
			} else {
				return;
			}
		}
		$classes       = '';
		$classes       = 'is-starter-slider-s' . hester()->options->get( 'hester_slider_style' ) . ' is-slider-' . hester()->options->get( 'hester_slider_shape' );
		$classes      .= hester()->options->get( 'hester_info_overlap' ) ? ' starter__slider-nextcomeup' : '';
		$slides        = hester()->options->get( 'hester_slider_slides' );
		$section_style = 'style="' . $section_style . '"';
		$slides_count  = count( $slides ) > 1 ? true : false;
		// this.length > 1 ? true : false > { "delay": 8000 }
		$slider_options = '
            "slidesPerView": 1,
            "direction": "vertical",
            "loop": "' . $slides_count . '",
            "effect": "fade",
            "watchOverflow": true,
            "fadeEffect": {
                "crossFade": true
            },
            "autoHeight": true,
            "centeredSlides": true,
            "speed": 2000,
            "autoplay": false,
            "keyboard": {
                "enabled": true
            },
            "pagination": {
                "el": ".swiper-pagination",
                "clickable": true
            },
            "navigation": {
                "nextEl": ".swiper-button-next",
                "prevEl": ".swiper-button-prev"
            }
        ';
		do_action( 'hester_before_slider_start' ); ?>
		<!-- dir="rtl" || Size of slider image > > 1920px X 810px -->
		<section id="hester-slider" class="starter__slider-section <?php echo esc_attr( $classes ); ?>" <?php echo wp_kses_post( $section_style ); ?>>
			<div class="hester_section_slider starter__swiper starter__slider swiper-container starter__bullet-s1" data-swiper-options='{<?php echo wp_kses_post( $slider_options ); ?>}'>
				<div class="swiper-wrapper">
				<?php
				if ( ! empty( $slides ) ) :
					foreach ( $slides as $index => $slide ) :
						$slide         = (object) $slide;
						$style_wrapper = hester_get_slide_classes( $slide );
						$button_1      = $slide->btn_1_text != '' ? sprintf( '<a class="hester-btn %3$s" href="%1$s" >%2$s</a>', $slide->btn_1_url, $slide->btn_1_text, $slide->btn_1_class ) : '';
						$button_2      = $slide->btn_2_text != '' ? sprintf( '<a class="hester-btn %3$s" href="%1$s">%2$s</a>', $slide->btn_2_url, $slide->btn_2_text, $slide->btn_2_class ) : '';
						$side_content  = '';
						if ( $slide->alignment != 'center' ) {
							if ( $slide->side_content_source == 'image' && ( isset( $slide->side_image ) && $slide->side_image['url'] != '' ) ) {

								$side_content = $slide->open_in_popup ? esc_url( $slide->side_image['url'] ) : '<img src="' . esc_url_raw( $slide->side_image['url'] ) . '">';
								// var_dump($side_content); echo "ererer";die;
							} elseif ( $slide->side_content_source == 'shortcode' && $slide->side_shortcode != '' ) {
								$side_content = do_shortcode( $slide->side_shortcode );
							} elseif ( $slide->side_content_source == 'url' && $slide->url != '' ) {

								$side_content = $slide->open_in_popup ? esc_url_raw( $slide->url ) : '[embed width="400" height="260"]' . esc_url_raw( $slide->url ) . '[/embed]';
							}
						}
						if ( $side_content != '' && $slide->open_in_popup && $slide->side_content_source != 'shortcode' ) {
							$side_content = sprintf( '<a href="%1$s" class="btn play-video-button border-effect hesterpop__video"><span class="play-icon"><i class="%2$s"></i></span></a>', $side_content, $slide->popup_icon );
						}
						?>
							<div id="hester-home-slide-<?php echo esc_attr( $index ); ?>" class="swiper-slide">
								<div class="starter__slider-wrapper is-overlay-inherit text-md-<?php esc_attr_e( $slide->alignment ); ?> text-center" <?php echo wp_kses_post( $style_wrapper['bg'] ); ?>>
								<?php if ( $slide->image['url'] != '' ) : ?>
										<div class="starter__slider-image">
											<img src="<?php echo esc_url_raw( $slide->image['url'] ); ?>" class="swiper-lazy" alt="<?php esc_attr_e( $slide->title ); ?>" />
										</div>
									<?php endif; ?>
									<div class="starter__slider-fluid" <?php echo wp_kses_post( $style_wrapper['fluid'] ); ?>>
										<div class="starter__grider">
											<div class="starter__grider-wrap">
												<div class="hester-container">
													<div class="hester-flex-row">
														<div class="col-md-7 col-xs-12 starter__slider-start">
															<div class="starter__slider-content" <?php echo wp_kses_post( $style_wrapper['content'] ); ?>>
																<div class="starter__slider-text">
																<?php if ( $slide->subtitle != '' ) : ?>
																		<div class="h6 starter__slider-subtitle"><?php echo wp_kses_post( $slide->subtitle ); ?></div>
																	<?php
																	endif;
																if ( $slide->title != '' ) :
																	?>
																		<div class="h1 starter__slider-title"><?php echo wp_kses_post( $slide->title ); ?></div>
																	<?php
																	endif;
																if ( $slide->text != '' ) :
																	?>
																		<div class="starter__slider-description"><?php echo wp_kses_post( $slide->text ); ?></div>
																	<?php endif; ?>
																</div>
																<?php if ( $button_1 != '' || $button_2 != '' ) { ?>
																	<div class="starter__slider-btn">
																		<?php
																		echo wp_kses_post( $button_1 );
																			  echo wp_kses_post( $button_2 );
																		?>
																	</div>
																<?php } ?>
															</div>
														</div>
														<?php if ( $side_content != '' ) { ?>
															<!-- /.col- text-left -->
															<div class="my-auto mt-3 col-md-5 col-xs-12 starter__slider-end mt-md-auto d-md-block d-none">
																<div class="starter__slider-content-img">
																	<?php echo wp_kses_post( $side_content ); ?>
																</div>
															</div>
														<?php } ?>
													</div>
													<!-- /.row -->
												</div>
												<!-- /.container -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
						endforeach;
					endif;
				?>
					<!-- /.swiper-slide -->
				</div>
				<!-- /.swiper-wrapper -->
				<div class="swiper-pagination"></div>

				<div class="starter__nav swiper-nav">
					<div class="swiper-button-prev">
						<i class="fa fa-angle-left"></i>
					</div>
					<div class="swiper-button-next">
						<i class="fa fa-angle-right"></i>
					</div>
				</div>
			</div>
			<!-- .starter-slider -->
			<!--div class="hester-separator"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 10V0a90 59 0 00100 0v10z"/></svg></div-->
		</section>
		<!-- .starter__slider-section -->
		<?php
		do_action( 'hester_after_slider_end' );
	}
}

/**
 * Return inline styles for a slide.
 *
 * @param array $slide Home slider's slide.
 * @return array
 */
function hester_get_slide_classes( $slide ) {
	$styles = array();

	// Gradient background style
	if ( $slide->background['background-type'] == 'color' ) {
		$bg_background = $slide->background['background-color'] != ' ' ? 'background-color:' . $slide->background['background-color'] : '';
	} elseif ( $slide->background['background-type'] == 'gradient' ) {
		$linear_value = $slide->background['gradient-linear-angle'] . 'deg,' . $slide->background['gradient-color-1'] . ' ' . $slide->background['gradient-color-1-location'] . '%,' . $slide->background['gradient-color-2'] . ' ' . $slide->background['gradient-color-2-location'] . '%';
		if ( $slide->background['gradient-type'] == 'linear' ) {
			$bg_background = '
                background: ' . $slide->background['gradient-color-1'] . ';
                background: -webkit-linear-gradient(' . $linear_value . ');
                background: -o-linear-gradient(' . $linear_value . ');
                background: linear-gradient(' . $linear_value . ');
            ';
		} else {
			$bg_background = '
                background: ' . $slide->background['gradient-color-1'] . ';
                background: -webkit-radial-gradient(' . $slide->background['gradient-position'] . ', circle, ' . $slide->background['gradient-color-1'] . ' ' . $slide->background['gradient-color-1-location'] . '%, ' . $slide->background['gradient-color-2'] . ' ' . $slide->background['gradient-color-2-location'] . '%);
                background: -o-radial-gradient(' . $slide->background['gradient-position'] . ', circle, ' . $slide->background['gradient-color-1'] . ' ' . $slide->background['gradient-color-1-location'] . '%, ' . $slide->background['gradient-color-2'] . ' ' . $slide->background['gradient-color-2-location'] . '%);
                background: radial-gradient(circle at ' . $slide->background['gradient-position'] . ', ' . $slide->background['gradient-color-1'] . ' ' . $slide->background['gradient-color-1-location'] . '%, ' . $slide->background['gradient-color-2'] . ' ' . $slide->background['gradient-color-2-location'] . '%);
            ';
		}
	}

	$styles['bg']      = 'style="' . $bg_background . '"';
	$styles['fluid']   = '';
	$styles['content'] = '';

	// Accent color styles.
	if ( $slide->accent_color != '' ) {
		$styles['fluid'] = 'style="--hester-primary:' . $slide->accent_color . ';--hester-primary_15:' . hester_luminance( $slide->accent_color, .15 ) . '"';
	}
	if ( $slide->text_color != '' ) {
		$styles['content'] = 'style="--hester-white:' . $slide->text_color . ';"';
	}
	return $styles;
}

if ( function_exists( 'hester_section_slider' ) ) {
	$section_priority = apply_filters( 'hester_section_priority', 0, 'hester_section_slider' );
	add_action( 'hester_before_home_order_sections', 'hester_section_slider', absint( $section_priority ) );
}
