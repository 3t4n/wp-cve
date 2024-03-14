<?php
/**
 * Order Customer Billing Details
 *
 */

defined( 'ABSPATH' ) || exit;


?>

<address class="woo-ready-billing-address">
    <?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'shopready-elementor-addon' ) )); ?>

    <?php if ( $order->get_billing_phone() ) : ?>
    <p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
    <?php endif; ?>

    <?php if ( $order->get_billing_email() ) : ?>
    <p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
    <?php endif; ?>
</address>