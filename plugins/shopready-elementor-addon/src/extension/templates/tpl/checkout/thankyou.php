<?php
/**
 * Thankyou page
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order woo-ready-thanks-orders">

    <?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );

		?>

    <?php if ( $order->has_status( 'failed' ) ) : ?>
    <?php
			$fail_default     = wc_get_page_permalink( 'myaccount' );
			$fail_custom_path = shop_ready_gl_get_setting( 'woo_ready_thankyou_fail_redirect_url' );

			if ( isset( $fail_custom_path['url'] ) && $fail_custom_path['url'] != '' ) {

				$fail_default = $fail_custom_path['url'];

			}
			?>
    <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
        <?php echo esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_order_fail_msg' ) ) == '' ? esc_html__( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'shopready-elementor-addon' ) : esc_html(shop_ready_gl_get_setting( 'woo_ready_thank_you_order_fail_msg' )); ?>
    </p>

    <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
        <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
            class="button pay"><?php echo esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_order_pay_text' ) == '' ? esc_html__( 'Pay', 'shopready-elementor-addon' ) : esc_html(shop_ready_gl_get_setting( 'woo_ready_thank_you_order_pay_text' ) )); ?></a>
        <?php if ( shop_ready_gl_get_setting( 'woo_ready_enable_thankyou_fail_myaccount', 'yes' ) == 'yes' && is_user_logged_in() ) : ?>
        <a href="<?php echo esc_url( $fail_default ); ?>"
            class="button pay"><?php echo shop_ready_gl_get_setting( 'woo_ready_thank_you_order_fail_myaccount_text' ) == '' ? esc_html__( 'My Account', 'shopready-elementor-addon' ) : esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_order_fail_myaccount_text' ) ); ?></a>
        <?php endif; ?>
    </p>

    <?php else : ?>

    <?php if ( shop_ready_gl_get_setting( 'woo_ready_enable_thankyou_msg', 'yes' ) == 'yes' ) : ?>
    <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
        <?php echo esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_msg', 'Thank you. Your order has been received.' ) ); ?>
    </p>
    <?php endif; ?>

    <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
        <?php if ( shop_ready_gl_get_setting( 'woo_ready_enable_thankyou_order_number' ) == 'yes' ) : ?>
        <li class="woocommerce-order-overview__order order">
            <?php echo esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_order_number', 'Order Number:' ) ); ?>
            <strong>
                <?php echo wp_kses_post( $order->get_order_number() ); ?>
            </strong>
        </li>
        <?php endif; ?>
        <?php if ( shop_ready_gl_get_setting( 'woo_ready_enable_thankyou_date' ) == 'yes' ) : ?>
        <li class="woocommerce-order-overview__date date">
            <?php echo esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_order_date', 'Date:' ) ); ?>
            <strong>
                <?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </strong>
        </li>
        <?php endif; ?>
        <?php if ( shop_ready_gl_get_setting( 'woo_ready_enable_thankyou_email', 'yes' ) == 'yes' && is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
        <li class="woocommerce-order-overview__email email">
            <?php echo esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_order_email', 'Email:' ) ); ?>
            <strong>
                <?php echo esc_html( $order->get_billing_email() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </strong>
        </li>
        <?php endif; ?>

        <?php if ( shop_ready_gl_get_setting( 'woo_ready_enable_thankyou_order_total' ) == 'yes' ) : ?>
        <li class="woocommerce-order-overview__total total">
            <?php echo esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_order_total', 'Total:' ) ); ?>
            <strong>
                <?php echo wp_kses_post( $order->get_formatted_order_total() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </strong>
        </li>
        <?php endif; ?>
        <?php if ( shop_ready_gl_get_setting( 'woo_ready_enable_thankyou_payment_method', 'yes' ) == 'yes' && $order->get_payment_method_title() ) : ?>
        <li class="woocommerce-order-overview__payment-method method">
            <?php echo esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_order_payment_method', 'Payment method:' ) ); ?>
            <strong>
                <?php echo wp_kses_post( $order->get_payment_method_title() ); ?>
            </strong>
        </li>
        <?php endif; ?>

    </ul>

    <?php endif; ?>

    <?php if ( shop_ready_gl_get_setting( 'woo_ready_enable_thankyou_order_details', 'yes' ) == 'yes' ) : ?>
    <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
    <?php endif; ?>

    <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>


    <?php else : ?>

    <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
        <?php echo esc_html( shop_ready_gl_get_setting( 'woo_ready_thank_you_msg', 'Thank you. Your order has been received.' ) ); ?>
    </p>

    <?php endif; ?>

</div>