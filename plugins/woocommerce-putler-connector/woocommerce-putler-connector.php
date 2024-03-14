<?php
/**
 * Plugin Name: Analytics for Woo – Putler Accurate Analytics and Reports for your WooCommerce Store
 * Plugin URI: https://putler.com/connector/woocommerce/
 * Description: Accurate reports, analytics, integrations, growth insights and tools for your WooCommerce store.
 * Version: 2.13.0
 * Author: putler, storeapps
 * Author URI: https://putler.com/
 * Text Domain: woocommerce-putler-connector
 * Domain Path: /languages/
 * Requires at least: 4.8.0
 * Tested up to: 6.4.2
 * Requires PHP: 5.6+
 * WC requires at least: 3.0.0
 * WC tested up to: 8.1.1
 * Copyright (c) 2006 - 2024 Putler. All rights reserved.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package woocommerce-putler-connector
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPC_VERSION', '2.13.0' );

// Hooks.
register_activation_hook( __FILE__, 'wpc_activate' );
add_action( 'plugins_loaded', 'wpc_load' );


/**
 * Registers a plugin function to be run when the plugin is activated.
 */
function wpc_activate() {
	// Redirect to WPC.
	update_option( '_wpc_activation_redirect', 'pending' );
}

$wpc_notice_msg = '';

/**
 * Function to load the plugin.
 */
function wpc_load() {

	global $wpc_notice_msg;

	$active_plugins = (array) get_option( 'active_plugins', array() );

	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}

	add_action( 'admin_notices', 'wpc_admin_notices' );

	if ( ( ! in_array( 'easy-digital-downloads-putler-connector/edd-putler-connector.php', $active_plugins, true ) && ! array_key_exists( 'easy-digital-downloads-putler-connector/edd-putler-connector.php', $active_plugins ) )
			&& ( ! in_array( 'jigoshop-putler-connector/jigoshop-putler-connector.php', $active_plugins, true ) && ! array_key_exists( 'jigoshop-putler-connector/jigoshop-putler-connector.php', $active_plugins ) )
			&& ( ! in_array( 'wp-e-commerce-putler-connector/wpec-putler-connector.php', $active_plugins, true ) && ! array_key_exists( 'wp-e-commerce-putler-connector/wpec-putler-connector.php', $active_plugins ) ) ) {

		if ( in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ) {

			if ( ! defined( 'PUTLER_GATEWAY' ) ) {
				define( 'PUTLER_GATEWAY', 'WooCommerce' );
			}

			if ( ! defined( 'PUTLER_GATEWAY_PREFIX' ) ) {
				define( 'PUTLER_GATEWAY_PREFIX', 'wpc' );
			}

			include_once 'classes/class-putler-connector.php';
			$GLOBALS['putler_connector'] = Putler_Connector::get_instance();
			include_once 'classes/class-woocommerce-putler-connector.php';
			if ( ! isset( $GLOBALS['woocommerce_putler_connector'] ) ) {
				$GLOBALS['woocommerce_putler_connector'] = new WooCommerce_Putler_Connector();
			}

			add_action( 'admin_init', 'wpc_init' );
		} else {
			$wpc_notice_msg = '<div id="notice" class="error"><p>' .
								'<b>' . __( 'Putler Connector for WooCommerce', 'woocommerce-putler-connector' ) . '</b> ' . __( 'add-on requires', 'woocommerce-putler-connector' ) . ' <a href="https://wordpress.org/plugins/woocommerce/">' . __( 'WooCommerce', 'woocommerce-putler-connector' ) . '</a> ' . __( 'plugin. Please install and activate it.', 'woocommerce-putler-connector' ) .
								'</p></div>';
		}
	} else {
		$wpc_notice_msg = '<div id="notice" class="error"><p>' .
							__( 'Any one of the Putler Connector\'s can be active at any given time. Please <b>deactivate all the other Putler Connector\'s.</b>', 'woocommerce-putler-connector' ) . '</b> ' .
							'</p></div>';
	}
}

/**
 * Function to show admin notices.
 */
function wpc_admin_notices() {

	global $wpc_notice_msg;

	if ( ! empty( $wpc_notice_msg ) ) {
		echo wp_kses_post( $wpc_notice_msg );
	}

}

/**
 * Function to initialize.
 */
function wpc_init() {
	// Init admin menu for settings etc if we are in admin.
	if ( is_admin() ) {

		if ( false === get_option( '_wpc_update_redirect_2911' ) && 'pending' !== get_option( '_wpc_activation_redirect' ) ) {

			// code for detecting if the store has any custom status.
			$default_order_statuses = array(
				'wc-pending'    => _x( 'Pending payment', 'Order status', 'woocommerce' ),
				'wc-processing' => _x( 'Processing', 'Order status', 'woocommerce' ),
				'wc-on-hold'    => _x( 'On hold', 'Order status', 'woocommerce' ),
				'wc-completed'  => _x( 'Completed', 'Order status', 'woocommerce' ),
				'wc-cancelled'  => _x( 'Cancelled', 'Order status', 'woocommerce' ),
				'wc-refunded'   => _x( 'Refunded', 'Order status', 'woocommerce' ),
				'wc-failed'     => _x( 'Failed', 'Order status', 'woocommerce' ),
			);

			$all_order_statuses  = ( function_exists( 'wc_get_order_statuses' ) ) ? wc_get_order_statuses() : $default_order_statuses;
			$custom_order_status = array_diff( $all_order_statuses, $default_order_statuses );

			delete_option( '_wpc_update_redirect' );

			if ( ! empty( $custom_order_status ) || false === get_option( '_wpc_update_redirect_2911' ) ) {
				update_option( '_wpc_activation_redirect', 'pending' );
				update_option( '_' . PUTLER_GATEWAY_PREFIX . '_delete_and_resync', 1, 'no' ); // for enabling resync on update.
				update_option( '_wpc_update_redirect_2911', 1 ); // flag for redirecting on update.
			}
		}

		if ( false !== get_option( '_wpc_activation_redirect' ) && ( current_user_can( 'import' ) === true ) ) {
			// Delete the redirect transient.
			delete_option( '_wpc_activation_redirect' );
			wp_safe_redirect( admin_url( 'tools.php?page=putler_connector&action=wpc_activate' ) );
			exit;
		}
	}
}
