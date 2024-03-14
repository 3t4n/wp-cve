<?php
/**
 * Plugin Name: Yektanet Ecommerce
 * Plugin URI: https://www.yektanet.com/
 * Description: Yektanet Ecommerce plugin to integrate with yektanet advertising system
 * Version: 1.1.6
 * Author: Yektanet
 * Author URI: https://www.yektanet.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP: 7.0
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * WC requires at least: 6.1.1
 * WC tested up to: 6.1.1
 * Requires at least: 5.4
 */

/**
 * Yektanet Ecommerce is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Yektanet Ecommerce is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with yektanet ecommerce plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */


defined('ABSPATH') || exit;

/**
 * Check if WooCommerce is active
 **/

if (!defined('YEKTANET_ECOMMERCE_PLUGIN_VERSION')) {
	define('YEKTANET_ECOMMERCE_PLUGIN_VERSION', '1.1.5');
}

require_once('requires.php');

add_action('plugins_loaded', 'yektanet_check_version');
function yektanet_check_version()
{
	if (YEKTANET_ECOMMERCE_PLUGIN_VERSION !== get_option('YEKTANET_ECOMMERCE_PLUGIN_VERSION')) {
		update_option('YEKTANET_ECOMMERCE_PLUGIN_VERSION', YEKTANET_ECOMMERCE_PLUGIN_VERSION);
	}
}

register_activation_hook(__FILE__, function () {
	if (!get_option('yektanet_app_id', true)) {
		update_option('yektanet_app_id', '-');
	}
});

add_action('activated_plugin', function () {
	if (!get_option('active_yektanet_plugin', true)) {
		update_option('active_yektanet_plugin', '1');
		exit(wp_redirect(admin_url() . 'admin.php?page=yektanet-settings.php'));
	}
});