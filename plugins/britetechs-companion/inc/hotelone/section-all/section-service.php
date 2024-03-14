<?php 
if ( !function_exists( 'bc_hp_service' ) ) :
	function bc_hp_service(){
		global $hotelone_options_default;
		$disable_service   = get_theme_mod( 'hotelone_services_hide', $hotelone_options_default['hotelone_services_hide']);
		$service_title    = get_theme_mod( 'hotelone_services_title', $hotelone_options_default['hotelone_services_title']);
		$service_subtitle    = get_theme_mod( 'hotelone_services_subtitle', $hotelone_options_default['hotelone_services_subtitle']);
		$layout = intval( get_theme_mod( 'hotelone_service_layout', $hotelone_options_default['hotelone_service_layout']) );
		$services_mbtn_text = get_theme_mod( 'hotelone_services_mbtn_text', $hotelone_options_default['hotelone_services_mbtn_text']);
		$services_mbtn_url = get_theme_mod( 'hotelone_services_mbtn_url', $hotelone_options_default['hotelone_services_mbtn_url']);
		$page_ids =  hotelone_lite_get_section_services_data();

		if( ! $disable_service ){
		?>
		<div id="service" class="service_section section">

		<?php do_action('hotelone_section_before_inner', 'services'); ?>

			<div class="container">
				
				<?php if( $service_title || $service_subtitle ){ ?>
				<div class="row">
					<div class="col-md-12 text-center">
						<?php if( $service_title ){ ?>
						<h2 class="section-title wow animated fadeInDown"><?php echo wp_kses_post($service_title); ?></h2>
						<?php } ?>
						
						<?php if( $service_subtitle ){ ?>
						<div class="seprator wow animated slideInLeft"></div>
						<p class="section-desc wow animated fadeInUp"><?php echo wp_kses_post($service_subtitle); ?></p>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				
				<div class="row">
					<?php  if ( ! empty( $page_ids ) ) { ?>
					
					<?php
					$columns = 2;
					switch ( $layout ) {
						case 12:
							$columns =  1;
							break;
						case 6:
							$columns =  2;
							break;
						case 4:
							$columns =  3;
							break;
						case 3:
							$columns =  4;
							break;
					} 
					
					$si = 0;
					
					$size = sanitize_text_field( get_theme_mod( 'hotelone_service_icon_size', '5x' ) );
					
					foreach ($page_ids as $settings) {
						global $post;
						$_postid = $settings['content_page'];
						$_postid = apply_filters( 'wpml_object_id', $_postid, 'page', true );
						$post = get_post($_postid);
						setup_postdata( $post );

						$settings['icon'] = trim($settings['icon']);
						
						
						if ( $layout == 12 ) {
							$classes = 'col-sm-12 col-lg-'.$layout;
						} else {
							$classes = 'col-sm-6 col-lg-'.$layout;
						}

						if ($si >= $columns) {
							$si = 1;
							$classes .= ' clearleft';
						} else {
							$si++;
						}
					?>
					<div class="<?php echo esc_attr( $classes ); ?> service-type-<?php echo esc_attr( $settings['icon_type'] ); ?>">
						<div class="card-service">
							<?php if( isset($settings['content_page']) && $settings['content_page'] > 0 ){ ?>
							<?php 
							$media = '';
						
							if ( $settings['icon'] && $settings['icon_type'] == 'icon' ) {
								$settings['icon'] = trim( $settings['icon'] );
								if ( $settings['icon'] != '' && strpos($settings['icon'], 'fa') !== 0) {
									$settings['icon'] = 'fa-' . $settings['icon'];
								}

								if($settings['enable_link']==true){
									$media = '<a href="'.esc_url(get_the_permalink()).'" target="_blank">';
								}
								$media .= '<i class="fa '.esc_attr( $settings['icon'] ).'"></i>';
								if($settings['enable_link']==true){
									$media .= '</a>';
								}
							}

							if ( $settings['icon'] && $settings['icon_type'] == 'icon' ) { ?>
							<div class="service-icon text-center <?php echo 'fa-' . esc_attr( $size ); ?>">
								<?php if ( $media != '' ) {
									echo wp_kses_post($media);
								} ?>
							</div>
							<?php } ?>

							<div class="service-content text-center">
								<?php 
								if ( $settings['icon_type'] == 'image' && $settings['image'] ){
									$media = '';
									$url = hotelone_get_media_url( $settings['image'] );
									if ( $url ) {
										$media .= '<div class="service-icon-image">';
										if($settings['enable_link']==true){
											$media .= '<a href="'.esc_url(get_the_permalink()).'" target="_blank">';
										}
										$media .= '<img src="'.esc_url( $url ).'" alt="'.esc_attr(get_the_title()).'">';
										if($settings['enable_link']==true){
											$media .= '</a>';
										}
										$media .= '</div>';
									}
									echo wp_kses_post($media);
								}
								?>
								<?php if( $settings['enable_link'] == true ){ ?>
								<a href="<?php the_permalink(); ?>">
								<?php } ?>
									<h4 class="service_title"><?php echo get_the_title( $post ); ?></h4>
								<?php if( $settings['enable_link'] == true ){ ?>
								</a>
								<?php } ?>
								<div class="service_desc">
									<?php the_excerpt(); ?>
								</div>
							</div>
							<?php } else { 
								
								$media = '';
							
								if ( $settings['icon'] && $settings['icon_type'] == 'icon' ) {
									$settings['icon'] = trim( $settings['icon'] );
									if ( $settings['icon'] != '' && strpos($settings['icon'], 'fa') !== 0) {
										$settings['icon'] = 'fa-' . $settings['icon'];
									}
									$media = '<a href="'.esc_url($settings['button_url']).'" target="_blank"><i class="fa '.esc_attr( $settings['icon'] ).'"></i></a>';
								}

								if ( $settings['icon'] && $settings['icon_type'] == 'icon' ) { ?>
								<div class="service-icon text-center <?php echo 'fa-' . esc_attr( $size ); ?>">
									<?php if ( $media != '' ) {
										echo wp_kses_post($media);
									} ?>
								</div>
								<?php } ?>

								<div class="service-content text-center">
									<?php 
									if ( $settings['icon_type'] == 'image' && $settings['image'] ){
										$url = hotelone_get_media_url( $settings['image'] );
										if ( $url ) {
											$media = '<div class="service-icon-image"><a href="'.esc_url($settings['button_url']).'" target="_blank"><img src="'.esc_url( $url ).'" alt="'.esc_attr($settings['title']).'"></a></div>';
										}
										echo wp_kses_post($media);
									}
									?>
									<?php if( $settings['button_url'] != null ){ ?>
									<a href="<?php echo esc_url($settings['button_url']); ?>">
									<?php } ?>
										<h4 class="service_title"><?php echo wp_kses_post( $settings['title'] ); ?></h4>
									<?php if( $settings['button_url'] != null ){ ?>
									</a>
									<?php } ?>
									<div class="service_desc">
										<p><?php echo wp_kses_post( $settings['desc'] ); ?></p>
										<?php if( $settings['button_url'] != null ){ ?>
										<div class="text-center">
											<a class="theme-btn" href="<?php echo esc_url($settings['button_url']); ?>" <?php if(isset($settings['target'])){ echo 'target="_blank"'; } ?>><?php echo wp_kses_post( $settings['button_text'] ); ?></a>
										</div>
										<?php } ?>
									</div>
								</div>
							<?php } 
							?>
						</div>
					</div>
					<?php  } wp_reset_postdata(); ?>
					<?php  } ?>
					
				</div>	
				<?php if( $services_mbtn_url ){ ?>
				<div class="row">
					<div class="col-md-12 text-center">
						<a class="btn btn-white mt-3" href="<?php echo esc_url( $services_mbtn_url); ?>"><?php printf( sprintf( wp_kses_post( $services_mbtn_text ) ) ); ?></a>
					</div>
				</div><!-- .row -->
				<?php } ?>
			</div><!-- .container -->
			
		<?php do_action('hotelone_section_after_inner', 'services'); ?>
		</div><!-- .service_section -->
		<?php } 
	}
endif;

if ( function_exists( 'bc_hp_service' ) ) {
	$section_priority = apply_filters( 'hotelone_section_priority', 20, 'bc_hp_service' );
	add_action( 'hotelone_sections', 'bc_hp_service', absint( $section_priority ) );
}