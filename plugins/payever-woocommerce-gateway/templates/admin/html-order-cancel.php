<?php
/** @var WC_Order $order */
/** @var int $order_id */
/** @var float $total_cancelled */
$currency = method_exists( $order, 'get_currency' ) ? $order->get_currency() : $order->get_order_currency();
?>
<div class="wc-order-data-row wc-order-data-row-toggle wc-payever-cancel" style="display: none;">
	<table class="wc-order-totals payever-order-totals">
		<tr>
			<td class="label"><?php esc_html_e( 'Amount already cancelled', 'payever-woocommerce-gateway' ); ?>:</td>
			<td class="total"><?php echo wc_price( $total_cancelled, array( 'currency' => $currency ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
		</tr>
		<tr>
			<td class="label"><label for="cancel_amount"><?php esc_html_e( 'Cancel amount', 'payever-woocommerce-gateway' ); ?>:</label></td>
			<td class="total">
				<input type="text" class="text wc_input_price" id="cancel_amount" name="cancel_amount" disabled="disabled" />
				<div class="clear"></div>
			</td>
		</tr>
	</table>
	<div class="clear"></div>
	<div class="cancel-actions">
		<?php $amount = '<span class="cancel-amount">' . wc_price( 0, array( 'currency' => $currency ) ) . '</span>'; ?>
		<button type="button" class="button button-primary payever-cancel-action" data-order-id="<?php esc_attr_e( $order_id ); ?>" disabled="disabled">
			<?php printf( esc_html__( 'Cancel %s', 'payever-woocommerce-gateway' ), $amount ); ?>
		</button>
		<button type="button" class="button cancel-action"><?php _e( 'Cancel', 'payever-woocommerce-gateway' ); ?></button>
		<div class="clear"></div>
	</div>
</div>
