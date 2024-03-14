<?php
if ( !function_exists( 'bc_hp_team' ) ) :
	function bc_hp_team(){
		global $hotelone_options_default;
		$disable_team   = get_theme_mod( 'hotelone_team_hide', $hotelone_options_default['hotelone_team_hide']);
		$team_title    = get_theme_mod( 'hotelone_team_title', $hotelone_options_default['hotelone_team_title']);
		$team_subtitle    = get_theme_mod( 'hotelone_team_subtitle', $hotelone_options_default['hotelone_team_subtitle']);
		$column   = absint( get_theme_mod( 'hotelone_team_layout', $hotelone_options_default['hotelone_team_layout']) );
		$team_social_icons_hide   = get_theme_mod( 'hotelone_team_social_icons_hide', $hotelone_options_default['hotelone_team_social_icons_hide']);
		$team_data =  bc_get_section_team_data();
		
		if(empty( $team_data )){
			$team_data = bc_team_default_data();
		}
		
		if( ! $disable_team ){
		?>
		<div id="team" class="team_section section">
			
			<?php do_action('hotelone_section_before_inner', 'team'); ?>
			
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<?php if( !empty($team_title) ){ ?>
						<h2 class="section-title wow animated fadeInDown"><?php echo wp_kses_post($team_title); ?></h2>
						<?php } ?>
						<?php if( !empty($team_subtitle) ){ ?>
						<div class="seprator wow animated slideInLeft"></div>
						<p class="section-desc wow animated fadeInUp"><?php echo wp_kses_post($team_subtitle); ?></p>
						<?php } ?>
					</div>
				</div>
				
				<div class="row">
					
					<?php 
					foreach( $team_data as $key => $t ){ 
						$t['facebook'] =  isset( $t['facebook'] ) ? $t['facebook'] : '#';
						$t['twitter'] =  isset( $t['twitter'] ) ? $t['twitter'] : '#';
						$t['linkedin'] =  isset( $t['linkedin'] ) ? $t['linkedin'] : '#';
						$t['google-plus'] =  isset( $t['google-plus'] ) ? $t['google-plus'] : '#';
					?>
					<div class="col-md-<?php echo esc_attr( $column ); ?> col-sm-6 wow animated rollIn">
						<div class="team">
					
							<?php 
							  if( $t['image'] ){
								$url = hotelone_get_media_url( $t['image'] );
							  ?>
							<div class="team_thumbnial">
								
								<img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $t['name'] ); ?>">

								<?php if($team_social_icons_hide==0){ ?>
								<div class="team_overlay">
									<div class="team_overlay_inner">

										<?php if(isset($t['facebook_hide']) == false ){?>
										<a class="team_social_icons facebook" href="<?php echo esc_url( $t['facebook'] ); ?>"><i class="fa fa-facebook"></i></a>
										<?php } ?>

										<?php if(isset($t['twitter_hide']) == false ){?>
										<a class="team_social_icons twitter" href="<?php echo esc_url( $t['twitter'] ); ?>"><i class="fa fa-twitter"></i></a>
										<?php } ?>

										<?php if(isset($t['google_plus_hide']) == false ){?>
										<a class="team_social_icons google-plus" href="<?php echo esc_url( $t['google-plus'] ); ?>"><i class="fa fa-google-plus"></i></a>
										<?php } ?>

										<?php if(isset($t['linkedin_hide']) == false ){?>
										<a class="team_social_icons linkedin" href="<?php echo esc_url( $t['linkedin'] ); ?>"><i class="fa fa-linkedin"></i></a>
										<?php } ?>
									</div>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
							
							<div class="team_body text-center">
								<a class="team_title" href="<?php echo esc_url( $t['link'] ); ?>"><h3><?php echo esc_html( $t['name'] ); ?></h3></a>							
								<div class="team_content">
									<p class="team_designation"><?php echo esc_html( $t['designation'] ); ?></p>
								</div>							
							</div>
						</div><!-- .team -->
					</div>	
					<?php } ?>
					
					
				</div><!-- .row -->			
			</div><!-- .container -->
			
			<?php do_action('hotelone_section_after_inner', 'team'); ?>
			
		</div><!-- .team_section -->

		<?php }
	}
endif;
if ( function_exists( 'bc_hp_team' ) ) {
	$section_priority = apply_filters( 'hotelone_section_priority', 60, 'bc_hp_team' );
	add_action( 'hotelone_sections', 'bc_hp_team', absint( $section_priority ) );
}