<?php 
if ( !function_exists( 'bc_hp_slider' ) ) :
	function bc_hp_slider(){
		global $hotelone_options_default;
		$disable_slider = get_theme_mod('hotelone_slider_disable',$hotelone_options_default['hotelone_slider_disable']);
		$images = hotelone_homepage_slider_data();
		
		if( ! $disable_slider ){
		?>
		<div id="slider" class="big_section <?php echo class_exists('Hotelier') ? 'homepage-slider--has-datepicker' : ''; ?>">
				<div id="hero_carousel" class="carousel slide " data-ride="carousel" data-interval="6000">
					<?php if( count($images) > 1 ){ ?>
					<?php $i = 1; ?>
					<ol class="carousel-indicators">
					<?php foreach($images as $index => $image){ ?>
					 
						<li data-target="#hero_carousel" data-slide-to="<?php echo esc_attr( $index ); ?>" class="<?php if( $i == 1 ){ echo 'active'; } $i++; ?>"></li>		  
					
					<?php } ?>
					</ol>
					<?php } ?>
					
				<div class="carousel-inner" role="listbox">
					
					<?php $i = 1; ?>
					<?php 
					foreach($images as $slide){
						$slide['image'] = isset($slide['image']) ? $slide['image'] : '';
						$slide['large_text'] = isset($slide['large_text'] ) && $slide['large_text'] != null ? $slide['large_text'] : get_theme_mod('hotelone_slider_bigtitle');
						$slide['small_text'] = isset($slide['small_text']) && $slide['small_text'] != null ? $slide['small_text'] : get_theme_mod('hotelone_slider_subtitle');
						$slide['buttontext1'] = isset($slide['buttontext1']) && $slide['buttontext1'] != null ? $slide['buttontext1'] : get_theme_mod('hotelone_pbtn_text');
						$slide['buttonlink1'] = isset($slide['buttonlink1']) && $slide['buttonlink1'] != null ? $slide['buttonlink1'] : get_theme_mod('hotelone_pbtn_link');
						$slide['buttontarget1'] = isset($slide['buttontarget1']) ? $slide['buttontarget1'] : '';
						$slide['buttontext2'] = isset($slide['buttontext2']) && $slide['buttontext2'] != null ? $slide['buttontext2'] : get_theme_mod('hotelone_sbtn_text');
						$slide['buttonlink2'] = isset($slide['buttonlink2']) && $slide['buttonlink2'] != null ? $slide['buttonlink2'] : get_theme_mod('hotelone_sbtn_link');
						$slide['buttontarget2'] = isset($slide['buttontarget2']) ? $slide['buttontarget2'] : '';
						$slideimage = hotelone_get_media_url( $slide['image'] );
					?>
					<div class="carousel-item <?php if( $i == 1 ){ echo 'active'; } $i++; ?>">
						<img class="slide_image d-block" src="<?php echo esc_url($slideimage); ?>">
						<div class="slider_overlay">
							<div class="slider_overlay_inner text-center">
								<div class="container">
									<div class="row">
										<div class="col-md-12">
											<div class="slider_overlay_bg">										
												<div>
													<?php if(!empty( $slide['large_text'] )){ ?>
													<h2 class="big_title animated fadeInDown"><?php echo wp_kses_post( $slide['large_text'] ); ?> </h2>
													<?php } ?>
												</div>
												
												<?php if(!empty( $slide['small_text'] )){ ?>
												<p class="slider_content animated fadeInDown"><?php echo wp_kses_post( $slide['small_text'] ); ?></p>
												<?php } ?>
												
												<?php if(!empty( $slide['buttonlink1'] )){ ?>
												<a class="hotel-btn hotel-primary animated fadeInDown" href="<?php echo esc_url( $slide['buttonlink1'] ); ?>" <?php if($slide['buttontarget1']){ echo 'target="_blank"'; } ?>><?php echo wp_kses_post( $slide['buttontext1'] ); ?></a>
												<?php } ?>
												
												<?php if(!empty( $slide['buttonlink2'] )){ ?>
												<a class="hotel-btn hotel-secondry animated fadeInDown" href="<?php echo esc_url( $slide['buttonlink2'] ); ?>" <?php if($slide['buttontarget2']){ echo 'target="_blank"'; } ?>><?php echo wp_kses_post( $slide['buttontext2'] ); ?></a>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><!-- .slider_overlay -->
					</div>
					<?php } ?>	
					
				</div><!-- .carousel-inner -->
				
				<?php if( count($images) > 1 ){ ?>
				<a class="carousel-control-prev" href="#hero_carousel" role="button" data-slide="prev">
					<span class="fa fa-angle-left" aria-hidden="true"></span>
					<span class="sr-only"><?php _e('Previous','hotelone'); ?></span>
				</a>
				<a class="carousel-control-next" href="#hero_carousel" role="button" data-slide="next">
					<span class="fa fa-angle-right" aria-hidden="true"></span>
					<span class="sr-only"><?php _e('Next','hotelone'); ?></span>
				</a>
				<?php } ?>
			</div>
			<?php 
			if ( class_exists('Hotelier') ) :

				echo '<div class="datepicker-homepage-wrapper">';
					echo do_shortcode( '[hotelier_datepicker]' );
				echo '</div>';

			elseif ( class_exists( 'HotelBookingPlugin' ) ): 

				echo '<div class="mphb_homepage_search_from">';
					echo do_shortcode( '[mphb_availability_search]' );
				echo '</div>';

			endif;
			?>
		</div><!-- .big_section -->
		<?php } 
	}
endif;

if ( function_exists( 'bc_hp_slider' ) ) {
	$section_priority = apply_filters( 'hotelone_section_priority', 10, 'bc_hp_slider' );
	add_action( 'hotelone_sections', 'bc_hp_slider', absint( $section_priority ) );
}