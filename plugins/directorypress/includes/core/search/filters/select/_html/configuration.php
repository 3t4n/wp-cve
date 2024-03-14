<div class="directorypress-modal-content wp-clearfix">
	<form class="search-config" method="POST" action="">
	<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_configure_fields_nonce');?>
	<div class="field-holder">
		<div><label><?php _e('How to display in search form', 'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></label></div>
		<div>
			<select name="search_input_mode">
				<option value="checkboxes" <?php selected($search_field->search_input_mode, 'checkboxes'); ?>><?php _e('CheckBox', 'DIRECTORYPRESS'); ?></option>
				<option value="radiobutton" <?php selected($search_field->search_input_mode, 'radiobutton'); ?>><?php _e('Radio Button', 'DIRECTORYPRESS'); ?></option>
				<option value="selectbox" <?php selected($search_field->search_input_mode, 'selectbox'); ?>><?php _e('Select List', 'DIRECTORYPRESS'); ?></option>
			</select>
		</div>
	</div>
	<div class="field-holder">
		<div><label><?php _e('Operator (for checkbox only)', 'DIRECTORYPRESS'); ?></label></div>
		<div>
			<p><?php _e('OR - filter for any option available', 'DIRECTORYPRESS')?></p>
			<label class="switch">
				<input name="checkboxes_operator" type="radio" value="OR" <?php checked($search_field->checkboxes_operator, 'OR'); ?> />
				<span class="slider"></span>
			</label>
			<p><?php _e('And - filter for exact option available', 'DIRECTORYPRESS')?></p>
			<label class="switch">
				<input name="checkboxes_operator" type="radio" value="AND" <?php checked($search_field->checkboxes_operator, 'AND'); ?> />
				<span class="slider"></span>
			</label>
		</div>
	</div>
	<div class="field-holder">
		<div><label><?php _e('Show listing count', 'DIRECTORYPRESS'); ?></label></div>
		<div>
			<label class="switch">
				<input name="items_count" type="checkbox" value="1" <?php checked($search_field->items_count, 1); ?> />
				<span class="slider"></span>
			</label>
		</div>
	</div>
		<div class="id">
			<input type="hidden" name="id" value="">
		</div>
	</form>
</div>