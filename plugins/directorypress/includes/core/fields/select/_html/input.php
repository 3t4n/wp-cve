<?php $select2_class = (!is_admin())? 'directorypress-select2': ''; ?>
<?php if (count($field->selection_items)): ?>
	<div class="field-wrap field-input-item submit_field_id_<?php echo esc_attr($field->id); ?> field-type-<?php echo esc_attr($field->type); ?> clearfix">
		<p class="directorypress-submit-field-title">
			<?php echo esc_html($field->name); ?>
			<?php do_action('directorypress_listing_submit_required_lable', $field); ?>
			<?php do_action('directorypress_listing_submit_user_info', $field->description); ?>
			<?php do_action('directorypress_listing_submit_admin_info', 'listing_field_select'); ?>
		</p>
		<div class="select-field-wrap">
			<select name="directorypress-field-input-<?php echo esc_attr($field->id); ?>" class="directorypress-field-input-select form-control <?php echo esc_attr($select2_class); ?>">
				<option value=""><?php printf(__('- Select %s -', 'DIRECTORYPRESS'), $field->name); ?></option>
				<?php foreach ($field->selection_items AS $key=>$item): ?>
				<option value="<?php echo esc_attr($key); ?>" <?php selected($field->value, $key, true); ?>><?php echo esc_html($item); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
<?php endif; ?>