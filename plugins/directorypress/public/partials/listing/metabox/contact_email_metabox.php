<?php if(is_admin()): ?>
	<p><?php _e("When field is empty contact messages from contact form will be sent directly to author email.", 'DIRECTORYPRESS'); ?></p>
<?php endif; ?>
<input class="directorypress-field-input-string form-control" type="text" name="contact_email" value="<?php echo esc_attr($listing->contact_email); ?>" />
<?php do_action('directorypress_contact_email_metabox_html', $listing); ?>