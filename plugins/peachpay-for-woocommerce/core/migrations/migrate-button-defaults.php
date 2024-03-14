<?php
/**
 * Migrate product page display position settings and migrate fade effect
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Migrate product page display position settings.
 */
function peachpay_migrate_button_defaults() {
	if ( ! get_option( 'peachpay_migrate_button_defaults_completed', 0 ) ) {
		// Migrate all button position settings to be after the add to cart button if it is currently inline.
		peachpay_set_settings_option( 'peachpay_express_checkout_button', 'product_button_position', 'after' );

		// Migrate button affect class names from fade/none to effect-fade/effect-none
		$current_button_effect = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect' );
		if ( 'fade' === $current_button_effect ) {
			peachpay_set_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'effect-fade' );
		} elseif ( 'none' === $current_button_effect ) {
			peachpay_set_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'effect-none' );
		} else {
			peachpay_set_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'effect-fade' );
		}

		update_option( 'peachpay_migrate_button_defaults_completed', 1 );
	}
}
