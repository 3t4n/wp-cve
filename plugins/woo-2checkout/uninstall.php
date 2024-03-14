<?php
/**
 * Uninstall Plugin
 *
 * Deletes all plugin settings.
 *
 * @package    StorePress/TwoCheckoutPaymentGateway
 * @since      1.0.0
 */

namespace StorePress\TwoCheckoutPaymentGateway;

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'woocommerce_woo-2checkout_settings' );
