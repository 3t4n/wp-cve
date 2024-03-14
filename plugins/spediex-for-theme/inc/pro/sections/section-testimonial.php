<?php
if ( ! function_exists( 'our_testimonial_activate' ) ) :
	function our_testimonial_activate(){
		$sections = array();
		$testimonial_sections = apply_filters('custom_section', $sections);
		$our_testimonial_number = get_theme_mod( 'our_testimonial_number', 4 );
		?>
		<div class="our_testimonial_section">
			<div class="our_testimonial_info scroll-element js-scroll fade-in-bottom">
				<div class="our_testimonial_main_title">
					<div class="testimonial_title heading_main_title">
						<h2><?php echo esc_html(get_theme_mod( 'our_testimonial_main_title', 'Our Testimonial' )); ?></h2>
						<span class="separator"></span>
					</div>
					<div class="our_testimonial_main_disc">
						<p><?php echo esc_html(get_theme_mod( 'our_testimonial_main_discription',$testimonial_sections['testimonial']['description']));?></p>
					</div>
				</div>
				<div class="owl-carousel owl-theme testinomial_owl_slider" id="testinomial_owl_slider">
					<?php
					for ( $i = 1; $i <= $our_testimonial_number ; $i++ ) {
						if($i <= 4){
							?>
							<div class="our_testimonial_data" >
								<div class="our_testimonial_data_info">
								<div class="testimonials_image">
									<div class="image_testimonials">
										<?php
										if(get_theme_mod( 'our_testimonial_image_'.$i )){
											?>
											<img src="<?php echo esc_attr(get_theme_mod( 'our_testimonial_image_'.$i )); ?>" alt="">
											<?php
										}else{
											?>
											<img src="<?php echo esc_attr(get_template_directory_uri()); ?>/assets/images/no-thumb.jpg" alt="">								
											<?php
										}
										?>
									</div>
								</div>	
								<div class="testimonials_disc">
									<div class="our_testimonials_container">
										<p><?php echo esc_html(get_theme_mod( 'our_testimonial_description_'.$i,$testimonial_sections['testimonial']['deacription1'][$i-1])); ?></p>
										<div class="testimonials_title">
											<h3><?php echo esc_html(get_theme_mod( 'our_testimonial_title_'.$i,$testimonial_sections['testimonial']['title'][$i-1])); ?></h3>
											<h4><?php echo esc_html(get_theme_mod( 'our_testimonial_subheadline_'.$i,$testimonial_sections['testimonial']['sub_heading'][$i-1])); ?></h4>
										</div>
									</div>
								</div>					
								</div>						
							</div>
							<?php
					    }else{
					    	?>
								<div class="our_testimonial_data" >
									<div class="our_testimonial_data_info">
									<div class="testimonials_image">
										<div class="image_testimonials">
											<?php
											if(get_theme_mod( 'our_testimonial_image_'.$i )){
												?>
												<img src="<?php echo esc_attr(get_theme_mod( 'our_testimonial_image_'.$i )); ?>" alt="">
												<?php
											}else{
												?>
												<img src="<?php echo esc_attr(get_template_directory_uri()); ?>/assets/images/no-thumb.jpg" alt="">								
												<?php
											}
											?>
										</div>
									</div>	
									<div class="testimonials_disc">
										<div class="our_testimonials_container">
											<p><?php echo esc_html(get_theme_mod( 'our_testimonial_description_'.$i)); ?></p>
											<div class="testimonials_title">
												<h3><?php echo esc_html(get_theme_mod( 'our_testimonial_title_'.$i)); ?></h3>
												<h4><?php echo esc_html(get_theme_mod( 'our_testimonial_subheadline_'.$i)); ?></h4>
											</div>
										</div>
									</div>					
									</div>						
								</div>
							<?php
					    }
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
endif;