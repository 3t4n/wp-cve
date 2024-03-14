<?php

/**
 * Plugin Name: Currency Switcher for WooCommerce 
 * Plugin URI:  #
 * Description: Currency Switcher for WooCommerce.
 * Version:     0.0.7
 * Author:      PressMaximum
 * Author URI:  http://pressmaximum.com/
 * Text Domain: pmcs
 * Domain Path: /languages
 * License:     GPL-2.0+
 * WC requires at least: 5.0
 * WC tested up to: 5.9.3
 * Requires at least: 5.1
 * Requires PHP: 7.0
 */

/**
 * Copyright (c) 2018 PressMaximum (email : PressMaximum@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */



if (!class_exists('PMCS_Plugin')) {

	// Useful global constants.
	define('PMCS_URL', plugin_dir_url(__FILE__));
	define('PMCS_PATH', dirname(__FILE__) . '/');
	define('PMCS_INC', PMCS_PATH . 'includes/');

	define('PMCS_PLUGIN_FILE', __FILE__);
	define('PMCS_PRO_URL', 'https://pressmaximum.com/pressmerce/currency-switcher-for-woocommerce-pro-version/');


	// Include files.
	require_once PMCS_INC . 'class-pmcs-plugin.php';

	/**
	 * Main instance of PM_Currency_Swicther.
	 *
	 * Returns the main instance of PM_Currency_Swicther to prevent the need to use globals.
	 *
	 * @since  0.0.1
	 * @return PMCS_Plugin
	 */
	function pmcs()
	{
		return PMCS_Plugin::instance();
	}
}

function pmcs__init()
{
	$GLOBALS['pmcs'] = pmcs();
}

add_action('woocommerce_loaded', 'pmcs__init', 2);


if (!function_exists('pmcs_activation_redirect')) {
	function pmcs_activation_redirect($plugin)
	{
		if (plugin_basename(__FILE__) == $plugin) {
			exit(wp_redirect(admin_url('admin.php?page=pm_currency_switcher')));
		}
	}
}

add_action('activated_plugin', 'pmcs_activation_redirect');
