<form method="POST" action="<?php the_permalink($listing->post->ID); ?>#contact-tab" id="directorypress_contact_form">
	<input type="hidden" name="listing_id" id="contact_listing_id" value="<?php echo esc_attr($listing->post->ID); ?>" />
	<input type="hidden" name="contact_nonce" id="contact_nonce" value="<?php print wp_create_nonce('directorypress_contact_nonce'); ?>" />
	<p id="contact_warning" class="alert alert-danger" style="display: none;"></p>
	<div class="directorypress-contact-form">
		<p>
			<input type="text" name="contact_name" id="contact_name" class="form-control" placeholder="<?php _e('Contact Name', 'DIRECTORYPRESS'); ?>" value="<?php echo esc_attr(directorypress_get_input_value($_POST, 'contact_name')); ?>" size="35" />
		</p>
		<p>
			<input type="text" name="contact_email" id="contact_email" class="form-control" placeholder="<?php _e("Contact Email", "DIRECTORYPRESS"); ?>" value="<?php echo esc_attr(directorypress_get_input_value($_POST, 'contact_email')); ?>" size="35" />
		</p>
		<p>
			<textarea name="contact_message" id="contact_message" placeholder="<?php _e("Your message", "DIRECTORYPRESS"); ?>" class="form-control" rows="15"><?php echo esc_textarea(directorypress_get_input_value($_POST, 'contact_message')); ?></textarea>
		</p>
		
		<?php echo directorypress_has_recaptcha(); ?>
		
		<input type="submit" name="submit" class="directorypress-send-message-button btn btn-primary" value="<?php esc_attr_e('Send message', 'DIRECTORYPRESS'); ?>" />
	</div>
</form>