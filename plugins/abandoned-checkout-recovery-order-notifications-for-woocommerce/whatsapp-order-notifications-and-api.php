<?php
/**
 * Plugin Name: Abandoned Checkout Recovery & Order Notifications for WooCommerce
 * Description: Send WhatsApp notifications for recovering abandoned carts, double confirming CoD orders and for other order & shipment updates! Also, send out your Woocommerce catalog via bulk WhatsApp campaigns and create chat automations to engage & convert more customers.
 * Version: 1.0.1
 * Author: Interakt
 * Author URI: https://www.interakt.shop/
 * Text Domain: abandoned-checkout-recovery-order-notifications-woocommerce
 * Requires PHP: 7.4
 * Requires at least: 6.0.1
 * Tested up to: 6.1
 * WC requires at least: 6.8.2
 * WC tested up to: 7.0.1
 *
 * @package interakt-add-on-woocommerce
 */

// Define WC_PLUGIN_URL.
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'whatsapp-order-notifications-and-api/whatsapp-order-notifications-and-api.php' ) ) {
	trigger_error( esc_html__( 'WhatsApp Order Notifications and API is installed please deactivate this plugin to activate the other plugin.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ) );
}
/**
 * Set constants.
 */
if ( ! defined( 'INTRKT_FILE' ) ) {
	define( 'INTRKT_FILE', __FILE__ );
}
// Files.
require_once 'includes/class-intrkt-loader.php';


/**
 * Set constants.
 */
if ( ! defined( 'INTRKT_ABANDON_FILE' ) ) {
	define( 'INTRKT_ABANDON_FILE', __FILE__ );
}
/**
 * Loader
 */
require_once 'lib/cart-abandonment-recovery/classes/class-intrkt-abandon-loader.php';



