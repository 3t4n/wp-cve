<?php
/**
 * Advanced Gift Cards email (plain text).
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-advanced-gift-card.php.
 *
 * @version 1.0.1
 */

defined( 'ABSPATH' ) || exit;

$coupon_url = 'yes' !== $coupon->get_advanced_prop( 'disable_url_coupon' ) ? $coupon->get_coupon_url() : get_permalink( wc_get_page_id( 'shop' ) );

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html( $email->get_message() ) . "\n\n";

echo esc_html__( 'Coupon code:', 'advanced-coupons-for-woocommerce-free' ) . ' ' . esc_html( $coupon->get_code() );
echo "\n\n----------------------------------------\n\n";

echo esc_html( sprintf( '%s: %s', $email->get_button_text(), $coupon_url ) );
echo "\n\n----------------------------------------\n\n";

if ( $additional_content ) {
    echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
    echo "\n\n----------------------------------------\n\n";
}

if ( ! apply_filters( 'acfw_use_woocommerce_email_footer', false ) ) {
    esc_html_e( 'Powered by', 'advanced-coupons-for-woocommerce-free' );
    echo ' Advanced Coupons ';
    echo esc_url_raw( 'https://advancedcouponsplugin.com/powered-by/?utm_source=acfwf&utm_medium=sendcouponemail&utm_campaign=sendcouponpoweredby' );
} else {
    echo wp_kses_post( apply_filters( 'acfw_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
}
