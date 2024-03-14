<?php if ( ! function_exists( 'hester_section_features' ) ) {
	function hester_section_features() {

		$show_section  = hester()->options->get( 'hester_enable_features' );
		$slides        = hester()->options->get( 'hester_features_slides' );
		$section_style = '';
		if ( (bool) $show_section === false || empty( $slides ) ) {
			if ( is_customize_preview() ) {
				$section_style .= 'display: none;';
			} else {
				return;
			}
		}
		$section_style = 'style="' . $section_style . '"';

		do_action( 'hester_before_features_start' );

		$sub_heading = hester()->options->get( 'hester_features_sub_heading' );
		$heading     = hester()->options->get( 'hester_features_heading' );
		$description = hester()->options->get( 'hester_features_description' ); ?>

		<section id="hester-features" class="hester_home_section hester_section_features is-features-init-s1" <?php echo wp_kses_post( $section_style ); ?>>
			<?php hester_display_customizer_shortcut( 'hester_enable_features', true ); ?>
			<div class="hester_bg">
				<div class="features-banner">
					<div class="hester-container <?php hester_section_classes( 'features' ); ?>">
						<div class="hester-flex-row gx-md-5 gy-sm-0 gy-4 mt-0">
							<?php if ( $heading != '' || $sub_heading != '' || $description != '' ) { ?>
								<div class="col-lg-5 col-xs-12 my-auto">
									<div class="hester-flex-row">
										<div class="col-lg-12 col-md-7 col-xs-12 mb-lg-0 mx-md-auto mb-h start-lg center-xs">
											<div class="starter__heading-title">
											<?php
											if ( $sub_heading != '' ) {
												?>
													<div id="hester-features-sub-heading" class="h6 sub-title text-primary">
														<?php echo esc_html( $sub_heading ); ?>
													</div>
													<?php
											}

											if ( $heading != '' ) {
												?>
													<div id="hester-features-heading" class="h2 title">
														<?php echo esc_html( $heading ); ?>
													</div>
													<?php
											}

											if ( $description != '' ) {
												?>
													<div id="hester-features-description" class="description">
														<?php echo wp_kses_post( $description ); ?>
													</div>
													<?php
											}
											?>

											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<div class="col-lg-7 col-xs-12 my-auto">
								<div class="hester-flex-row gy-md-4 gy-4 starter__features-wrapper">
									<?php
									foreach ( $slides as $key => $slide ) {
										if ( $key == 4 ) {
											break;
										}

										$slide       = (object) $slide;
										$title       = $slide->add_link ? sprintf( '<a href="%1$s">%2$s</a>', esc_url_raw( $slide->link ), esc_html( $slide->title ) ) : esc_html( $slide->title );
										$description = $slide->description ? sprintf( '<div class="description">%1$s</div>', esc_html( $slide->description ) ) : '';
										?>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<div class="starter__features-item">
												<div class="item--holder">
													<div class="item--holder-inner">
														<?php if ( $slide->icon != '' ) : ?>
															<div class="item--icon">
																<div class="icon"><i class="<?php echo esc_attr( $slide->icon ); ?>"></i></div>
															</div>
														<?php endif; ?>
														<div class="item--content">
															<h4 class="title"><?php echo wp_kses_post( $title ); ?></h4>
															<?php
															echo wp_kses_post( $description );
															?>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- .starter__features-section -->
		<?php do_action( 'hester_after_features_end' );
	}
}
?>
