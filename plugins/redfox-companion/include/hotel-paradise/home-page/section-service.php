<?php 
if ( !function_exists( 'rfc_frontpage_service' ) ) :
	
	function rfc_frontpage_service(){
		
		$option = wp_parse_args(  get_option( 'hotel_paradise_option', array() ), hotel_paradise_data() );
		
		$page_ids =  hotel_paradise_get_section_services_data();
		if ( empty( $page_ids ) ) {
			$page_ids = rfc_service_default_data();
		}

		$class = '';
		if(empty($option['service_s_bgimage'])){
			$class = 'noneimage-padding';
		}else{
			$class = 'has_section_image';
		}

		if( !$option['service_s_hide'] ): ?>
		<div class="service_wrap <?php echo esc_attr( $class ); ?>" style="background-color:<?php echo esc_attr($option['service_s_bgcolor']); ?>;background-image:url('<?php echo esc_attr($option['service_s_bgimage']); ?>');">
			
			<?php if(!empty($option['service_s_bgimage'])){ ?>
			<div class="section-overlay">
			<?php } ?>
			
			<div class="container">
			
				<div class="row">
					<div class="col-md-12 text-center section_header">
					
						<?php if( !empty( $option['service_s_title'] ) ){ ?>
						<h2 class="wow animated fadeIn"><?php echo wp_kses_post( $option['service_s_title'] ); ?></h2>
						<?php } ?>
						
						<?php if( !empty( $option['service_s_subtitle'] ) ){ ?>
						<p class="wow animated fadeIn"><?php echo wp_kses_post( $option['service_s_subtitle'] ); ?></p>
						<?php } ?>
					</div>
				</div>
				
				<div class="row">
					<?php  if ( ! empty( $page_ids ) ) { ?>
					<?php
					$columns = 2;
					switch ( $option['service_s_column'] ) {
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
					
					$size = sanitize_text_field( get_theme_mod( 'service_s_icon_size', '5x' ) );
					
					foreach ($page_ids as $settings) {
						$settings['icon'] = trim($settings['icon']);
						
						$media = '';
						
						if ( $settings['icon'] && $settings['icon_type'] == 'icon' ) {
							$settings['icon'] = trim( $settings['icon'] );
							if ( $settings['icon'] != '' && strpos($settings['icon'], 'fa') !== 0) {
								$settings['icon'] = 'fa-' . $settings['icon'];
							}
							$media = '<a class="service_icon" href="'.esc_url($settings['link']).'" target="_blank"><i class="fa '.esc_attr( $settings['icon'] ).' fa-'.esc_attr( $size ).'" style="color: #'.$settings['iconcolor'].';"></i></a>';
						}
						if ( $option['service_s_column'] ) {
							$classes = 'col-xs-12 col-sm-6 col-md-'.$option['service_s_column'];
						} else {
							$classes = 'col-xs-12 col-sm-6 col-md-'.$option['service_s_column'];
						}

						if ($si >= $columns) {
							$si = 1;
							$classes .= ' clearleft';
						} else {
							$si++;
						}
					?>
					<div class="<?php echo esc_attr( $classes ); ?> card_service text-center animated wow fadeInUp">
						<div class="service_inner">
							<?php 
							if ( $media != '' ) {
								echo $media;
							} 
							
							if ( $settings['icon_type'] == 'image' && $settings['image'] ){
								$url = hotel_paradise_get_media_url( $settings['image'] );
								if ( $url ) {
									$media = '<div class="service-icon-image"><img src="'.esc_url( $url ).'" alt="'.esc_attr(get_the_title()).'"></div>';
								}
								echo $media;
							}
							?>
							
							<?php if($settings['enable_link']){ ?>
							<a class="service_title" href="<?php echo esc_url($settings['link']); ?>">
							<?php } 
								echo '<h3>' . esc_html($settings['title']) . '</h3>';
							if($settings['enable_link']){ ?>
							</a>
							<?php } ?>
							
							<div class="service_content">
								<?php echo wp_kses_post( $settings['desc'] ); ?>
							</div>
						</div>
					</div>
					<?php  } wp_reset_postdata(); ?>
					<?php  } ?>
					
				</div>
				
			</div>
			
			<?php if(!empty($option['service_s_bgimage'])){ ?>
			</div>
			<?php } ?>
			
		</div><!-- End service -->
		<?php endif;

	}	
endif;

if ( function_exists( 'rfc_frontpage_service' ) ) {
	$section_priority = apply_filters( 'hotel_paradise_section_priority', 2, 'hotel_paradise_service' );
	add_action( 'hotel_paradise_sections', 'rfc_frontpage_service', absint( $section_priority ) );
}