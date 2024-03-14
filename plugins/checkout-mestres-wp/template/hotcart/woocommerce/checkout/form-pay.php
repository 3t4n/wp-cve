<?php
defined( 'ABSPATH' ) || exit;
$totals = $order->get_order_item_totals(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
?>
<form id="order_review" method="post">
	<h2><?php esc_attr_e( 'Hi', 'checkout-mestres-wp' ); ?> <?php echo $order->get_billing_first_name(); ?></h2>
	<div id="payment">
		<?php if ( $order->needs_payment() ) : ?>
			<ul class="wc_payment_methods payment_methods methods">
				<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', esc_html__( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
				}
				?>
			</ul>
		<?php endif; ?>
		<div class="form-row">
			<input type="hidden" name="woocommerce_pay" value="1" />
			<?php wc_get_template( 'checkout/terms.php' ); ?>
			<?php do_action( 'woocommerce_pay_order_before_submit' ); ?>
			<a href="" class="cwmp_button_order">
				<svg width="40" height="50" viewBox="0 0 40 50" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M40 25C40 22.2425 37.7575 20 35 20H32.5V12.5C32.5 5.6075 26.8925 0 20 0C13.1075 0 7.5 5.6075 7.5 12.5V20H5C2.2425 20 0 22.2425 0 25V45C0 47.7575 2.2425 50 5 50H35C37.7575 50 40 47.7575 40 45V25ZM12.5 12.5C12.5 8.365 15.865 5 20 5C24.135 5 27.5 8.365 27.5 12.5V20H12.5V12.5Z" fill="white"/>
				</svg>
				<?php esc_attr_e( 'Buy', 'checkout-mestres-wp' ); ?>
			</a>
			
			<?php echo apply_filters( 'woocommerce_pay_order_button_html', '<button type="submit" class="button alt" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

			<?php do_action( 'woocommerce_pay_order_after_submit' ); ?>

			<?php wp_nonce_field( 'woocommerce-pay', 'woocommerce-pay-nonce' ); ?>
		</div>
	</div>
</form>
