<?php
/**
 * Plugin Name: SumUp Payment Gateway For WooCommerce
 * Plugin URI: https://wordpress.org/plugins/sumup-payment-gateway-for-woocommerce/
 * Description: Take credit card payments on your store using SumUp.
 * Author: SumUp
 * Author URI: https://sumup.com
 * Version: 2.4.2
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WC_SUMUP_MAIN_FILE', __FILE__ );
define( 'WC_SUMUP_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WC_SUMUP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_SUMUP_VERSION', '2.4.2' );
define( 'WC_SUMUP_MINIMUM_PHP_VERSION', '7.2' );
define( 'WC_SUMUP_MINIMUM_WP_VERSION', '5.0' );
define( 'WC_SUMUP_PLUGIN_SLUG', 'sumup-payment-gateway-for-woocommerce' );

/**
 * Check PHP and WP version before start anything.
 *
 * @since 2.0
 */
if ( ! version_compare( PHP_VERSION, WC_SUMUP_MINIMUM_PHP_VERSION, '>=' ) ) {
	add_action( 'admin_notices', 'sumup_payment_admin_notice_php_version_fail' );
	return;
}

if ( ! version_compare( get_bloginfo( 'version' ), WC_SUMUP_MINIMUM_WP_VERSION, '>=' ) ) {
	add_action( 'admin_notices', 'sumup_payment_admin_notice_wp_version_fail' );
	return;
}

/**
 * Initialize the SumUp Gateway plugin.
 *
 * @since    1.0.0
 * @version  1.0.0
 */
function sumup_payment_gateway_for_woocommerce_init() {
	/**
	 * Display links next to the plugin's version.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	function plugin_row_meta( $links, $file ) {
		if ( plugin_basename( __FILE__ ) === $file ) {
			$row_meta = array(
				'docs'    => '<a href="https://developer.sumup.com">' . esc_html__( 'Docs', 'sumup-payment-gateway-for-woocommerce' ) . '</a>',
			);
			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}

	add_filter( 'plugin_row_meta', 'plugin_row_meta', 10, 2 );

	/**
	 * Display admin notice when WooCommerce is not installed.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	if ( ! class_exists( 'WooCommerce' ) ) {
		function sumup_missing_wc_notice() {
			echo '<div class="notice notice-error"><p><strong>' . sprintf( esc_html__( 'SumUp requires WooCommerce to be installed and active. You can download %s here.', 'sumup-payment-gateway-for-woocommerce' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
		}
		add_action( 'admin_notices', 'sumup_missing_wc_notice' );
		return;
	}

	/**
	 * Get plugin's setting page URL.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	function get_sumup_gateway_setup_link() {
		return admin_url( 'admin.php?page=wc-settings&tab=checkout&section=sumup' );
	}

	/**
	 * Display admin notice if the plugin is not configured successfully.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	function plugin_not_configured_notice() {
		$plugin_options          = get_option( 'woocommerce_sumup_settings' );
		$plugin_enabled          = isset( $plugin_options['enabled'] ) && 'yes' === $plugin_options['enabled'];
		$is_plugin_configured    = get_option( 'sumup_valid_credentials', 'not_configured' );
		$is_plugin_settings_page = !empty( $_GET['page'] ) && $_GET['page'] === 'wc-settings' && !empty( $_GET['tab'] ) && $_GET['tab'] === 'checkout' && !empty( $_GET['section'] ) && $_GET['section'] === 'sumup';

		if ( $is_plugin_configured === 'not_configured' ) {
			/* translators: %s = admin.php?page=wc-settings&tab=checkout&section=sumup */
			echo '<div class="notice notice-warning"><p><strong>' . sprintf( __( 'SumUp Gateway is almost ready. To get started, <a href="%s">set your SumUp account keys</a>.', 'sumup-payment-gateway-for-woocommerce' ), get_sumup_gateway_setup_link() ) . '</strong></p></div>';

			return; /* don't display other notices about configurations */
		}

		if ( $plugin_enabled && !$is_plugin_configured && !$is_plugin_settings_page ) {
			/* translators: %s = admin.php?page=wc-settings&tab=checkout&section=sumup */
			echo '<div class="notice notice-error"><p><strong>' . sprintf( __( 'SumUp Gateway is not configured properly. You can fix this from <a href="%s">here</a>.', 'sumup-payment-gateway-for-woocommerce' ), get_sumup_gateway_setup_link() ) . '</strong></p></div>';
		}

		if ( $plugin_enabled && $is_plugin_configured && get_woocommerce_currency() !== $plugin_options['currency'] ) {
			echo '<div class="notice notice-warning"><p><strong>' . __( 'SumUp Gateway needs your attention. Currency is different from WooCommerce currency (WooCommerce->Settings->General->Currency).', 'sumup-payment-gateway-for-woocommerce' ) . '</strong></p></div>';
		}

		if ( isset( $plugin_options['merchant_id'] ) && empty( $plugin_options['merchant_id'] ) ) {
			$message = sprintf(
				'<div class="notice notice-error"><p>%1$s</p></div>',
				__( 'SumUp Gateway is not configured properly. Please fill the field Merchant ID.', 'sumup-payment-gateway-for-woocommerce' )
			);

			echo wp_kses_post( $message );
		}

		return;
	}

	add_action( 'admin_notices', 'plugin_not_configured_notice' );

	/**
	 * Display links beneath the plugin's name
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 */
	function plugin_action_links( $links ) {
		$plugin_links = array(
			'<a href="' . get_sumup_gateway_setup_link() . '">' . esc_html__( 'Settings', 'sumup-payment-gateway-for-woocommerce' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}

	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'plugin_action_links' );

	include_once( 'includes/class-wc-sumup-logger.php' );
	include_once( 'includes/class-wc-sumup-gateway.php' );

	function add_gateways( $methods ) {
		$methods[] = 'WC_Gateway_Sumup';

		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_gateways' );
}

add_action( 'plugins_loaded', 'sumup_payment_gateway_for_woocommerce_init' );

/**
 * Admin notice to PHP check version fail
 *
 * @since 2.0
 * @return void
 */
function sumup_payment_admin_notice_php_version_fail() {
	$message = sprintf(
		esc_html__( '%1$s requires PHP version %2$s or greater.', 'sumup-payment-gateway-for-woocommerce' ),
		'<strong>SumUp Payment Gateway For WooCommerce</strong>',
		WC_SUMUP_MINIMUM_PHP_VERSION
	);

	$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );

	echo wp_kses_post( $html_message );
}

/**
 * Admin notice to WP version check fail
 *
 * @since 2.0
 * @return void
 */
function sumup_payment_admin_notice_wp_version_fail() {
	$message = sprintf(
		esc_html__( '%1$s requires WordPress version %2$s or greater.', 'sumup-payment-gateway-for-woocommerce' ),
		'<strong>SumUp Payment Gateway For WooCommerce</strong>',
		WC_SUMUP_MINIMUM_WP_VERSION
	);

	$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );

	echo wp_kses_post( $html_message );
}
