<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.3
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin as Elementor;
$is_editor = Elementor::$instance->editor->is_edit_mode();

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
	echo '<section>';
}

if ( ! wp_doing_ajax() ) {
	?>
		<h4 id="payment_method_title" class="sellkit-one-page-checkout-payment-heading heading"><?php echo esc_html__( 'Payment', 'sellkit' ); ?></h4>
		<p class="sellkit-one-page-checkout-payment-desc sub-heading">
			<?php echo apply_filters( 'sellkit_core/widgets/checkout/custom_message/secure_transaction_text', esc_html__( 'All transactions are secure and encrypted.', 'sellkit' ) ); ?>
		</p> 
	<?php
}
if ( $is_editor ) {
	?>
		<h4 id="payment_method_title" class="sellkit-one-page-checkout-payment-heading heading"><?php echo esc_html__( 'Payment', 'sellkit' ); ?></h4>
		<p class="sellkit-one-page-checkout-payment-desc sub-heading">
			<?php echo apply_filters( 'sellkit_core/widgets/checkout/custom_message/secure_transaction_text', esc_html__( 'All transactions are secure and encrypted.', 'sellkit' ) ); ?>
		</p>
	<?php
}
	?>
<div id="payment" class="woocommerce-checkout-payment sellkit-one-page-checkout-payment-methods sellkit-checkout-widget-divider">
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					echo '<hr class="sellkit-checkout-widget-divider">';
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
		</ul>
	<?php endif; ?>
</div>
<?php
if ( $is_editor ) {
	?>
		<div class="sellkit-one-page-checkout-payment-desc sub-heading">
			<?php wc_get_template( 'checkout/terms.php' ); ?>
		</div>
	<?php
}

if ( ! wp_doing_ajax() ) {
	?>
		<div class="sellkit-one-page-checkout-payment-desc sub-heading">
			<?php wc_get_template( 'checkout/terms.php' ); ?>
		</div>
	<?php
	echo '</section>';
	do_action( 'woocommerce_review_order_after_payment' );
}
