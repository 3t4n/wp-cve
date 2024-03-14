<div class="field-wrap field-input-item submit_field_id_<?php echo esc_attr($field->id); ?> field-type-<?php echo esc_attr($field->type); ?> clearfix">
	<p class="directorypress-submit-field-title">
		<?php echo esc_html($field->name); ?>
		<?php do_action('directorypress_listing_submit_required_lable', $field); ?>
		<?php do_action('directorypress_listing_submit_user_info', $field->description); ?>
		<?php do_action('directorypress_listing_submit_admin_info', 'listing_field_text'); ?>
	</p>
	<input type="text" name="directorypress-field-input-<?php echo esc_attr($field->id); ?>" class="directorypress-field-input-string form-control" value="<?php echo esc_attr($field->value); ?>" />
</div>