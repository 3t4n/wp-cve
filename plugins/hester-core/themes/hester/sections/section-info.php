<?php
if ( ! function_exists( 'hester_section_info' ) ) {
	function hester_section_info( $enable_section = false ) {
		$show_section  = $enable_section || hester()->options->get( 'hester_enable_info' );
		$slides        = hester()->options->get( 'hester_info_slides' );
		$style         = hester()->options->get( 'hester_info_style' );
		$column        = hester()->options->get( 'hester_info_column' );
		$section_style = '';
		if ( (bool) $show_section === false || empty( $slides ) ) {
			if ( is_customize_preview() ) {
				$section_style .= 'display: none;';
			} else {
				return;
			}
		}
		$sections = (array) json_decode( hester()->options->get( 'hester_sections_order' ) );

		$classes = '';
		$classes = 'is-info-init-s' . $style;
		if ( ! hester()->options->get( 'hester_info_overlap' ) || $sections['hester_section_info'] !== 10 ) {
			$classes = 'is-info-init-s' . $style . ' hester_home_section';
		}

		$section_style = 'style="' . $section_style . '"';

		$sub_heading = hester()->options->get( 'hester_info_sub_heading' );
		$heading     = hester()->options->get( 'hester_info_heading' );
		$description = hester()->options->get( 'hester_info_description' );
		do_action( 'hester_before_info_start' ); ?>

		<section id="hester-info" class="<?php echo esc_attr( $classes ); ?> hester_section_info" <?php echo wp_kses_post( $section_style ); ?>>
			<?php
			if ( $classes != '' ) {
				hester_display_customizer_shortcut( 'hester_enable_info', true );
			}
			?>
			<div class="hester_bg 
			<?php
			if ( ! hester()->options->get( 'hester_info_overlap' ) ) {
				echo 'hester-py-default'; }
			?>
			">
				<div class="hester-container">
					<?php
					if ( ! hester()->options->get( 'hester_info_overlap' ) ) {
						if ( $heading != '' || $sub_heading != '' || $description != '' ) {
							?>
							<div class="hester-flex-row">
								<div class="col-md-7 col-xs-12 mx-md-auto mb-h center-xs">
									<div class="starter__heading-title">
									<?php
									if ( $sub_heading ) {
										?>
											<div id="hester-info-sub-heading" class="h6 sub-title text-primary">
												<?php echo esc_html( $sub_heading ); ?>
											</div>
											<?php
									}
									if ( $heading != '' ) {
										?>
											<div id="hester-info-heading" class="h2 title">
												<?php echo esc_html( $heading ); ?>
											</div>
											<?php
									}

									if ( $description != '' ) {
										?>
											<div id="hester-info-description" class="description">
												<?php echo wp_kses_post( $description ); ?>
											</div>
											<?php
									}
									?>

									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
					<div class="hester-flex-row">
						<div class="col-xs-12 wow fadeInUp">
							<div class="hester-flex-row gy-4 starter__info-wrapper active__focus-row">
								<?php
								foreach ( $slides as $key => $slide ) {
									$slide = (object) $slide;
									if ( $key == 4 ) {
										break;
									}

									$title    = $slide->add_link ? sprintf( '<a href="%1$s">%2$s</a>', esc_url_raw( $slide->link ), esc_html( $slide->title ) ) : $slide->title;
									$link     = ( $slide->add_link == true && $slide->linktext != '' ) ? sprintf( '<div class="readmore"><a href="%1$s" tabindex="0"><span>%2$s</span>%3$s</a></div>', esc_url_raw( $slide->link ), esc_html( $slide->linktext ), wp_kses_post( '<i class="far fa-arrow-right"></i>' ) ) : '';
									$isactive = $slide->is_active ? ' active' : '';
									?>
									<div class="col-md<?php echo esc_attr( $column ); ?> col-sm-6 col-xs-12">
										<div class="starter__info-item active__focus-item<?php echo esc_attr( $isactive ); ?>">
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
				</div>
			</div>
		</section>
		<!-- .starter__info-section -->

		<?php do_action( 'hester_after_info_end' );
	}
}
?>
