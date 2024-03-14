<script>
	(function($) {
		"use strict";
	
		$(function() {
			$('select').on('change', function() {
			 // alert( $(this).val());
			 if($(this).val() == 'oncall'){
				$('.directorypress-field-input-price').attr('disabled', 'disabled');
				$('.directorypress-field-input-currency').attr('disabled', 'disabled');
			 }else{
				 $('.directorypress-field-input-price').removeAttr('disabled');
				 $('.directorypress-field-input-currency').removeAttr('disabled');
			 }
			});
		});
	})(jQuery);
</script>
<?php $select2_class = (!is_admin())? 'directorypress-select2': ''; ?>
<div class="field-wrap field-input-item submit_field_id_<?php echo esc_attr($field->id); ?> field-type-<?php echo esc_attr($field->type); ?> clearfix">
	<p class="directorypress-submit-field-title">
		<?php echo esc_html($field->name); ?>
		<?php do_action('directorypress_listing_submit_required_lable', $field); ?>
		<?php do_action('directorypress_listing_submit_user_info', $field->description); ?>
		<?php do_action('directorypress_listing_submit_admin_info', 'listing_field_price'); ?>
	</p>
	<div class="row">
		<?php if($field->has_frontend_currency): ?>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<input type="text" name="directorypress-field-price-frontend-currency-<?php echo esc_attr($field->id); ?>" class="directorypress-field-input-currency form-control" placeholder="<?php echo _e('Currency Symbol', 'DIRECTORYPRESS'); ?>" value="<?php echo esc_attr($field->data['frontend_currency']); ?>" size="4" />
			</div>
		<?php endif; ?>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<input type="text" name="directorypress-field-input-<?php echo esc_attr($field->id); ?>" class="directorypress-field-input-price form-control" placeholder="<?php echo _e('Insert Price', 'DIRECTORYPRESS'); ?>" value="<?php echo esc_attr($field->data['value']); ?>" size="4" />
		</div>
		<?php if($field->has_input_options): ?>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<select name="directorypress-field-price-options-<?php echo esc_attr($field->id); ?>" class="directorypress-field-input-select form-control <?php echo esc_attr($select2_class); ?>">
					<option value=""><?php echo esc_html__('Price Options', 'DIRECTORYPRESS'); ?></option>
					<option value="fixed" <?php selected($field->data['option_selection'], 'fixed', true); ?>><?php echo esc_html__('Fixed', 'DIRECTORYPRESS'); ?></option>
					<option value="negotiable" <?php selected($field->data['option_selection'], 'negotiable', true); ?>><?php echo esc_html__('Negotiable', 'DIRECTORYPRESS'); ?></option>
					<option value="oncall" <?php selected($field->data['option_selection'], 'oncall', true); ?>><?php echo esc_html__('On Call', 'DIRECTORYPRESS'); ?></option>
				</select>
			</div>
		<?php endif; ?>
	</div>
</div>