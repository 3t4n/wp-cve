<?php global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object; ?>
<div class="search-element-col field-id-<?php echo esc_attr($search_field->field->id); ?> field-form-id-<?php echo esc_attr($search_form->form_id); ?> unique-form-field-id-<?php echo esc_attr($search_field->field->id); ?>_<?php echo esc_attr($search_form->form_id); ?> field-type-<?php echo esc_attr($search_field->field->type); ?>  pull-left" style=" width:<?php echo esc_attr($search_field->field_width($search_form)); ?>%; padding:0 <?php echo esc_attr($search_form->args['gap_in_fields']); ?>px;">
	<?php $search_field->field_label($search_form); ?>
	<div class="field-input-wrapper">
		<input type="text" class="form-control" name="field_<?php echo esc_attr($search_field->field->slug); ?>" placeholder="<?php echo esc_attr($search_field->field->name); ?>" value="<?php echo esc_attr($search_field->value); ?>" />
	</div>
</div>