<?php
/**
 * Plugin Name: Flexible Wishlist
 * Description: Let your customers create their own multiple and intuitive WooCommerce wishlists and customize them entirely to their needs.
 * Version: 1.2.12
 * Author: WP Desk
 * Author URI: https://www.wpdesk.net/
 * Text Domain: flexible-wishlist
 * Domain Path: /lang/
 * Requires at least: 6.2
 * Tested up to: 6.4
 * WC requires at least: 8.2
 * WC tested up to: 8.5
 * Requires PHP: 7.4
 *
 * Copyright 2022 WP Desk Ltd.
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
 *
 * @package WPDesk\FlexibleWishlist
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* THESE TWO VARIABLES CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '1.2.12';

$plugin_name        = 'Flexible Wishlist';
$plugin_class_name  = 'WPDesk\FlexibleWishlist\Plugin';
$plugin_text_domain = 'flexible-wishlist';
$product_id         = null;
$plugin_file        = __FILE__;
$plugin_dir         = dirname( __FILE__ );

$requirements = [
	'php'     => '7.4',
	'wp'      => '6.2',
	'plugins' => [
		[
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '8.2',
		],
	],
];

add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';
