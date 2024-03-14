<?php if ($field->value): ?>
<div class="directorypress-field-item directorypress-field-type-<?php echo esc_attr($field->type); ?>">
	<?php if ($field->icon_image || !$field->is_hide_name): ?>
	<span class="field-label">
		<?php if ($field->icon_image): ?>
		<span class="directorypress-field-icon directorypress-fa directorypress-fa-lg <?php echo esc_attr($field->icon_image); ?>"></span>
		<?php endif; ?>
		<?php if (!$field->is_hide_name): ?>
		<span class="directorypress-field-title"><?php echo esc_html($field->name); ?>:</span>
		<?php endif; ?>
	</span>
	<?php endif; ?>
	<span class="field-content">
		<?php echo apply_filters('the_content', $field->value); ?>
	</span>
</div>
<?php endif; ?>