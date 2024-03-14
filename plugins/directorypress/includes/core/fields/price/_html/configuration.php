<script>
	(function($) {
		"use strict";
	
		$(function() {
			var max_index = 0;
			
			$("#add_selection_item").click(function() {
				max_index = max_index+1;
				$("#selection_items_wrapper").append('<div class="selection_item"><input name="range_options['+max_index+']" type="text" size="40" value="" /><span class="delete_selection_item far fa-trash-alt" title="<?php esc_attr_e('Remove range option', 'DIRECTORYPRESS')?>"></span></div>');
			});
			$(document).on("click", ".delete_selection_item", function() {
				$(this).parent().remove();
			});
		});
	})(jQuery);
</script>
<div class="directorypress-modal-content wp-clearfix">
	<form class="config" method="POST" action="">
		<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_configure_fields_nonce');?>
		<div class="field-holder">
			<div><label><?php _e('Price Field Type', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<select name="price_field_type">
					<option value="1" <?php if($field->price_field_type == '1') echo 'selected'; ?>>Single</option>
					<option value="2" <?php if($field->price_field_type == '2') echo 'selected'; ?>>Range</option>
				</select>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Currency symbol', 'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></label></div>
			<div>
				<input name="currency_symbol" type="text" size="2" value="<?php echo esc_attr($field->currency_symbol); ?>" />
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Currency symbol position', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<select name="symbol_position">
					<option value="1" <?php if($field->symbol_position == '1') echo 'selected'; ?>>$1.00</option>
					<option value="2" <?php if($field->symbol_position == '2') echo 'selected'; ?>>$ 1.00</option>
					<option value="3" <?php if($field->symbol_position == '3') echo 'selected'; ?>>1.00$</option>
					<option value="4" <?php if($field->symbol_position == '4') echo 'selected'; ?>>1.00 $</option>
				</select>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Decimal separator', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<select name="decimal_separator">
					<option value="." <?php if($field->decimal_separator == '.') echo 'selected'; ?>><?php _e('dot', 'DIRECTORYPRESS')?></option>
					<option value="," <?php if($field->decimal_separator == ',') echo 'selected'; ?>><?php _e('comma', 'DIRECTORYPRESS')?></option>
				</select>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Hide decimals', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<select name="hide_decimals">
					<option value="0" <?php if($field->hide_decimals == '0') echo 'selected'; ?>><?php _e('no', 'DIRECTORYPRESS')?></option>
					<option value="1" <?php if($field->hide_decimals == '1') echo 'selected'; ?>><?php _e('yes', 'DIRECTORYPRESS')?></option>
				</select>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Thousands separator', 'DIRECTORYPRESS'); ?></label></div>
			<div>
				<select name="thousands_separator">
					<option value="" <?php if($field->thousands_separator == '') echo 'selected'; ?>><?php _e('no separator', 'DIRECTORYPRESS')?></option>
					<option value="." <?php if($field->thousands_separator == '.') echo 'selected'; ?>><?php _e('dot', 'DIRECTORYPRESS')?></option>
					<option value="," <?php if($field->thousands_separator == ',') echo 'selected'; ?>><?php _e('comma', 'DIRECTORYPRESS')?></option>
					<option value=" " <?php if($field->thousands_separator == ' ') echo 'selected'; ?>><?php _e('space', 'DIRECTORYPRESS')?></option>
				</select>
			</div>
		</div>
		<div class="field-holder">
			<div><label for="has_input_options"><?php _e('Turn On Input Options ?',  'DIRECTORYPRESS'); ?></label></div>
			<div>
				<label class="switch">
					<input id="has_input_options" name="has_input_options" type="checkbox" value="0" <?php checked(1, $field->has_input_options); ?> />
					<span class="slider"></span>
				</label>
				<p class="description"><?php _e("if checked, a dropdown option filed will be render with price field", 'DIRECTORYPRESS'); ?></p>
			</div>
		</div>
		<div class="field-holder">
			<div><label for="has_frontend_currency"><?php _e('Turn On Frontend Currency Symbol?',  'DIRECTORYPRESS'); ?></label></div>
			<div>
				<label class="switch">
					<input id="has_frontend_currency" name="has_frontend_currency" type="checkbox" value="0" <?php checked(1, $field->has_frontend_currency); ?> />
					<span class="slider"></span>
				</label>
				<p class="description"><?php _e("if checked, a input filed will be render with price field", 'DIRECTORYPRESS'); ?></p>
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php _e('Range options', 'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></label></div>
			<div id="selection_items_wrapper">
				<?php if (count($field->range_options)): ?>
					<?php foreach ($field->range_options AS $item): ?>
						<div class="selection_item">
							<input name="range_options[]" type="text" size="9" value="<?php echo esc_attr($item); ?>" />
							<i class="delete_selection_item far fa-trash-alt"></i>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="selection_item">
						<input name="range_options[1]" type="text" size="9"  value="" />
						<i class="delete_selection_item far fa-trash-alt"></i>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<input type="button" id="add_selection_item" class="button button-primary" value="<?php esc_attr_e('Add min-max option', 'DIRECTORYPRESS'); ?>" />
		<div class="id">
			<input type="hidden" name="id" value="">
		</div>
	</form>
</div>