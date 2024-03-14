<?php require_once TBLIGHT_PLUGIN_PATH . 'fields/select.list.php'; ?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.btn-group-yesno label.btn').click(function () {
			if (jQuery(this).prop("checked")) {
				// checked
				return;
			}
			jQuery(this).siblings('.btn').removeClass('active');
			jQuery(this).addClass('active');
			jQuery(this).parent('.btn-group-yesno').children('input').attr('checked', false);
			jQuery(this).prev('input').attr('checked', true);
		});		
	})
</script>

<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
	
		<?php wp_nonce_field( 'create-price-config', 'tblight_create_price_config' ); ?>
		<input type="hidden" name="action" value="save" />
		<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>
		<input type="hidden" name="title" id="title" value="General Price Settings" />

		<div class="form-group clearfix">
			<label class="label">Show Prices</label>
			<fieldset id="show_price" class="btn-group btn-group-yesno radio">
				<input type="radio" id="show_price1" name="configdata[show_price]" value="1" <?php echo ( $data->show_price ) ? 'checked="checked"' : ''; ?> />
				<label for="show_price1" class="btn <?php echo ( $data->show_price ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="show_price0" name="configdata[show_price]" value="0" <?php echo ( $data->show_price ) ? '' : 'checked="checked"'; ?> />
				<label for="show_price0" class="btn <?php echo ( $data->show_price ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix">
			<label class="label">Auto Approve Free Order</label>
			<fieldset id="auto_approve_free_order" class="btn-group btn-group-yesno radio">
				<input type="radio" id="auto_approve_free_order1" name="configdata[auto_approve_free_order]" value="1" <?php echo ( $data->auto_approve_free_order ) ? 'checked="checked"' : ''; ?> />
				<label for="auto_approve_free_order1" class="btn <?php echo ( $data->auto_approve_free_order ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="auto_approve_free_order0" name="configdata[auto_approve_free_order]" value="0" <?php echo ( $data->auto_approve_free_order ) ? '' : 'checked="checked"'; ?> />
				<label for="auto_approve_free_order0" class="btn <?php echo ( $data->auto_approve_free_order ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix">
			<label class="label">Debug Price Calculation</label>
			<fieldset id="debug_price_calculation" class="btn-group btn-group-yesno radio">
				<input type="radio" id="debug_price_calculation1" name="configdata[debug_price_calculation]" value="1" <?php echo ( $data->debug_price_calculation ) ? 'checked="checked"' : ''; ?> />
				<label for="debug_price_calculation1" class="btn <?php echo ( $data->debug_price_calculation ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="debug_price_calculation0" name="configdata[debug_price_calculation]" value="0" <?php echo ( $data->debug_price_calculation ) ? '' : 'checked="checked"'; ?> />
				<label for="debug_price_calculation0" class="btn <?php echo ( $data->debug_price_calculation ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix round_up_price">
			<label class="label">Round up Price?</label>
			<?php
				$active_no_class = $active_whole_class = $active_nearest_class = false;
			if ( $data->roundup_price == 'no' ) {
				$active_no_class = true;
			} elseif ( $data->roundup_price == 'whole' ) {
				$active_whole_class = true;
			} elseif ( $data->roundup_price == 'nearest5' ) {
				$active_nearest_class = true;
			}
			?>
						
			<fieldset id="roundup_price" class="btn-group btn-group-yesno radio">
				<input type="radio" id="roundup_price0" name="configdata[roundup_price]" value="no" <?php echo ( $data->roundup_price == 'no' ) ? 'checked="checked"' : ''; ?> />
				<label for="roundup_price0" class="btn <?php echo ( $active_no_class ) ? 'active' : ''; ?>">No</label>
				<input type="radio" id="roundup_price1" name="configdata[roundup_price]" value="whole" <?php echo ( $data->roundup_price == 'whole' ) ? 'checked="checked"' : ''; ?> />
				<label for="roundup_price1" class="btn <?php echo ( $active_whole_class ) ? 'active' : ''; ?>">Whole Number</label>
				<input type="radio" id="roundup_price2" name="configdata[roundup_price]" value="nearest5" <?php echo ( $data->roundup_price == 'nearest5' ) ? 'checked="checked"' : ''; ?> />
				<label for="roundup_price2" class="btn <?php echo ( $active_nearest_class ) ? 'active' : ''; ?>">Nearest 5 upwards</label>
			</fieldset>
		</div>
		<div class="form-group clearfix">
			<label class="label">Currency</label>
			<?php echo html_entity_decode( esc_html( SelectList::getCurrencyOptions( 'configdata[currency]', 'currency', $data->currency ) ) ); ?>
		</div>
		<div class="form-group clearfix">
			<label class="label">Currency Symbol</label>
			<input type="text" name="configdata[currency_symbol]" class="form-control small-text" value="<?php echo esc_attr( $data->currency_symbol ); ?>" />
		</div>
		<div class="form-group clearfix">
			<label class="label">Currency symbol position</label>
			<fieldset id="currency_sign_position" class="btn-group btn-group-yesno radio">
				<input type="radio" id="currency_sign_position0" name="configdata[currency_sign_position]" value="before" <?php echo ( $data->currency_sign_position == 'before' ) ? 'checked="checked"' : ''; ?> />
				<label for="currency_sign_position0" class="btn <?php echo ( $data->currency_sign_position == 'before' ) ? 'active' : ''; ?>">Before the amount</label>
				<input type="radio" id="currency_sign_position1" name="configdata[currency_sign_position]" value="after" <?php echo ( $data->currency_sign_position == 'after' ) ? '' : 'checked="checked"'; ?> />
				<label for="currency_sign_position1" class="btn <?php echo ( $data->currency_sign_position == 'after' ) ? 'active' : ''; ?>">After the amount</label>
			</fieldset>
		</div>
		

		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-price-config" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=configs' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
