<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<div class="acfw-order-used-coupons wc-order-preview-address" style="padding-top: 0;">
    <strong><?php esc_html_e( 'Store Credit Used', 'advanced-coupons-for-woocommerce-free' ); ?></strong>
    <span class="store-credit-amount"><?php echo wp_kses_post( wc_price( $sc_data['amount'] * -1, array( $order->get_currency() ) ) ); ?></span>
</div>
