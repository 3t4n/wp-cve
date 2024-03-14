<div class="directorypress-modal-content wp-clearfix">
	<form class="config" method="POST" action="">
		<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_configure_fields_nonce');?>
		
		<div class="field-holder">
			<div><label><?php _e('Enable Editor', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<label class="switch">
					<input id="html_editor" name="html_editor" type="checkbox" value="1" <?php checked(1, $field->html_editor, true)?> />
					<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Allow Shortcode?', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<label class="switch">
					<input id="do_shortcodes" name="do_shortcodes" type="checkbox" value="1" <?php checked(1, $field->do_shortcodes, true)?> />
					<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Maximum Characters Allowed', 'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></label></div>
			<div>
				<input name="max_length" type="text" size="2" value="<?php echo esc_attr($field->max_length); ?>" />
			</div>
		</div>
		<div class="id">
			<input type="hidden" name="id" value="">
		</div>
	</form>
</div>