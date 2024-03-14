<?php
/**
 * Plugin Name: Shipping Notices
 * Plugin URI: https://wordpress.org/plugins/octolize-shipping-notices/
 * Description: Configure custom shipping notice instead of "no shipping options were found". Display it in the cart and checkout, based on defined regions.
 * Version: 1.5.9
 * Author: Octolize
 * Author URI: https://octol.io/notices-author
 * Text Domain: octolize-shipping-notices
 * Domain Path: /lang/
 * Requires at least: 5.8
 * Tested up to: 6.4
 * WC requires at least: 8.3
 * WC tested up to: 8.7
 * Requires PHP: 7.4
 * Copyright 2022 Octolize Ltd.
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* THIS VARIABLE CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '1.5.9';

$plugin_name        = 'Shipping Notices';
$plugin_class_name  = '\Octolize\Shipping\Notices\Plugin';
$plugin_text_domain = 'octolize-shipping-notices';
$product_id         = 'Shipping Notices';
$plugin_file        = __FILE__;
$plugin_dir         = __DIR__;

define( $plugin_class_name, $plugin_version );
define( 'OCTOLIZE_SHIPPING_NOTICES_VERSION', $plugin_version );
define( 'OCTOLIZE_SHIPPING_NOTICES_SCRIPT_VERSION', 6 );

$requirements = [
	'php'          => '7.2',
	'wp'           => '5.7',
	'repo_plugins' => [
		[
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '6.2',
		],
	],
];

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';
