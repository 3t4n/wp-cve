<div class="form-group clearfix">
	<label class="label"><?php esc_attr_e( 'Minimum Amount', 'cab-fare-calculator' ); ?></label>
	<input type="text" name="params[min_amount]" id="min_amount" class="form-control regular-text" aria-required="true" value="<?php echo esc_attr( $data->min_amount ); ?>" />
</div>
<div class="form-group clearfix">
	<label class="label"><?php esc_attr_e( 'Maximum Amount', 'cab-fare-calculator' ); ?></label>
	<input type="text" name="params[max_amount]" id="max_amount" class="form-control regular-text" aria-required="true" value="<?php echo esc_attr( $data->max_amount ); ?>" />
</div>
<div class="form-group clearfix">
	<label class="label"><?php esc_attr_e( 'Fee per transaction', 'cab-fare-calculator' ); ?></label>
	<input type="text" name="params[cost_per_transaction]" id="cost_per_transaction" class="form-control regular-text" aria-required="true" value="<?php echo esc_attr( $data->cost_per_transaction ); ?>" />
</div>
<div class="form-group clearfix">
	<label class="label"><?php esc_attr_e( 'TAX in %', 'cab-fare-calculator' ); ?></label>
	<input type="text" name="params[cost_percent_total]" id="cost_percent_total" class="form-control regular-text" aria-required="true" value="<?php echo esc_attr( $data->cost_percent_total ); ?>" />
</div>
<div class="form-group clearfix">
	<label class="label"><?php esc_attr_e( 'Default Order Status', 'cab-fare-calculator' ); ?></label>
	<div>
		<?php echo html_entity_decode( esc_html( SelectList::getDefaultOrderStatusOptions( 'params[default_status]', 'default_status', $data->default_status ) )); ?>
	</div>
</div>
