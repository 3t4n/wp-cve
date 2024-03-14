<?php
/**
 * Declare any actions and filters here.
 * In most cases you should use a service provider, but in cases where you
 * just need to add an action/filter and forget about it you can add it here.
 *
 * @package WcGetnet
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Options
 * helpers/options.php
 */
add_filter( 'plugin_action_links_wc-checkout-getnet/wc-checkout-getnet.php', 'plugin_links' );
add_filter( 'plugin_row_meta', 'support_links', 10, 4 );

/**
 * Settings
 * helpers/settings.php
 */
add_action( 'admin_init', ['\WcGetnet\WooCommerce\GateWays\AdminSettingsFields\Settings', 'getnet_add_settings_fields'], 10 );
add_action( 'admin_init', ['\WcGetnet\WooCommerce\GateWays\AdminSettingsFields\Settings', 'getnet_register_settings_fields'], 10 );
add_filter( 'woocommerce_screen_ids', ['\WcGetnet\WooCommerce\GateWays\AdminSettingsFields\Settings', 'getnet_set_wc_screen_ids'] );
add_filter( 'admin_menu', ['\WcGetnet\WooCommerce\GateWays\WcGetnet_Settings', 'getnet_admin_options'], 100 );
add_action( 'all_admin_notices', ['\WcGetnet\WooCommerce\GateWays\AdminSettingsFields\Settings', 'getnet_admin_notice'], 100 );
add_action( 'wp_ajax_save_privacy_policy_meta_accept', ['\WcGetnet\WooCommerce\GateWays\AdminSettingsFields\Privacy_Policy', 'save_privacy_policy_meta_accept'] );
add_action( 'wp_ajax_nopriv_save_privacy_policy_meta_accept', ['\WcGetnet\WooCommerce\GateWays\AdminSettingsFields\Privacy_Policy', 'save_privacy_policy_meta_accept'] );

/**
 * Admin Notices
 * helpers/notices.php
 */
add_action( 'admin_init', 'add_admin_notices' );

/**
 * Discounts
 * helpers/billet-discount.php
 */
add_action( 'woocommerce_cart_calculate_fees', 'add_payment_discount', 30 );
