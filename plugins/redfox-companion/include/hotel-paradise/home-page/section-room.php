<?php 
if ( !function_exists( 'rfc_frontpage_room' ) ) :
	function rfc_frontpage_room(){
		$option = wp_parse_args(  get_option( 'hotel_paradise_option', array() ), hotel_paradise_data() );
		
		$page_ids =  hotel_paradise_get_section_room_data();
		if ( empty( $page_ids ) ) {
			$page_ids = rfc_room_default_data();
		}
		
		$class = '';
		if(empty($option['room_s_bgimage'])){
			$class = 'noneimage-padding';
		}else{
			$class = 'has_section_image';
		}
						
		if( !$option['room_s_hide'] ): ?>
		<div class="rooms_wrap <?php echo esc_attr( $class ); ?>" style="background-color:<?php echo esc_attr($option['room_s_bgcolor']); ?>;background-image:url('<?php echo esc_attr($option['room_s_bgimage']); ?>');">
			
			<?php if(!empty($option['room_s_bgimage'])){ ?>
			<div class="section-overlay">
			<?php } ?>
			
			<div class="container">	
				<div class="row">
					<div class="col-md-12 text-center section_header">
						<?php if( !empty( $option['room_s_title'] ) ){ ?>
						<h2 class="wow animated fadeIn"><?php echo wp_kses_post( $option['room_s_title'] ); ?></h2>
						<?php } ?>
						
						<?php if( !empty( $option['room_s_subtitle'] ) ){ ?>
						<p class="wow animated fadeIn"><?php echo wp_kses_post( $option['room_s_subtitle'] ); ?></p>
						<?php } ?>
					</div>
				</div>
				
				<div class="row">
					<?php 
					$columns = 4;
					$sm_col = 6;
					switch ( $option['room_s_column'] ) {
						case 12:
							$columns =  12;
							$sm_col = 12;
							break;
						case 6:
							$columns =  6;
							$sm_col = 6;
							break;
						case 4:
							$columns =  4;
							$sm_col = 6;
							break;
						case 3:
							$columns =  3;
							$sm_col = 3;
							break;
					} 
					
					if ( $option['room_s_column'] ) {
							$classes = 'col-xs-12 col-sm-6 col-md-'.$columns;
						}
						
					foreach ($page_ids as $settings) { ?>
					
					<div class="<?php echo esc_attr( $classes ); ?> card_room animated wow fadeInUp">
						<div class="room_inner">					
							<?php 
							$url = hotel_paradise_get_media_url( $settings['image'] );
							if( $settings['image'] ){ ?>
							<div class="room_thumbnail">
								<a href="<?php echo esc_url( $settings['link'] ); ?>" title="<?php echo esc_url( $settings['title'] ); ?>">
									<img src="<?php echo esc_url($url); ?>">
								</a>
							</div>
							<?php } ?>
							
							<a class="room_title" href="<?php echo esc_url( $settings['link'] ); ?>" title="<?php echo esc_url( $settings['title'] ); ?>">
								<h3><?php echo esc_html( $settings['title'] ); ?></h3>
							</a>
							
							<p class="room_content">
								<?php echo esc_html( $settings['desc'] ); ?>
							</p>
							
							<a class="room-btn" href="<?php echo esc_url( $settings['link'] ); ?>"><?php esc_html_e( 'Book Now', 'redfox-companion' ); ?></a>
						</div>
					</div>
					<?php } ?>

				</div>
				
			</div>
			
			<?php if(!empty($option['room_s_bgimage'])){ ?>
			</div>
			<?php } ?>
			
		</div><!-- End rooms -->
		<?php endif; 
	}
endif;

if ( function_exists( 'rfc_frontpage_room' ) ) {
	$section_priority = apply_filters( 'hotel_paradise_section_priority', 3, 'hotel_paradise_room' );
	add_action( 'hotel_paradise_sections', 'rfc_frontpage_room', absint( $section_priority ) );
}