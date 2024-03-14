<?php
/**
 * Plugin Name: DHL Express Live Rates
 * Plugin URI: https://wordpress.org/plugins/flexible-shipping-dhl-express/
 * Description: DHL Express shipping methods with real-time calculated shipping rates based on the established DHL Express API connection.
 * Version: 3.0.9
 * Author: Octolize
 * Author URI: https://octol.io/dhlexpress-author/
 * Text Domain: flexible-shipping-dhl-express
 * Domain Path: /lang/
 * Requires at least: 5.8
 * Tested up to: 6.4
 * WC requires at least: 8.3
 * WC tested up to: 8.7
 * Requires PHP: 7.4
 *
 * Copyright 2019 WP Desk Ltd.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package WPDesk\FlexibleShippingDhl
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* THIS VARIABLE CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '3.0.9';

$plugin_name        = 'DHL Express for WooCommerce';
$plugin_class_name  = '\WPDesk\FlexibleShippingDhl\Plugin';
$plugin_text_domain = 'flexible-shipping-dhl-express';
$product_id         = 'Flexible Shipping DHL Express';
$plugin_file        = __FILE__;
$plugin_dir         = __DIR__;

define( $plugin_class_name, $plugin_version );
define( 'FLEXIBLE_SHIPPING_DHL_EXPRESS_VERSION', $plugin_version );

$requirements = array(
	'php'          => '7.4',
	'wp'           => '4.9',
	'repo_plugins' => array(
		array(
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '3.8',
		),
	),
);

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';

require_once __DIR__ . '/vendor_prefixed/guzzlehttp/guzzle/src/functions_include.php';
