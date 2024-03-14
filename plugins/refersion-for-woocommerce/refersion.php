<?php
/***************************************************************************
 * Plugin Name: Refersion for WooCommerce
 * Plugin URI: https://www.refersion.com
 * Description: Integrates <a href="https://www.refersion.com">Refersion</a> tracking with your WooCommerce store.
 * Version: 4.10.0
 * Author: Refersion, Inc.
 * Author URI: https://www.refersion.com
 * Text Domain: Refersion.com
 * License: GPL3
 ***************************************************************************/

/*

Copyright 2017 Refersion, Inc. (email : helpme@refersion.com)

This file is part of Refersion for WooCommerce.

Refersion for WooCommerce is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Refersion for WooCommerce is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Refersion for WooCommerce. If not, see <http://www.gnu.org/licenses/>.

*/

defined('ABSPATH') or die("No direct access allowed!");

define('REFERSION_VERSION', '4.10.0');
define('REFERSION__MINIMUM_WP_VERSION', '4.4');
define('REFERSION__PLUGIN_URL', plugin_dir_url(__FILE__));
define('REFERSION__PLUGIN_DIR', plugin_dir_path(__FILE__));

global $table_prefix;
define('REFERSION_WC_ORDERS_TABLE', $table_prefix . 'refersion_cart_tracking');
define('REFERSION_CART_ID_LENGTH', 40);

if (!function_exists('add_action')) {
	echo 'No direct access allowed!';
	exit;
}

require_once(REFERSION__PLUGIN_DIR . 'class.refersion.php');

// Hook to check if Woocomerce already installed or not
register_activation_hook(__FILE__, array('Refersion', 'check_woocomerce'));

// Hook to create the Refersion DB table (if not already existing)
register_activation_hook(__FILE__, array('Refersion', 'refersion_activation_db'));

// Add Settings link to Plugins page
if (is_admin()) {

	require_once(REFERSION__PLUGIN_DIR . 'class.refersion-admin.php');

	$refersion_settings_page = new Refersion_Admin();

	$plugin = plugin_basename(__FILE__);
	add_filter("plugin_action_links_$plugin", array('Refersion_Admin', 'add_plugins_settings'));

}

// Do not run in admin
if (!is_admin()) {

	// Hook to call Refersion JS code to run on every page
	add_action('wp_enqueue_scripts', array('Refersion', 'refersion_wp_enqueue_scripts'), 0);

	// Hook to run Refersion tracking
	add_action('wp_footer', array('Refersion', 'refersion_footer'), PHP_INT_MAX);

}

// Hook to generate a cart_id and start it in the DB
add_action('woocommerce_new_order', array('Refersion', 'refersion_woocommerce_new_order'), 100);

// Hook to call Refersion JS code on Woocommerce checkout page
add_action('woocommerce_thankyou', array('Refersion', 'refersion_woocommerce_thankyou'), 1000);

// Hook to add Refersion's post Purchase code to the Woocommerce thank you page
add_action('woocommerce_thankyou', array('Refersion', 'refersion_woocommerce_post_purchase'), 1200);

// Hook to call send order data to Refersion
add_action('woocommerce_order_status_completed', array('Refersion', 'refersion_woocommerce_order_status_completed'));
add_action('woocommerce_order_status_processing', array('Refersion', 'refersion_woocommerce_order_status_processing'));

// Hook to send order cancellation data to Refersion
add_action('woocommerce_order_status_cancelled', array('Refersion', 'refersion_woocommerce_order_status_cancelled'));

// Hook to check if Refersion configurations has been set or not
add_action('admin_notices', array('Refersion_Admin', 'activation_message'), PHP_INT_MAX);

// Adds optional styling to Refersion's admin settings page
add_action('admin_enqueue_scripts', array('Refersion_Admin', 'add_refersion_admin_css'));