<?php
/**
Plugin Name: ShopMagic for WooCommerce
Plugin URI: https://shopmagic.app/
Description: Marketing Automation and Custom Email Designer for WooCommerce
Author: WP Desk
Version: 4.2.9
Author URI: https://shopmagic.app/
Text Domain: shopmagic-for-woocommerce
Domain Path: /lang/
Requires at least: 6.2
Tested up to: 6.4
WC requires at least: 8.3
WC tested up to: 8.6
Requires PHP: 7.2

Copyright 2023 WP Desk Ltd.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* THESE VARIABLES CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '4.2.9';

if ( ! defined( 'SHOPMAGIC_VERSION' ) ) {
	define( 'SHOPMAGIC_VERSION', $plugin_version );
}

$plugin_name        = 'ShopMagic for WooCommerce';
$plugin_class_name  = '\WPDesk\ShopMagic\Plugin';
$plugin_text_domain = 'shopmagic-for-woocommerce';
$product_id         = 'ShopMagic for WooCommerce';
$plugin_file        = __FILE__;
$plugin_dir         = __DIR__;

$requirements = [
	'php'     => '7.2',
	'wp'      => '5.0',
];

require __DIR__ . '/vendor/woocommerce/action-scheduler/action-scheduler.php';
require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';
require __DIR__ . '/vendor_prefixed/php-di/php-di/src/functions.php';
require __DIR__ . '/vendor_prefixed/league/csv/src/functions.php';
