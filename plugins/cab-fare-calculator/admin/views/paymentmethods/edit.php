<?php
require_once TBLIGHT_PLUGIN_PATH . 'fields/select.list.php';
wp_enqueue_script( 'paymentmethods-custom', TBLIGHT_PLUGIN_DIR_URL . 'admin/js/paymentmethods.js', array(), filemtime( TBLIGHT_PATH . '/admin/js/paymentmethods.js' ), true );
?>
<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">	
		<?php wp_nonce_field( 'create-paymentmethod', 'tblight_create_paymentmethod' ); ?>
		<input type="hidden" name="action" value="save" />
		<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>

		<div id="tabs" class="paymentMethodTabs">
			<ul>
				<li><a href="#payment-info"><?php esc_attr_e( 'Payment Method Information', 'cab-fare-calculator' ); ?></a></li>
				<li><a href="#config-info"><?php esc_attr_e( 'Configuration', 'cab-fare-calculator' ); ?></a></li>
			</ul>
			<div id="payment-info">
				<div class="form-group clearfix form-required">
					<label class="label"><?php esc_attr_e( 'Title', 'cab-fare-calculator' ); ?> <span class="star">*</span></label>
					<input type="text" name="title" id="title" class="form-control regular-text requried" aria-required="true" value="<?php echo esc_attr( $item->title ); ?>" />
				</div>
				<div class="form-group clearfix">
					<label class="label"><?php esc_attr_e( 'Payment Method', 'cab-fare-calculator' ); ?></label>
					<div>
						<?php echo html_entity_decode( esc_html( SelectList::getPaymentOptions( 'payment_element', 'payment_element', $item->payment_element ) )); ?>
					</div>
				</div>
				<input type="hidden" name="language" value="*" />
				<div class="form-group clearfix">
					<label class="label"><?php esc_attr_e( 'Published', 'cab-fare-calculator' ); ?></label>
					<fieldset id="state" class="btn-group btn-group-yesno radio">
						<input type="radio" id="state1" name="state" value="1" <?php echo ( $item->state ) ? 'checked="checked"' : ''; ?> />
						<label for="state1" class="btn <?php echo ( $item->state ) ? 'active' : ''; ?>">Yes</label>
						<input type="radio" id="state0" name="state" value="0" <?php echo ( $item->state ) ? '' : 'checked="checked"'; ?> />
						<label for="state0" class="btn <?php echo ( $item->state ) ? '' : 'active'; ?>">No</label>
					</fieldset>
				</div>
				<div class="form-group clearfix">
					<label class="label"><?php esc_attr_e( 'Description', 'cab-fare-calculator' ); ?></label>
					<textarea name="text"><?php echo esc_textarea( $item->text ); ?></textarea>
				</div>
			</div>
			<div id="config-info">				
				<?php
				if ( ! empty( $item->payment_element ) && file_exists( TBLIGHT_PLUGIN_PATH . 'admin/views/paymentmethods/plugins/' . $item->payment_element . '.php' ) ) {
					require_once TBLIGHT_PLUGIN_PATH . 'admin/views/paymentmethods/plugins/' . $item->payment_element . '.php';
				} else {
					echo '<p>Please select a Payment Method in the tab Payment Method Information, and click Save button to display the appropriate parameters here.</p>';
				}
				?>
			</div>
		</div>		
		

		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-paymentmethod" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=paymentmethods' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
