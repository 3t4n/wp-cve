<?php
/** @var WC_Order $order */
/** @var int $order_id */
/** @var float $total_captured */
/** @var float $remaining_total */
/** @var array $providers_list */
$disabled = ! WC_Payever_Helper::instance()->is_allow_order_capture_by_amount( $order_id );
$currency = method_exists( $order, 'get_currency' ) ? $order->get_currency() : $order->get_order_currency();
?>
<div class="wc-order-data-row wc-order-data-row-toggle wc-payever-capture" style="display:none;">
	<table class="wc-order-totals payever-order-totals">

		<tr>
			<td class="label"><?php esc_html_e( 'Amount already captured', 'payever-woocommerce-gateway' ); ?>:</td>
			<td class="total"><?php echo wc_price( $total_captured, array( 'currency' => $currency ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>

		<?php if ( $remaining_total > 0 ) : ?>
			<tr>
				<td class="label"><?php esc_html_e( 'Remaining order total', 'payever-woocommerce-gateway' ); ?>:</td>
				<td class="total"><?php echo wc_price( $remaining_total, array( 'currency' => $currency ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
			</tr>
		<?php endif; ?>

		<tr>
			<td class="label"><label for="capture_amount"><?php esc_html_e( 'Capture amount', 'payever-woocommerce-gateway' ); ?>:</label></td>
			<td class="total">
				<input type="text" class="text wc_input_price" id="capture_amount" name="capture_amount"
				<?php if ( $disabled ) : ?>
					disabled="disabled"
				<?php endif; ?> />
				<div class="clear"></div>
			</td>
		</tr>
		<tr>
			<td class="label"><label for="capture_comment"><?php esc_html_e( 'Comment (optional):', 'payever-woocommerce-gateway' ); ?></label></td>
			<td class="total">
				<input type="text" class="text" id="capture_comment" name="capture_comment" />
				<div class="clear"></div>
			</td>
		</tr>

		<?php if ( $order->needs_processing() ) : ?>
			<?php if ( count( $providers_list ) > 0 ) : ?>
			<tr>
				<td class="label">
					<label for="wc_shipping_provider">
						<?php esc_html_e( 'Shipping Provider', 'payever-woocommerce-gateway' ); ?>
					</label>
				</td>
				<td class="total">
						<select id="wc_shipping_provider" name="wc_shipping_provider" class="chosen_select">
							<option value="" selected>
								<?php esc_html_e( 'Custom Provider', 'payever-woocommerce-gateway' ); ?>
							</option>
							<?php foreach ( $providers_list as $provider_group => $providers ) : ?>
							<optgroup label="<?php esc_attr_e( $provider_group ); ?>">
								<?php foreach ( $providers as $provider => $url ) : ?>
									<option value="<?php esc_attr_e( wc_clean( $provider ) ); ?>"
											data-url="<?php esc_attr_e( wc_clean( $url ) ); ?>">
										<?php esc_html_e( $provider ); ?>
									</option>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</select>
					<div class="clear"></div>
				</td>
			</tr>
			<?php endif; ?>

			<tr id="provider_custom_row">
				<td class="label">
					<label for="wc_shipping_provider_custom">
						<?php esc_html_e( 'Shipping Provider Name', 'payever-woocommerce-gateway' ); ?>:
					</label>
				</td>
				<td class="total">
					<input type="text" class="text" id="wc_shipping_provider_custom" name="wc_shipping_provider_custom"  />
					<div class="clear"></div>
				</td>
			</tr>

			<tr>
				<td class="label">
					<label for="wc_tracking_number">
						<?php esc_html_e( 'Shipping Tracking Number', 'payever-woocommerce-gateway' ); ?>:
					</label>
				</td>
				<td class="total">
					<input type="text" class="text" id="wc_tracking_number" name="wc_tracking_number" />
					<div class="clear"></div>
				</td>
			</tr>
			<tr id="tracking_url_row">
				<td class="label">
					<label for="wc_tracking_url">
						<?php esc_html_e( 'Shipping Tracking Url', 'payever-woocommerce-gateway' ); ?>
					</label>
				</td>
				<td class="total">
					<input type="text" class="text" id="wc_tracking_url" name="wc_tracking_url" placeholder="http://" />
					<div class="clear"></div>
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="wc_shipping_date">
						<?php esc_html_e( 'Shipping Date', 'payever-woocommerce-gateway' ); ?>
					</label>
				</td>
				<td class="total">
					<input type="text"
						class="date-picker-field"
						id="wc_shipping_date"
						name="wc_shipping_date"
						placeholder="<?php echo date_i18n( __( 'Y-m-d', 'payever-woocommerce-gateway' ), time() ); ?>"
						value="<?php echo date_i18n( __( 'Y-m-d', 'payever-woocommerce-gateway' ), time() ); ?>"
					/>
					<div class="clear"></div>
				</td>
			</tr>
		<?php endif; ?>
	</table>
	<div class="clear"></div>
	<div class="capture-actions">
		<?php $amount = '<span class="capture-amount">' . wc_price( 0, array( 'currency' => $currency ) ) . '</span>'; ?>
		<button type="button" class="button button-primary payever-capture-action" data-order-id="<?php esc_attr_e( $order_id ); ?>" disabled="disabled">
			<?php printf( esc_html__( 'Capture %s', 'payever-woocommerce-gateway' ), $amount ); ?>
		</button>
		<button type="button" class="button cancel-action">
			<?php _e( 'Cancel', 'payever-woocommerce-gateway' ); ?>
		</button>

		<div class="clear"></div>
	</div>
</div>
