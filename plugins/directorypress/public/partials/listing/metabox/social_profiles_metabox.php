<?php global $directorypress_object; ?>
	<div class="field-wrap social-media-field clearfix">
		<p class="directorypress-submit-section-label directorypress-submit-field-title">
			<?php _e('Social Media', 'directorypress-frontend'); ?>
			<?php do_action('directorypress_listing_submit_user_info', esc_attr__('Add your social media profiles', 'directorypress-frontend')); ?>
			<?php do_action('directorypress_listing_submit_admin_info', 'listing_field_social_links'); ?>
		</p>
		<div class="input-group">
			<span class="input-group-addon"><i class="fab fa-facebook-f"></i></span>
			<input id="facebook_link" type="text" class="form-control" name="facebook_link" value="<?php echo get_post_meta($directorypress_object->current_listing->post->ID, 'facebook_link', true); ?>" placeholder="<?php _e('Facebook Page Link', 'DIRECTORYPRESS'); ?>">
		</div>
		<div class="input-group">
			<span class="input-group-addon"><i class="fab fa-twitter"></i></span>
			<input id="twitter_link" type="text" class="form-control" name="twitter_link" value="<?php echo get_post_meta($directorypress_object->current_listing->post->ID, 'twitter_link', true); ?>" placeholder="<?php _e('Twitter Profile Link', 'DIRECTORYPRESS'); ?>">
		</div>
		<div class="input-group">
			<span class="input-group-addon"><i class="fab fa-linkedin-in"></i></span>
			<input id="linkedin_link" type="text" class="form-control" name="linkedin_link" value="<?php echo get_post_meta($directorypress_object->current_listing->post->ID, 'linkedin_link', true); ?>" placeholder="<?php _e('Linkedin Profile Link', 'DIRECTORYPRESS'); ?>">
		</div>
		<div class="input-group">
			<span class="input-group-addon"><i class="fab fa-youtube"></i></span>
			<input id="youtube_link" type="text" class="form-control" name="youtube_link" value="<?php echo get_post_meta($directorypress_object->current_listing->post->ID, 'youtube_link', true); ?>" placeholder="<?php _e('Youtube Channel Link', 'DIRECTORYPRESS'); ?>">
		</div>
		<div class="input-group">
			<span class="input-group-addon"><i class="fab fa-instagram"></i></span>
			<input id="instagram_link" type="text" class="form-control" name="instagram_link" value="<?php echo get_post_meta($directorypress_object->current_listing->post->ID, 'instagram_link', true); ?>" placeholder="<?php _e('Instagram Profile Link', 'DIRECTORYPRESS'); ?>">
		</div>
	</div>
  <?php do_action('directorypress_social_profiles_metabox_html', $listing); ?>

