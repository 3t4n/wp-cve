<?php
if ( ! function_exists( 'our_services_activate' ) ) :
	function our_services_activate(){
		$sections = array();
		$services_sections = apply_filters('custom_section', $sections);
		$our_services_number = get_theme_mod( 'our_services_number', 6 );
		?>
		<div class="our_services_section">
			<div class="our_services_info scroll-element js-scroll fade-in-bottom">
				<div class="our_services_main_info">
					<div class="our_services_main_title heading_main_title">
						<h2><?php echo esc_html(get_theme_mod( 'our_services_main_title', 'Our Services' )); ?></h2>
						<span class="separator"></span>
					</div>
					<div class="our_services_main_disc">
						<p><?php echo  esc_html(get_theme_mod( 'our_services_main_discription',$services_sections['service']['description']));?></p>
					</div>
				</div>
				<div class="our_services_section_data">
					<?php
					for ( $i = 1; $i <= $our_services_number ; $i++ ) {
						if($i <= 6){
							?>
							<div class="card section-services-wrep"> 
								<div class="side services-section-wrep">
									<div class="our_services_data">							
										<div class="our_services_img">
											<i class="<?php echo esc_attr(get_theme_mod( 'our_services_icon_'.$i,$services_sections['service']['icon'][$i-1]))?>"></i>
										</div>
										<div class="our_services_container">
											<div class="our_services_title">
												<h3><a href="<?php echo esc_attr(get_theme_mod( 'our_services_title_link_'.$i, '#')); ?>"><?php echo esc_html(get_theme_mod( 'our_services_title_'.$i,$services_sections['service']['title'][$i-1])); ?></a></h3>
											</div>
										</div>
									</div>
								</div>
								<div class="side back services-section-data">
									<div class="our_services_data">							
										<div class="our_services_img">
											<i class="<?php echo esc_attr(get_theme_mod( 'our_services_icon_'.$i,$services_sections['service']['icon'][$i-1]))?>"></i>
										</div>
										<div class="our_services_container">
											<div class="our_services_title">
												<h3><a href="<?php echo esc_attr(get_theme_mod( 'our_services_title_link_'.$i, '#')); ?>"><?php echo esc_html(get_theme_mod( 'our_services_title_'.$i,$services_sections['service']['title'][$i-1])); ?></a></h3>
											</div>
											<div class="our_services_discription">
												<p><?php echo esc_html(get_theme_mod( 'our_services_description_'.$i,$services_sections['service']['subheading'][$i-1])); ?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } 
						else{
							?>
							<div class="card section-services-wrep"> 
								<div class="side services-section-wrep">
									<div class="our_services_data">							
										<div class="our_services_img">
											<i class="<?php echo esc_attr(get_theme_mod( 'our_services_icon_'.$i))?>"></i>
										</div>
										<div class="our_services_container">
											<div class="our_services_title">
												<h3><a href="<?php echo esc_attr(get_theme_mod( 'our_services_title_link_'.$i, '#')); ?>"><?php echo esc_html(get_theme_mod( 'our_services_title_'.$i)); ?></a></h3>
											</div>
										</div>
									</div>
								</div>
								<div class="side back services-section-data">
									<div class="our_services_data">							
										<div class="our_services_img">
											<i class="<?php echo esc_attr(get_theme_mod( 'our_services_icon_'.$i))?>"></i>
										</div>
										<div class="our_services_container">
											<div class="our_services_title">
												<h3><a href="<?php echo esc_attr(get_theme_mod( 'our_services_title_link_'.$i, '#')); ?>"><?php echo esc_html(get_theme_mod( 'our_services_title_'.$i)); ?></a></h3>
											</div>
											<div class="our_services_discription">
												<p><?php echo esc_html(get_theme_mod( 'our_services_description_'.$i)); ?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php
						}
					} ?>
				</div>
			</div>
		</div>
		<?php
	}
endif;
