<div class="directorypress-modal-content wp-clearfix">
	<form class="add-edit" method="POST" action="">
		<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_fields_nonce');?>
		
		<div class="field-holder">
			<div><label><?php _e('Fields Group name', 'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></label></div>
			<div><input name="name" id="fields_group_name" type="text" class="regular-text" value="<?php echo esc_attr($fields_group->name); ?>" /></div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Show in Single listing Tabs', 'DIRECTORYPRESS'); ?></label></div>
			<label class="switch">
				<input name="on_tab" type="checkbox" value="1" <?php checked($fields_group->on_tab); ?> />
				<span class="slider"></span>
			</label>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Group Style', 'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></label></div>
			<div>
				<select name="group_style" id="group_style">
					<option value=""><?php _e('- Select style -', 'DIRECTORYPRESS'); ?></option>
					<option value="1" <?php selected($fields_group->group_style, '1'); ?>><?php echo esc_html__('style 1', 'DIRECTORYPRESS') ?></option>
					<option value="2" <?php selected($fields_group->group_style, '2'); ?>><?php echo esc_html__('style 2', 'DIRECTORYPRESS') ?></option>
				</select>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Hide this group from anonymous users', 'DIRECTORYPRESS'); ?></label></div>
			<label class="switch">
					<input name="hide_anonymous" type="checkbox" value="1" <?php checked($fields_group->hide_anonymous); ?> />
					<span class="slider"></span>
			</label>
		</div>
		<div class="id">
			<input type="hidden" name="id" value="">
		</div>
	</form>
</div>