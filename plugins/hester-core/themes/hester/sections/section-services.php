<?php if ( ! function_exists( 'hester_section_services' ) ) {
	function hester_section_services() {

		$show_section  = hester()->options->get( 'hester_enable_services' );
		$slides        = hester()->options->get( 'hester_services_slides' );
		$column        = hester()->options->get( 'hester_services_column' );
		$section_style = '';
		if ( (bool) $show_section === false || empty( $slides ) ) {
			if ( is_customize_preview() ) {
				$section_style .= 'display: none;';
			} else {
				return;
			}
		}
		$section_style = 'style="' . $section_style . '"';

		do_action( 'hester_before_services_start' );

		$sub_heading = hester()->options->get( 'hester_services_sub_heading' );
		$heading     = hester()->options->get( 'hester_services_heading' );
		$description = hester()->options->get( 'hester_services_description' );
		?>
		<section id="hester-services" class="hester_home_section hester_section_services" <?php echo wp_kses_post( $section_style ); ?>>
			<?php hester_display_customizer_shortcut( 'hester_enable_services', true ); ?>
			<div class="hester_bg hester-py-default">
				<div class="hester-container">
					<?php if ( $heading != '' || $sub_heading != '' || $description != '' ) { ?>
						<div class="hester-flex-row">
							<div class="col-md-7 col-xs-12 mx-md-auto mb-h center-xs">
								<div class="starter__heading-title">
								<?php
								if ( $sub_heading ) {
									?>
										<div id="hester-service-sub-heading" class="h6 sub-title text-primary">
											<?php echo esc_html( $sub_heading ); ?>
										</div>
										<?php
								}
								if ( $heading != '' ) {
									?>
										<div id="hester-service-heading" class="h2 title">
											<?php echo esc_html( $heading ); ?>
										</div>
										<?php
								}
								if ( $description != '' ) {
									?>
										<div id="hester-service-description" class="description">
											<?php echo wp_kses_post( $description ); ?>
										</div>
										<?php
								}
								?>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="hester-flex-row gy-md-5 gy-5 is-services-init-s1 active__focus-row">
						<?php
						foreach ( $slides as $key => $slide ) {
							if ( $key == 3 ) {
								break;
							}

							$slide = (object) $slide;
							$title = $slide->add_link ? sprintf( '<a href="%1$s">%2$s</a>', esc_url_raw( $slide->link ), esc_html( $slide->title ) ) : esc_html( $slide->title );
							$link  = $slide->add_link ? sprintf( '<div class="readmore"><a href="%1$s" tabindex="0"><span>%2$s</span>%3$s</a></div>', esc_url_raw( $slide->link ), esc_html( $slide->linktext ), wp_kses_post( '<i class="far fa-arrow-right"></i>' ) ) : '';
							?>
							<div class="col-md<?php echo esc_attr( $column ); ?> col-sm-6 col-xs-12">
								<div class="starter__services-item active__focus-item">
									<div class="item--holder">
										<div class="item--holder-inner is__grid">
											<?php if ( $slide->icon != '' ) : ?>
												<div class="item--icon">
													<div class="icon"><i class="<?php echo esc_attr( $slide->icon ); ?>"></i></div>
												</div>
											<?php endif; ?>
											<div class="item--content">
												<h4 class="title"><?php echo wp_kses_post( $title ); ?></h4>
												<div class="description"><?php echo wp_kses_post( $slide->description ); ?></div>
												<?php echo wp_kses_post( $link ); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</section>
		<!-- .starter__services-section -->
		<?php do_action( 'hester_after_services_end' );
	}
}

?>
