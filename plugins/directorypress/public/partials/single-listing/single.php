<?php
global $wpdb, $DIRECTORYPRESS_ADIMN_SETTINGS, $current_user, $post, $directorypress_dynamic_styles;
$directorypress_styles = '';
$id = uniqid();
$field_ids = $wpdb->get_results('SELECT id, type, slug, group_id FROM '.$wpdb->prefix.'directorypress_fields');

?>	

<div class="single-listing directorypress-content-wrap">
	<?php if ($public_handler->listings):
		while ($public_handler->query->have_posts()):
		
			
			$public_handler->query->the_post();
			$listing = $public_handler->listings[get_the_ID()];
			$GLOBALS['listing_id'] = $public_handler->listings[get_the_ID()];
			$authorID = get_the_author_meta( 'ID' );
			$GLOBALS['authorID2'] = $authorID;
			$GLOBALS['hash'] = $public_handler;
			$single_style_class = 'default';
			$custom_layout = 0;
			?>
			<?php if($custom_layout): ?>
				<?php 
				do_action('directorypress_vc_css_fix', 3722);
				echo do_shortcode(get_post_field('post_content', 3722)); 
				
				?>
			<?php else: ?>
			<div id="<?php echo esc_attr($listing->post->post_name); ?>" itemscope itemtype="http://schema.org/LocalBusiness">
				<article id="post-<?php the_ID(); ?>" class="directorypress-listing directorypress-single-content-area <?php echo esc_attr($single_style_class); ?>">
					<?php do_action('directorypress-breadcrumb', $listing, $public_handler); ?>
					<div class="listing-main-content clearfix">
						<div class="listing-metas-single clearfix">	
							<?php do_action('single-listing-date-published', $listing); ?>		
							<?php do_action('single-listing-views', $listing); ?>
							<?php do_action('single-listing-id', $listing); ?>
						</div>
						<?php do_action('single-listing-title', $listing); ?>
						<div class="single-listing-btns clearfix">
							<ul>
								<?php 
										if ( is_user_logged_in() && $current_user->ID == $listing->post->post_author){
											echo '<li>';
												do_action('directorypress-edit-listing-button', $listing->post->ID, true, 2);
											echo '</li>';
										}
										
										do_action('directorypress_listing_buttons_list_pre', $listing->post->ID, true, 2);
										if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_report_button']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_report_button']){
											echo '<li>';
												do_action('single-listing-report', $listing, true, 2);
											echo '</li>';
										}
										if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pdf_button']){
											echo '<li>';
												do_action('single-listing-pdf', $listing, true, 2);
											echo '</li>';
										}
										if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_print_button']){
											echo '<li>';
												do_action('single-listing-print', $listing, true, 2);
											echo '</li>';
										}
										if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_favourites_list']){
											echo '<li>';
												do_action('single-listing-bookmark', $listing, true, 2);
											echo '</li>';
										}
										if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_share_buttons']['enabled']){
											echo '<li>';
												do_action('single-listing-share', $listing, true, 2);
											echo '</li>';
										}
										
										do_action('directorypress_listing_buttons_list_post', $listing->post->ID, true, 2);
										
									?>
							</ul>
						</div>
					</div>	
					<?php do_action('single-listing-slider', $listing, true); ?>
					<div class="directorypress-single-listing-text-content-wrap">
						<?php do_action('directorypress_listing_pre_content_html', $listing); ?>
						<?php
							$has_field = 0;
							foreach( $field_ids as $field_id ) {
								if($field_id->group_id == 0){
									$singlefield_id = $field_id->id;	
									if (isset($listing->fields[$singlefield_id]) && $listing->fields[$singlefield_id]->is_field_not_empty($listing)){
										$has_field = 1;
									}
								}
							}
							if($has_field){
								echo '<div class="single-filed-wrapper clearfix">';
									$listing->display_content_fields(true);
								echo '</div>';
							}
						?>
						<?php $listing->display_content_fields_ingroup(true); ?>
						<?php do_action('directorypress_listing_post_content_html', $listing); ?>
					</div>
					<?php $hash = $public_handler->hash; ?>
					<?php do_action('single-listing-tabs', $listing, $hash); ?>
					<?php do_action('single-listing-videos', $listing); ?>
					<?php do_action('single-listing-map', $listing, $hash); ?>					
					<?php do_action('single-listing-review-form', $listing); ?>
					<?php do_shortcode('[dpar_list]'); ?>
					<?php do_action('dpar_review_form', $listing); ?>	
					</article>
					
				</div>
				<?php endif; ?>
				<?php do_action('single-listing-similar', $listing); ?>
				<?php 
					echo '<div class="directorypress-custom-popup" data-popup="single_shedule_form">';
						echo '<div class="directorypress-custom-popup-inner single-shedule">';
							echo '<div class="directorypress-popup-title">'.esc_html__('Shedule a Test Drive', 'DIRECTORYPRESS').'<a class="directorypress-custom-popup-close" data-popup-close="single_shedule_form" href="#"><i class="far fa-times-circle"></i></a></div>';
							echo '<div class="directorypress-popup-content">';
								echo do_shortcode('[dhvc_form id="2578"]');
							echo'</div>';
						echo'</div>';
					echo'</div>';
					
					echo '<div class="directorypress-custom-popup" data-popup="single_tradein_form">';
						echo '<div class="directorypress-custom-popup-inner single-tradein">';
							echo '<div class="directorypress-popup-title">'.esc_html__('Apply For TradeIn With Us', 'DIRECTORYPRESS').'<a class="directorypress-custom-popup-close" data-popup-close="single_tradein_form" href="#"><i class="far fa-times-circle"></i></a></div>';
							echo '<div class="directorypress-popup-content">';
								echo do_shortcode('[dhvc_form id="2578"]');
							echo'</div>';
						echo'</div>';
					echo'</div>';
				?>
				<?php do_action('single_listing_bidding', $listing); ?>
				<?php do_action('single_listing_contact', $listing); ?>
			<?php endwhile; endif; ?>
</div>