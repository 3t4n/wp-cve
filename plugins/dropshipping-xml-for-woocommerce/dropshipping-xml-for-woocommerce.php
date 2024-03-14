<?php
/**
 * Plugin Name: Dropshipping XML for WooCommerce
 * Plugin URI: https://www.wpdesk.net/products/dropshipping-xml-woocommerce/
 * Description: Import wholesale products to your store. Synchronize your WooCommerce products with the current offer of your suppliers.
 * Product: Dropshipping XML for WooCommerce
 * Version: 1.5.8
 * Author: WP Desk
 * Author URI: https://www.wpdesk.net/
 * Text Domain: dropshipping-xml-for-woocommerce
 * Domain Path: /lang/
 * Requires at least: 5.8
 * Tested up to: 6.4
 * WC requires at least: 8.3
 * WC tested up to: 8.7
 * Requires PHP: 7.2
 *
 * Copyright 2016 WP Desk Ltd.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* THESE TWO VARIABLES CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '1.5.8';
$plugin_release_timestamp = '2023-11-15 15:31';

$plugin_name        = 'Dropshipping XML for WooCommerce';
$plugin_class_name  = '\WPDesk\DropshippingXmlFree\Plugin';
$plugin_text_domain = 'dropshipping-xml-for-woocommerce';
$product_id         = 'Dropshipping XML for WooCommerce';
$plugin_file        = __FILE__;
$plugin_dir         = dirname( __FILE__ );
$dummy_desc         = __( 'Import wholesale products to your store. Synchronize your WooCommerce products with the current offer of your suppliers.', 'dropshipping-xml-for-woocommerce' );
$dummy_url          = __( 'https://www.wpdesk.net/products/dropshipping-xml-woocommerce/', 'dropshipping-xml-for-woocommerce' );

$requirements = [
	'php'     => '7.2',
	'wp'      => '5.0',
	'plugins' => [
		[
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '5.0',
		],
	],
];

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/plugin-init-php52-free.php';
