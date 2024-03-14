<?php
/**
 * Checkout Order Receipt Template
 * use in Thankyou page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<ul class="order_details woo-ready-order-details">
	<li class="order">
		<?php esc_html_e( 'Order number:', 'shopready-elementor-addon' ); ?>
		<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
	</li>
	<li class="date">
		<?php esc_html_e( 'Date:', 'shopready-elementor-addon' ); ?>
		<strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
	</li>
	<li class="total">
		<?php esc_html_e( 'Total:', 'shopready-elementor-addon' ); ?>
		<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
	</li>
	<?php if ( $order->get_payment_method_title() ) : ?>
	<li class="method">
		<?php esc_html_e( 'Payment method:', 'shopready-elementor-addon' ); ?>
		<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
	</li>
	<?php endif; ?>
</ul>

<?php do_action( 'woocommerce_receipt_' . $order->get_payment_method(), $order->get_id() ); ?>

<div class="clear"></div>
