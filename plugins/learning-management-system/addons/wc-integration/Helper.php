<?php
/**
 * WC Integration helper functions.
 *
 * @since 1.8.1
 * @package Masteriyo\Addons\WcIntegration
 */

namespace Masteriyo\Addons\WcIntegration;

class Helper {
	/**
	 * Return if WooCommerce is active.
	 *
	 * @since 1.8.1
	 *
	 * @return boolean
	 */
	public static function is_wc_active() {
		return in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins', array() ), true );
	}

	/**
	 * Return if WooCommerce Subscriptions is active.
	 *
	 * @since 1.8.1
	 * @return boolean
	 */
	public static function is_wc_subscriptions_active() {
		return in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', get_option( 'active_plugins', array() ), true );
	}
}
