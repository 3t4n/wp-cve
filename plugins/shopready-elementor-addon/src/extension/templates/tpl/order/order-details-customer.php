<?php
/**
 * Order Customer Details
 *
 */

defined( 'ABSPATH' ) || exit;

	$w_title          = shop_ready_gl_get_setting('woo_ready_thankyou_order_details_billing_heading','Billing address');
	$sh_title         = shop_ready_gl_get_setting('woo_ready_enable_thankyou_shipping_heading','yes') == 'yes' ? true : false;
	$sh_title_content = shop_ready_gl_get_setting('woo_ready_thankyou_order_details_shipping_heading','Shipping address');
	$show_shipping    = !wc_ship_to_billing_address_only() && $order->needs_shipping_address();

?>
<div class="woocommerce-customer-details">

    <?php if ( $show_shipping ) : ?>

    <section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
        <div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

            <?php endif; ?>

            <h2 class="woocommerce-column__title"><?php echo esc_html($w_title); ?></h2>

            <address>
                <?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'shopready-elementor-addon' ) ) ); ?>

                <?php if ( $order->get_billing_phone() ) : ?>
                <p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?>
                </p>
                <?php endif; ?>

                <?php if ( $order->get_billing_email() ) : ?>
                <p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?>
                </p>
                <?php endif; ?>
            </address>

            <?php if ( $show_shipping && $sh_title) : ?>

        </div><!-- /.col-1 -->

        <div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
            <h2 class="woocommerce-column__title"><?php echo wp_kses_post($sh_title_content); ?></h2>
            <address>
                <?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'shopready-elementor-addon' ) ) ); ?>

                <?php if ( $order->get_shipping_phone() ) : ?>
                <p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_shipping_phone() ); ?>
                </p>
                <?php endif; ?>

            </address>
        </div><!-- /.col-2 -->

    </section><!-- /.col2-set -->

    <?php endif; ?>

    <?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</div>