<?php 
if ( !function_exists( 'bc_hp_testimonial' ) ) :
	function bc_hp_testimonial(){
		global $hotelone_options_default;
		$disable_testimonial   = get_theme_mod( 'hotelone_testimonial_hide', $hotelone_options_default['hotelone_testimonial_hide']);
		$testimonial_title    = get_theme_mod( 'hotelone_testimonial_title', $hotelone_options_default['hotelone_testimonial_title']);
		$testimonial_subtitle    = get_theme_mod( 'hotelone_testimonial_subtitle', $hotelone_options_default['hotelone_testimonial_subtitle']);
		$bgcolor    = get_theme_mod( 'hotelone_testimonial_bgcolor', $hotelone_options_default['hotelone_testimonial_bgcolor']);
		$bgimage    = get_theme_mod( 'hotelone_testimonial_bgimage', $hotelone_options_default['hotelone_testimonial_bgimage']);
		$testimonial_data =  bc_get_section_testimonial_data();
		
		if(empty($testimonial_data)){
			$testimonial_data = bc_testimonial_default_data();
		}

		$class = '';
		if( !empty( $bgimage ) ){
			$class = 'section-overlay';
		}

		if( ! $disable_testimonial ){
		?>
		<div id="testimonial" class="testimonial_section section <?php echo esc_attr( $class ); ?>" style="background-color: <?php echo esc_attr( $bgcolor ); ?>; background-image: url(<?php echo esc_url( $bgimage ); ?>);">
			
			<?php do_action('hotelone_section_before_inner', 'testimonial'); ?>
			
			<?php if( !empty( $bgimage ) ){ ?>
			<div class="sectionOverlay">
			<?php } ?>
			
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<?php if( !empty($testimonial_title) ){ ?>
						<h2 class="section-title wow animated fadeInDown"><?php echo wp_kses_post($testimonial_title); ?></h2>
						<?php } ?>
						<?php if( !empty($testimonial_subtitle) ){ ?>
						<div class="seprator wow animated slideInLeft"></div>
						<p class="section-desc wow animated fadeInUp"><?php echo wp_kses_post($testimonial_subtitle); ?></p>
						<?php } ?>
					</div>
				</div>
				
				<?php if(!empty($testimonial_data)){ ?>
				<div class="row">
					<div class="col-md-12">
						<div id="testimonial_slider" class="owl-carousel owl-theme" data-collg="3" data-colmd="2" data-colsm="2" data-colxs="1" data-itemspace="30" data-loop="true" data-autoplay="true" data-smartspeed="800" data-nav="true" data-dots="true">
							<?php foreach( $testimonial_data as $key => $t ){ ?>
							<div class="testimonial item">
								<div>
								  <?php 
								  $url = hotelone_get_media_url( $t['photo'] );
								  if( !empty($url) ){				
								  ?>
								  <div class="testi-image">
									<img class="animated zoomIn" src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $t['name'] ); ?>">
								  </div>
								  <?php } ?>
								  
								  <div class="testimonial_content">	
									<?php if( $t['review'] ){ ?>
									<p class="testimonial_desc animated zoomIn">" <?php echo wp_kses_post( $t['review'] ); ?> "</p>
									<?php } ?>
									<?php if( $t['name'] ){ ?>
									<h4 class="testimonial_title animated zoomIn"><?php echo wp_kses_post( $t['name'] ); ?></h4>
									<?php } ?>
									<?php if( $t['designation'] ){ ?>
									<span class="testimonial_designation animated zoomIn"><?php echo wp_kses_post( $t['designation'] ); ?></span>
									<?php } ?>
								  </div>
								</div>
							</div>
							<?php }  ?>
						</div>
					</div>
				</div><!-- .row -->	
				<?php } ?>
						
			</div><!-- .container -->
			
			<?php if( !empty( $bgimage ) ){ ?>
			</div><!-- .sectionOverlay -->
			<?php } ?>
			
			<?php do_action('hotelone_section_after_inner', 'testimonial'); ?>
			
		</div><!-- .testimonial_section -->
			
		<?php } 
	}
endif;
if ( function_exists( 'bc_hp_testimonial' ) ) {
	$section_priority = apply_filters( 'hotelone_section_priority', 70, 'bc_hp_testimonial' );
	add_action( 'hotelone_sections', 'bc_hp_testimonial', absint( $section_priority ) );
}