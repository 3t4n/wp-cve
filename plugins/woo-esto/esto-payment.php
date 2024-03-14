<?php
/*
  Plugin Name: Woocommerce ESTO
  Plugin URI:  https://www.esto.ee
  Description: Adds ESTO redirect link to a Woocommerce instance
  Version:     2.24.2
  Author:      Mikk Mihkel Nurges, Rebing OÜ
  Author URI:  www.rebing.ee
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Text Domain: woo-esto

  WC tested up to: 8.3.1

  Woocommerce ESTO is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 2 of the License, or
  any later version.

  Woocommerce ESTO is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.
*/

/**
 * ESTO payment module
 *
 * @author Mikk Mihkel Nurges
 */
if( ! defined('ABSPATH')) {
	exit;
}

global $esto_plugin_url, $esto_plugin_dir;
$esto_plugin_dir = dirname(__FILE__) . "/";
$esto_plugin_url = plugins_url() . "/" . basename($esto_plugin_dir) . "/";

if ( ! defined( 'WOO_ESTO_API_URL_EE' ) ) {
	define( 'WOO_ESTO_API_URL_EE', 'https://api.esto.ee/' );
}

if ( ! defined( 'WOO_ESTO_API_URL_LT' ) ) {
	define( 'WOO_ESTO_API_URL_LT', 'https://api.estopay.lt/' );
}

if ( ! defined( 'WOO_ESTO_API_URL_LV' ) ) {
    define( 'WOO_ESTO_API_URL_LV', 'https://api.esto.lv/' );
}

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
	|| is_plugin_active_for_network( 'woocommerce/woocommerce.php' )
	|| class_exists('WooCommerce')
	) {
	add_action('plugins_loaded', 'init_woocommerce_esto_payment');

	add_action( 'init', function() {
		load_plugin_textdomain( 'woo-esto', false, dirname(plugin_basename(__FILE__)) . '/assets/i18n');
	} );

	if( ! class_exists('WC_Esto_Payment')) {
		require_once('includes/Payment.php');
	}

	if( ! class_exists('WC_Esto_Calculator')) {
		require_once('includes/Calculator.php');
	}
	// use this global to add/remove hook actions
	global $esto;
	$esto = new WC_Esto_Calculator();
	add_shortcode('esto_monthly_payment', [$esto, 'display_calculator']);

	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'esto_add_action_links');

	add_filter( 'woocommerce_cancel_unpaid_order', 'esto_prevent_cancelling_orders_early', 10, 2 );
}

function esto_add_action_links($links) {
	$plugin_links = array(
		'<a href="' . admin_url('admin.php?page=esto-calculator-settings') . '">' . __('Settings', 'woo-esto') . '</a>',
	);
	return array_merge($links, $plugin_links);
}

function esto_get_api_url_from_options() {

	if ( defined( 'WOO_ESTO_API_URL_DEV' ) ) {
		return WOO_ESTO_API_URL_DEV;
	}

	$payment_settings = get_option( 'woocommerce_esto_settings', null );

	if ( $payment_settings && ! empty( $payment_settings['endpoint'] ) ) {
		return $payment_settings['endpoint'];
	}

	return false;
}

function esto_get_api_url( $country = '' ) {
	if ( ! $country ) {
		$country = esto_get_country();
	}

	$country = strtoupper( $country );

	if ( $country == 'LT' && esto_api_config_is_active_for_country( 'lt' ) ) {
		return WOO_ESTO_API_URL_LT;
	}
	elseif ( $country == 'LV' && esto_api_config_is_active_for_country( 'lv' ) ) {
		return WOO_ESTO_API_URL_LV;
	}
	elseif ( $country == 'EE' && esto_api_config_is_active_for_country( 'ee' ) ) {
		return WOO_ESTO_API_URL_EE;
	}
	else {
		$selected_endpoint_url = esto_get_api_url_from_options();
		return $selected_endpoint_url ? $selected_endpoint_url : WOO_ESTO_API_URL_EE;
	}
}

function esto_api_config_is_active_for_country( $country ) {
	$payment_settings = get_option( 'woocommerce_esto_settings', null );

	if ( $payment_settings
		&& ! empty( $payment_settings['use_secondary_endpoint_' . $country] )
		&& $payment_settings['use_secondary_endpoint_' . $country] == 'yes'
		&& ! empty( $payment_settings['shop_id_' . $country] )
		&& ! empty( $payment_settings['secret_key_' . $country] )
	) {
		return true;
	}

	return false;
}

function esto_get_country() {
	if ( isset( $_REQUEST['esto_api_country_code'] ) ) {
		// this is for data returning from API
		if ( in_array( $_REQUEST['esto_api_country_code'], ['ee', 'lv', 'lt'] ) ) {
			return $_REQUEST['esto_api_country_code'];
		}
		else {
			return '';
		}
	}

	$country = '';

	if ( function_exists( 'WC' ) ) {
		$customer = WC()->customer;

		if ( $customer ) {
			if ( method_exists( WC()->customer, 'get_billing_country' ) ) {
				$country = WC()->customer->get_billing_country();
			}
			else {
				$country = WC()->customer->get_country();
			}
		}
	}

	return strtolower( $country );
}

// field can be 'shop_id' or 'secret_key'
function esto_get_api_field( $field ) {
	$country = esto_get_country();
	$payment_settings = get_option( 'woocommerce_esto_settings', null );

	if ( $payment_settings && $country && esto_api_config_is_active_for_country( $country ) ) {
		return $payment_settings[ $field . '_' . $country ];
	}
	else {
		return $payment_settings[ $field ];
	}

	return false;
}

function woo_esto_log( $message, $level = 'info' ) {
	if ( function_exists( 'wc_get_logger' ) ) {
		$logger = wc_get_logger();
		if ( method_exists( $logger, 'log' ) ) {
			$logger->log( $level, $message, array( 'source' => 'woo-esto' ) );
			return;
		}
	}

	// fallback
	error_log( $message );
}

function esto_prevent_cancelling_orders_early( $can_cancel, $order ) {
	$payment_method = $order->get_payment_method();
	if ( $payment_method == 'esto_pay' ) {
		$order_date = $order->get_date_modified();
		if ( $order_date && ( $order_date->getTimestamp() + DAY_IN_SECONDS ) > time() ) {
			$can_cancel = false;
		}
	}
	else if ( in_array( $payment_method, ['esto', 'esto_x', 'pay_later'] ) ) {
		$order_date = $order->get_date_modified();
		if ( $order_date && ( $order_date->getTimestamp() + 3 * DAY_IN_SECONDS ) > time() ) {
			$can_cancel = false;
		}
	}

	return $can_cancel;
}

// replicates woocommerce function for early loading
function esto_get_countries() {
	$countries = apply_filters( 'woocommerce_countries', include WC()->plugin_path() . '/i18n/countries.php' );
	if ( apply_filters( 'woocommerce_sort_countries', true ) && function_exists( 'wc_asort_by_locale' ) ) {
		wc_asort_by_locale( $countries );
	}
	return $countries;
}

// WooCommerce Google Analytics Integration compatibility
add_filter( 'woocommerce_get_return_url', 'esto_remove_utm_nooverride', 20 );
function esto_remove_utm_nooverride( $return_url ) {
	return remove_query_arg( 'utm_nooverride', $return_url );
}

if ( ! empty( $_REQUEST['esto_auto_callback'] ) ) {
	add_filter( 'woocommerce_ga_disable_tracking', '__return_true' );
}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
