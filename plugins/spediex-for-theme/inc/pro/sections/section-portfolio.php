<?php
if ( ! function_exists( 'our_portfolio_section_activate' ) ) :
	function our_portfolio_section_activate(){
		$sections = array();
		$portfolio_sections = apply_filters('custom_section', $sections);
		$quantity  = get_theme_mod( 'our_portfolio_number', 6 );
		?>
			<div class="our_portfolio_info" id="our_portfolio_info">
				<div class="our_portfolio_data scroll-element js-scroll fade-in-bottom">
					<?php if(get_theme_mod('our_portfolio_main_title','Our Portfolio')){
						?>
						<div class="our_portfolio_main_title heading_main_title">
							<h2><?php echo esc_html(get_theme_mod('our_portfolio_main_title','Our Portfolio'));?></h2>
							<span class="separator"></span>
						</div>
						<?php
					} ?>
					<div class="our_portfolio_main_disc">
						<p><?php echo esc_html(get_theme_mod( 'our_portfolio_main_discription',$portfolio_sections['portfolio']['description']));?></p>
					</div>		
					<div class="wrappers our_portfolio_section">
						<?php for ( $i = 1; $i <= $quantity ; $i++ ) { 
							if( $i <= 6){ ?>
							<div class="parent our_portfolio_caption">
								<div class="child our_portfolio_image">
									<div class="our_portfolio_container">
										<div class="protfolio_images">
											<?php if(get_theme_mod( 'our_portfolio_image_' .$i)){ ?>
												<img src="<?php echo esc_attr(get_theme_mod('our_portfolio_image_' .$i)); ?>" alt="The Last of us">
											<?php }else{
												?>
												<img src="<?php echo esc_attr(get_template_directory_uri()); ?>/assets/images/no-thumb.jpg" alt="The Last of us">
												<?php
											} ?>
										</div>
										<div class="our_port_containe">
											<div class="our_portfolio_title" >
												<h3><?php echo esc_html(get_theme_mod('our_portfolio_title_' .$i,$portfolio_sections['portfolio']['title'][$i-1]));?></h3>
												<p><?php echo esc_html(get_theme_mod('our_portfolio_subheading_' .$i,$portfolio_sections['portfolio']['sub_heading'][$i-1])); ?></p>
											</div>
										
											<div class="our_portfolio_btn">
												<a href="<?php echo esc_attr(get_theme_mod('our_portfolio_buttonlink_' .$i , '#')); ?>">
													<i class="<?php echo esc_attr(get_theme_mod('our_portfolio_button_' .$i,'fa fa-arrow-right')); ?>"></i> 
												</a>
											</div>
										</div>
									</div>
								</div>					
							</div>
							<?php }else{
								?>
								<div class="parent our_portfolio_caption">
									<div class="child our_portfolio_image">
										<div class="our_portfolio_container">
											<div class="protfolio_images">
												<?php if(get_theme_mod( 'our_portfolio_image_' .$i)){ ?>
													<img src="<?php echo esc_attr(get_theme_mod('our_portfolio_image_' .$i)); ?>" alt="The Last of us">
												<?php }else{
													?>
													<img src="<?php echo esc_attr(get_template_directory_uri()); ?>/assets/images/no-thumb.jpg" alt="The Last of us">
													<?php
												} ?>
											</div>
											<div class="our_port_containe">
												<div class="our_portfolio_title" >
													<h3><?php echo esc_html(get_theme_mod('our_portfolio_title_' .$i));?></h3>
													<p><?php echo esc_html(get_theme_mod('our_portfolio_subheading_' .$i)); ?></p>
												</div>
											
												<div class="our_portfolio_btn">
													<a href="<?php echo esc_attr(get_theme_mod('our_portfolio_buttonlink_' .$i , '#')); ?>">
														<i class="<?php echo esc_attr(get_theme_mod('our_portfolio_button_' .$i,'fa fa-arrow-right')); ?>"></i> 
													</a>
												</div>
											</div>
										</div>
									</div>					
								</div>
								<?php
							} ?>
						<?php } ?>
					</div>				
				</div>
			</div>
		<?php
	}
endif;