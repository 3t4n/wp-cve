<div class="woo-freepay-order-transaction-data">
	<table border="0" cellpadding="0" cellspacing="0" class="meta">
		<tr>
			<td colspan='2'><span style='font-weight:bold'><?php _e('ID', 'freepay-for-woocommerce' ) ?>:</span> <?php echo $transaction_id ?></td>
		</tr>
		<tr>
			<td><?php _e('Method', 'freepay-for-woocommerce' ) ?>:</td>
			<td>
				<span class="transaction-brand"><img src="<?php echo $transaction_brand_logo_url ?>" alt="<?php echo $transaction_brand ?>" title="<?php echo $transaction_brand ?>" /></span>
			</td>
		</tr>
	</table>
	<div class="tags">
		<?php if ( $transaction_is_test ) : ?>
			<?php $tip_transaction_test = esc_attr( __( 'This order has been paid with test card data!', 'freepay-for-woocommerce' ) ) ?>
			<span class="tag is-test tips" data-tip="<?php echo $tip_transaction_test ?>"><?php _e( 'Test', 'freepay-for-woocommerce' ) ?></span>
		<?php endif; ?>
		<span class="tag is-<?php echo $transaction_status ?>">
			<?php echo $transaction_status ?>
		</span>
	</div>
</div>