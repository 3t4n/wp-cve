<div class="directorypress-modal-content wp-clearfix">
	<form class="config" method="POST" action="">
		<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_configure_fields_nonce');?>
		<div class="field-holder">
			<div><label><?php _e('taget _blank? (open in new window)', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<label class="switch">
					<input name="is_blank" type="checkbox" class="regular-text" value="1" <?php if($field->is_blank) echo 'checked'; ?>/>
					<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Nofollow?', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<label class="switch">
					<input name="is_nofollow" type="checkbox" class="regular-text" value="1" <?php if($field->is_nofollow) echo 'checked'; ?> />
					<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Enable custom link text', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<label class="switch">
					<input name="use_link_text" type="checkbox" class="regular-text" value="1" <?php if($field->use_link_text) echo 'checked'; ?> />
					<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Add Default text?', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<label class="switch">
					<input name="use_default_link_text" type="checkbox" class="regular-text" value="1" <?php if($field->use_default_link_text) echo 'checked'; ?> />
					<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Default text', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<input name="default_link_text" type="text" size="2" value="<?php echo esc_attr($field->default_link_text); ?>" />
			</div>
		</div>
		<div class="id">
			<input type="hidden" name="id" value="">
		</div>
	</form>
</div>