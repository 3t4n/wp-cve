<?php
/**
 * @var WC_QuickPay_API_Transaction $transaction
 * @var int $transaction_id
 * @var null|string $transaction_status
 * @var null|string $payment_link
 * @var null|string $payment_id
 * @var mixed $transaction_order_id
 * @var string $transaction_brand
 */
?>
<?php if ( isset( $transaction ) ) : ?>
	<p class="woocommerce-quickpay-<?php echo esc_attr( $transaction_status ) ?>">
		<strong><?php _e( 'Current payment state', 'woo-quickpay' ) ?>: <?php echo $transaction_status ?></strong>
	</p>

	<?php if ( $transaction->is_action_allowed( 'standard_actions' ) ) : ?>
		<h4><strong><?php _e( 'Actions', 'woo-quickpay' ) ?></strong></h4>
		<ul class="order_action">
			<?php if ( $transaction->is_action_allowed( 'capture' ) ) : ?>
				<li class="qp-full-width">
					<a class="button button-primary" data-action="capture" data-confirm="<?php echo esc_attr( __( 'You are about to capture this payment', 'woo-quickpay' ) ) ?>">
						<?php printf( __( 'Capture Full Amount (%s)', 'woo-quickpay' ), wc_price( $transaction->get_remaining_balance_as_float(), [ 'currency' => $transaction->get_currency() ] ) ) ?>
					</a>
				</li>
			<?php endif ?>

			<li class="qp-balance">
				<span class="qp-balance__label"><?php _e( 'Remaining balance', 'woo-quickpay' ) ?>:</span>
				<span class="qp-balance__amount">
                <span class='qp-balance__currency'>
                <?php echo $transaction->get_currency() ?>
                </span>
                <?php echo $transaction->get_formatted_remaining_balance() ?></span>
			</li>

			<?php if ( $transaction->is_action_allowed( 'capture' ) ) : ?>
				<li class="qp-balance last">
                <span class="qp-balance__label">
                    <?php _e( 'Capture amount', 'woo-quickpay' ) ?>:
                </span>
					<span class="qp-balance__amount">
                    <span class='qp-balance__currency'><?php echo $transaction->get_currency() ?></span>
                    <input id='qp-balance__amount-field' type='text' value='<?php echo esc_attr( $transaction->get_formatted_remaining_balance() ) ?> '/>
                </span>
				</li>

				<li class="qp-full-width">
					<a class="button" data-action="captureAmount" data-confirm="<?php esc_attr__( 'You are about to capture this payment', 'woo-quickpay' ) ?>">
						<?php _e( 'Capture Specified Amount', 'woo-quickpay' ) ?>
					</a>
				</li>
			<?php endif ?>

			<?php if ( $transaction->is_action_allowed( 'cancel' ) ) : ?>
				<li class="qp-full-width">
					<a class="button" data-action="cancel" data-confirm="<?php esc_attr__( 'You are about to cancel this payment', 'woo-quickpay' ) ?>">
						<?php _e( 'Cancel', 'woo-quickpay' ) ?>
					</a>
				</li>
			<?php endif ?>
		</ul>
	<?php endif ?>
	<p>
		<small>
			<strong><?php echo __( 'Transaction ID', 'woo-quickpay' ) ?>:</strong> <?php echo $transaction_id ?>
			<?php if ( $brand_image_url = WC_Quickpay_Helper::get_payment_type_logo( $transaction_brand ) ) : ?>
				<span class="qp-meta-card">
                <img src="<?php echo esc_attr( $brand_image_url ) ?>" alt="<?php echo esc_attr( $transaction_brand ) ?>"/>
            </span>
			<?php endif ?>
		</small>
	</p>
<?php endif ?>

<?php if ( ! empty( $transaction_order_id ) ) : ?>
	<p>
		<small>
			<strong><?php _e( 'Transaction Order ID', 'woo-quickpay' ) ?>:</strong> <?php echo $transaction_order_id ?>
		</small>
	</p>
<?php endif ?>

<?php if ( ! empty( $payment_id ) ) : ?>
	<p>
		<small>
			<strong><?php _e( 'Payment ID', 'woo-quickpay' ) ?>:</strong> <?php echo $payment_id ?>
		</small>
	</p>
<?php endif ?>

<?php if ( ! empty( $payment_link ) ) : ?>
	<p>
		<small>
			<strong><?php _e( 'Payment Link', 'woo-quickpay' ) ?>:</strong> <br/>
			<input type="text" style="width: 100%;" value="<?php echo esc_attr( $payment_link ) ?>" readonly/>
		</small>
	</p>
<?php endif ?>
