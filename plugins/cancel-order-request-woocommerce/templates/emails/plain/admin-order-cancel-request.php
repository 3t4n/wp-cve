<?php
/**
 * Admin new order email (plain text)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails/Plain
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/* translators: %s: Customer billing full name */

if(!empty($predefined_reason)):
echo sprintf( esc_html__( 'Selected reason: %s', 'cancel-order-request-woocommerce' ), esc_html( $predefined_reason ) ) . "\n\n";
endif;

if(!empty($cancellation_reason)):
echo sprintf( esc_html__( 'Order cancellation reason: %s', 'cancel-order-request-woocommerce' ), esc_html( $cancellation_reason ) ) . "\n\n";
endif;



echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );