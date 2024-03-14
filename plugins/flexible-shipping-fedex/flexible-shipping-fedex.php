<?php
/**
 * Plugin Name: FedEx Live Rates
 * Plugin URI: https://wordpress.org/plugins/flexible-shipping-fedex/
 * Description: FedEx WooCommerce shipping methods with real-time calculated shipping rates based on the established FedEx API connection.
 * Version: 2.7.5
 * Author: Octolize
 * Author URI: https://octol.io/fedex-author
 * Text Domain: flexible-shipping-fedex
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
 * @package WPDesk\FlexibleShippingFedex
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* THIS VARIABLE CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '2.7.5';

$plugin_name        = 'Flexible Shipping FedEx';
$plugin_class_name  = '\WPDesk\FlexibleShippingFedex\Plugin';
$plugin_text_domain = 'flexible-shipping-fedex';
$product_id         = 'Flexible Shipping FedEx';
$plugin_file        = __FILE__;
$plugin_dir         = __DIR__;

define( 'FLEXIBLE_SHIPPING_FEDEX_VERSION', $plugin_version );
define( $plugin_class_name, $plugin_version );

$requirements = array(
	'php'     => '5.6',
	'wp'      => '4.5',
	'plugins' => array(
		array(
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '3.0',
		),
	),
	'modules' => array(
		array(
			'name'      => 'soap',
			'nice_name' => 'SOAP',
		),
	),
);

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';
