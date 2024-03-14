<?php
/*
	Plugin Name: Autopay for WooCommerce
	Plugin URI: https://www.wpdesk.pl/
	Description: Autopay online payments for WooCommerce. Pay quickly and securely with electronic transfer, BLIK, G-Pay, Apple Pay or credit card.
	Version: 2.2.2
	Author: WP Desk
	Text Domain: pay-wp
	Domain Path: /lang/
	Author URI: http://www.wpdesk.pl/
	Requires at least: 6.1
    Tested up to: 6.4.2
    WC requires at least: 8.4
    WC tested up to: 8.7
    Requires PHP: 7.4

	Copyright 2022 WP Desk Ltd.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
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


/* THESE TWO VARIABLES CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '2.2.2';
$plugin_release_timestamp = '2023-06-05 08:23';

$plugin_name        = 'Autopay dla WooCommerce';
$product_id         = 'Autopay dla WooCommerce';
$plugin_class_name  = \WPDesk\GatewayWPPay\Plugin::class;
$plugin_text_domain = 'pay-wp';

$plugin_file = __FILE__;
$plugin_dir  = __DIR__;

define( $plugin_class_name, $plugin_version );

$requirements = array(
	'php'     => '7.4',
	'wp'      => '6.1',
	'plugins' => array(
		array(
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
		),
	),
);

add_action( 'before_woocommerce_init', function () {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';
