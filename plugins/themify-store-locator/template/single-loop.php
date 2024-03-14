	<?php
 		if(!isset($contact)):
 			extract(Themify_Store_Locator::get_instance()->shortcode_defaults('store'));
 			$unlink_title = 'yes';
 			$height = '420px';
 			$is_single = true;
			get_header();
 	?>
	<!-- layout-container -->
	<div id="layout" class="pagewidth tf_clearfix">
		<!-- content -->
		<div id="content" class="list-post">
	<?php endif;?>
		<article <?php ($is_single) ? post_class('tsl_store tsl_single_tempalte') :  post_class('tsl_store'); ?> >

			<?php if ( has_post_thumbnail() && strtolower($feature_image) == 'yes') { ?>
				<figure class="tsl_store_image">
					<a href="<?php echo get_permalink(); ?>"><?php ($is_single) ? the_post_thumbnail(array(get_option('single_image_size_w',1024), get_option('single_image_size_h',768))) : the_post_thumbnail(array(get_option('archive_image_size_w',250), get_option('archive_image_size_h',250)));?></a>
				</figure>
			<?php } ?>

			<div class="tsl_store_content_wrap">
				
				<?php 
					$temp = json_decode( get_post_meta( get_the_id(), 'themify_storelocator_address', true ), true );
					if ( $is_single || ! isset( $is_single ) ) { 
						$atts = Themify_Store_Locator::get_instance()->shortcode_defaults('map');
						$atts['single_post_map'] = true;
						$location = array();
						$location['position'] = isset($temp['position']) ? $temp['position'] : '';
						$location['title']	=	get_the_title();
					?>
					<div class="tsl_store_map themify_SL_scripts" style="display:none;" data-settings="<?php echo base64_encode(json_encode($atts))?>" data-single="<?php echo base64_encode(json_encode($location))?>">
						<span class="wait_sl"><?php _e( 'Wait Loading Map...', 'themify-store-locator' ); ?></span>
					</div>
				<?php } ?>
		
				<div class="tsl_store_content">
		
					<h2 class="tsl_store_title">
					<?php if(strtolower($unlink_title) == 'no') { ?>
						<a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
					<?php } else the_title(); ?>
					</h2>
		
				<?php if(strtolower($description) == 'yes') { ?>
						<div class="tsl_store_description">
							<?php the_content(); ?>
						</div>
						<!-- /tsl_store_description -->
				<?php } ?> 
				<div class="tsl_store_address">
					<?php if(isset($temp['address'])) echo $temp['address']; ?>
				</div>
				<!-- /tsl_store_description -->
				<?php if(strtolower($contact) == 'yes') : ?>
					<div class="tsl_store_contact_wrap">
					<?php
						$contacts =  json_decode(get_post_meta(get_the_ID(),'themify_storelocator_numbers',true),true);
						foreach($contacts as $temp_contact):
					?>
						<div class="tsl_store_contact">
							<span class="tsl_contact_label"><?php echo $temp_contact['lable']; ?></span> 
							<span class="tsl_contact_number">
								<?php if ( ! empty( $temp_contact['link'] ) ) : ?><a href="<?php echo esc_url( $temp_contact['link'] ); ?>"><?php endif; ?>
									<?php echo $temp_contact['number']; ?>
								<?php if ( ! empty( $temp_contact['link'] ) ) : ?></a><?php endif; ?>
							</span>
						</div>
					<?php endforeach; ?>
					</div>
					<!-- /tsl_store_contact_wrap -->
				<?php endif;

				if(strtolower($hours) == 'yes') : ?>
					<div class="tsl_store_hour_wrap">
					<?php
						$timings =  json_decode(get_post_meta(get_the_ID(),'themify_storelocator_timing',true),true);
						foreach($timings as $timing):
					?>
						<div class="tsl_store_hour">
							<span class="tsl_store_hour_day"><?php echo $timing['lable']; ?></span>
							<span class="tsl_store_hour_time">
								<span class="tsl_open_time"><?php echo $timing['open']; ?></span>
								<span class="tsl_store_hour_divider">&#8211;</span>
								<span class="tsl_close_time"><?php echo $timing['close']; ?></span>
							</span>
						</div>
						<!-- /tsl_store_hour -->
					<?php endforeach; ?>
					</div>
					<!-- /tsl_store_hour_wrap -->
				<?php endif; ?>
				</div>
				<!-- /tsl_store_content -->
			</div>
			<!-- /tsl_store_content_wrap -->

		</article>
		<!-- /tsl_store -->
	<?php if($is_single): ?>
		</div><!-- /content -->
	</div><!-- /layout-container -->
	<?php 
		get_footer();
		endif;
	?>