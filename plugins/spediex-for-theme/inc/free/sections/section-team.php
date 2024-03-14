<?php
if ( ! function_exists( 'our_team_activate' ) ) :
	function our_team_activate(){
		$sections = array();
		$team_sections = apply_filters('custom_section', $sections);
		$quantity  = get_theme_mod( 'our_team_number', 4 );
		?>
		<div class="our_team_section">
			<div class="our_team_info scroll-element js-scroll fade-in-bottom">
				<div class="our_team_main_title">
					<div class="our_team_tit heading_main_title">
						<h2><?php echo esc_html(get_theme_mod( 'our_team_main_title','Our Team' )); ?></h2>
						<span class="separator"></span>
					</div>
					<div class="our_team_main_disc">
						<p><?php echo esc_html(get_theme_mod( 'our_team_main_discription',$team_sections['team']['description']));?></p>
					</div>
				</div>
				<div class="our_team_data">
					<?php for ( $i = 1; $i <= $quantity ; $i++ ) { ?>
							<div class="our_team_container">							
								<div class="our_team_container_data">	
									<div class="our_team_img">
										<?php
										if(get_theme_mod('our_team_image'.$i)){
											?>
											<img src="<?php echo esc_attr(get_theme_mod('our_team_image'.$i)); ?>">
											<?php
										}else{
											?>
											<img src="<?php echo esc_attr(get_template_directory_uri()); ?>/assets/images/no-thumb.jpg" alt="The Last of us">
											<?php
										}
										?>
									</div>
									<div class="our_team_contain_info">
										<div class="our_team_social_icon">
											<div class="dddd">
											<?php if(!empty(get_theme_mod( 'our_team_twitter_link_'.$i, 'https://twitter.com/'))){
												?>
												<a class="twitter our_social_icon" href="<?php echo esc_attr(get_theme_mod( 'our_team_twitter_link_'.$i,'https://twitter.com/' ));?>" target="_blank">
													<i class="fa fa-twitter"></i>
												</a>
												<?php
											} if(!empty(get_theme_mod( 'our_team_facebook_link_'.$i,'https://www.facebook.com/'))){
												?>									
												<a class="facebook our_social_icon" href="<?php echo esc_attr(get_theme_mod( 'our_team_facebook_link_'.$i,'https://www.facebook.com/' ));?>" target="_blank">
													<i class="fa fa-facebook"></i>
												</a>
											<?php } if(!empty(get_theme_mod( 'our_team_instagram_link_'.$i, 'https://www.instagram.com/'))){
											?>
												<a class="instagram our_social_icon" href="<?php echo esc_attr(get_theme_mod( 'our_team_instagram_link_'.$i,'https://www.instagram.com/' ));?>" target="_blank">
													<i class="fa fa-instagram"></i>
												</a>
											<?php } if(!empty(get_theme_mod( 'our_team_linkedin_link_'.$i,'https://www.linkedin.com/feed/'))){
											?>
											<a class="linkedin our_social_icon" href="<?php echo esc_attr(get_theme_mod( 'our_team_linkedin_link_'.$i,'https://www.linkedin.com/feed/' ));?>" target="_blank">
												<i class="fa fa-linkedin"></i>
											</a>
											<?php } ?>
											</div>
										</div>
										
									</div>
								</div>
								<div class="our_teams_contain">
									<div class="our_team_title">
										<a href="<?php echo esc_html(get_theme_mod( 'our_team_title_link_'.$i ,'#' )); ?>">
											<h3><?php echo esc_html(get_theme_mod( 'our_team_title_'.$i, $team_sections['team']['title'][$i-1] )); ?></h3>
										</a>
									</div>
									<div class="our_team_headline">
										<p><?php echo esc_html(get_theme_mod( 'our_team_headline_'.$i,$team_sections['team']['headline'][$i-1] ));?></p>
									</div>
								</div>	
							</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
    }
endif;