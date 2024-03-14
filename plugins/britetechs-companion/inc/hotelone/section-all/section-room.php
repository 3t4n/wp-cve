<?php 
if ( !function_exists( 'bc_hp_room' ) ) :
	function bc_hp_room(){
		global $hotelone_options_default;

		$disable_room  = get_theme_mod( 'hotelone_room_hide', $hotelone_options_default['hotelone_room_hide']);
		$room_title    = get_theme_mod( 'hotelone_room_title', $hotelone_options_default['hotelone_room_title']);
		$room_subtitle = get_theme_mod( 'hotelone_room_subtitle', $hotelone_options_default['hotelone_room_subtitle']);
		$roomlayout    = get_theme_mod( 'hotelone_room_layout', $hotelone_options_default['hotelone_room_layout']);
		$page_ids = hotelone_lite_get_section_rooms_data();

		if( ! $disable_room ){
		?>
		<div id="room" class="room_section section">
			
			<?php do_action('hotelone_section_before_inner', 'room'); ?>
			
			<div class="container">
				<?php if( $room_title || $room_subtitle ){ ?>
				<div class="row">
					<div class="col-md-12 text-center">
						<?php if( $room_title ){ ?>
						<h2 class="section-title wow animated fadeInDown"><?php echo wp_kses_post($room_title); ?></h2>
						<?php } ?>
						
						<?php if( $room_subtitle ){ ?>
						<div class="seprator wow animated slideInLeft"></div>
						<p class="section-desc wow animated fadeInUp"><?php echo wp_kses_post($room_subtitle); ?></p>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				
				<div class="row">
				<?php  if ( ! empty( $page_ids ) ) { ?>

					<?php 
					$columns = 2;
					switch ( $roomlayout ) {
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
					
					if ( $roomlayout == 12 ) {
						$classes = 'col-sm-12 col-lg-'.$roomlayout;
					} else {
						$classes = 'col-sm-6 col-lg-'.$roomlayout;
					}
						
					if ($si >= $columns) {
						$si = 1;
						$classes .= ' clearleft';
					} else {
						$si++;
					}
					
						foreach ($page_ids as $settings) { 
						global $post;
						$_postid = $settings['content_page'];
						$_postid = apply_filters( 'wpml_object_id', $_postid, 'page', true );
						$post = get_post($_postid);
						setup_postdata( $post );
						$settings['enable_link'] = true;

						$room_image = hotelone_get_media_url( $settings['image'] );
						$room_title = isset( $settings['title'] ) && $settings['title'] != null?$settings['title']:'';
						$room_desc = isset( $settings['desc'] ) && $settings['desc'] != null ?$settings['desc']:'';
						$room_button_text = isset( $settings['button_text'] ) && $settings['button_text'] != null ? $settings['button_text'] :'';
						$room_button_url = isset( $settings['button_url'] ) && $settings['button_url'] != null ? $settings['button_url'] :'';
						$room_target = isset( $settings['target'] ) && $settings['target'] != null ? $settings['target'] :'';
					?>
					<div class="<?php echo esc_attr( $classes ); ?> wow animated fadeInUp">
						<?php if( isset($settings['content_page']) && $settings['content_page'] > 0 ){ ?>
							<div class="card-room">
								<?php if( has_post_thumbnail() ) { ?>
								<div class="room_thumbnial">
									<?php the_post_thumbnail('full'); ?>
									<div class="room_overlay">
										<div class="room_overlay_inner">
											<?php 
											  $thumbId = get_post_thumbnail_id();
											  $thumbnailUrl = wp_get_attachment_url( $thumbId );
											  ?>								
											<?php if( $settings['enable_link'] == true ){ ?>
											<a class="overlay-btn" href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
											<?php } ?>
										</div>
									</div>
								</div>
								<?php } ?>
								
								<div class="room_detail_info text-left">
									<span><?php echo esc_html( $settings['price'] ); ?></span>
									<span>
										<?php for($i=1; $i<=$settings['person']; $i++){ ?>
										<i class="fa fa-male"></i>							
										<?php } ?>
									</span>
								</div>
								<div class="room-content text-center">
									<div class="room_rate">
										<?php for($r=1; $r<=5; $r++){ ?>
											<?php if($r<=$settings['rating']){ ?>
											<i class="fa fa-star star_yellow"></i>
											<?php }else{ ?>
											<i class="fa fa-star"></i>
											<?php } ?>
										<?php } ?>
									</div>
									
									<?php if( $settings['enable_link'] == true ){ ?>
									<a href="<?php the_permalink(); ?>">
									<?php } ?>
										<?php the_title('<h4 class="room_title">','</h4>'); ?>
									<?php if( $settings['enable_link'] == true ){ ?>
									</a>
									<?php } ?>
									<div class="room_desc">
										<?php
											the_excerpt();
										?>
										<?php if( $settings['enable_link'] == true ){ ?>
										<div class="text-center">
											<a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Book Now','hotelone'); ?></a>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php } else {  ?>
								<div class="card-room">
									<?php if( $settings['icon_type'] == 'image' && $settings['image'] ) { ?>
									<div class="room_thumbnial">
										<?php 
										  $image_url = hotelone_get_media_url( $settings['image'] );
										?>
										<img src="<?php echo esc_url($image_url); ?>" class="attachment-full size-full wp-post-image" alt="" loading="lazy">
										<div class="room_overlay">
											<div class="room_overlay_inner">								
												<?php if( $room_button_url != null ){ ?>
												<a class="overlay-btn" href="<?php echo esc_url($room_button_url); ?>"><i class="fa fa-link"></i></a>
												<?php } ?>
											</div>
										</div>
									</div>
									<?php } ?>
									
									<div class="room_detail_info text-left">
										<span><?php echo esc_html( $settings['price'] ); ?></span>
										<span>
											<?php for($i=1; $i<=$settings['person']; $i++){ ?>
											<i class="fa fa-male"></i>							
											<?php } ?>
										</span>
									</div>
									<div class="room-content text-center">
										<div class="room_rate">
											<?php for($r=1; $r<=5; $r++){ ?>
												<?php if($r<=$settings['rating']){ ?>
												<i class="fa fa-star star_yellow"></i>
												<?php }else{ ?>
												<i class="fa fa-star"></i>
												<?php } ?>
											<?php } ?>
										</div>
										
										<?php if( $room_button_url != null ){ ?>
										<a href="<?php echo esc_url($room_button_url); ?>">
										<?php } ?>
											<h4 class="room_title"><?php echo wp_kses_post($room_title); ?></h4>
										<?php if( $room_button_url != null ){ ?>
										</a>
										<?php } ?>
										<div class="room_desc">
											<p><?php echo wp_kses_post($room_desc); ?></p>
											<?php if( $room_button_url != null ){ ?>
											<div class="text-center">
												<a class="more-link" href="<?php echo esc_url($room_button_url); ?>"><?php echo wp_kses_post($room_button_text); ?></a>
											</div>
											<?php } ?>
										</div>
									</div>					
								</div>
							<?php } ?>
						</div>
						<?php } wp_reset_postdata(); ?>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php do_action('hotelone_section_after_inner', 'room'); ?>
		</div>
		<?php }
	}
endif;

if ( function_exists( 'bc_hp_room' ) ) {
	$section_priority = apply_filters( 'hotelone_section_priority', 30, 'bc_hp_room' );
	add_action( 'hotelone_sections', 'bc_hp_room', absint( $section_priority ) );
}