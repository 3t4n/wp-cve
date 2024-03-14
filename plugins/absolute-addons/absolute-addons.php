<?php
/**
 * Addon elements for Elementor Page Builder.
 *
 * Plugin Name: Absolute Addons
 * Description: Addon elements for Elementor Page Builder.
 * Plugin URI: https://absoluteplugins.com/absolute-addons
 * Version: 1.0.14
 * Author: AbsolutePlugins
 * Author URI: https://absoluteplugins.com
 * Text Domain: absolute-addons
 * Domain Path: /languages/
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * [PHP]
 * Requires PHP: 7.1
 *
 * [WP]
 * Requires at least: 5.2
 * Tested up to: 6.0
 *
 * [Elementor]
 * Elementor requires at least: 3.2.5
 * Elementor tested up to: 3.6
 *
 * [WC]
 * WC requires at least: 5.9
 * WC tested up to: 6.7
 *
 * @package AbsoluteAqddons
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2019 AbsolutePlugins <https://absoluteplugins.com>
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'ABSOLUTE_ADDONS_VERSION' ) ) {
	/**
	 * Plugin Version.
	 *
	 * @var string
	 */
	define( 'ABSOLUTE_ADDONS_VERSION', '1.0.14' );
}
if ( ! defined( 'ABSOLUTE_ADDONS_FILE' ) ) {
	/**
	 * Plugin File Ref.
	 *
	 * @var string
	 */
	define( 'ABSOLUTE_ADDONS_FILE', __FILE__ );
}
if ( ! defined( 'ABSOLUTE_ADDONS_BASE' ) ) {
	/**
	 * Plugin Base Name.
	 *
	 * @var string
	 */
	define( 'ABSOLUTE_ADDONS_BASE', plugin_basename( ABSOLUTE_ADDONS_FILE ) );
}
if ( ! defined( 'ABSOLUTE_ADDONS_PATH' ) ) {
	/** @define "ABSOLUTE_ADDONS_PATH" "./" */
	/**
	 * Plugin Dir Ref.
	 *
	 * @var string
	 */
	define( 'ABSOLUTE_ADDONS_PATH', plugin_dir_path( ABSOLUTE_ADDONS_FILE ) );
}
if ( ! defined( 'ABSOLUTE_ADDONS_WIDGETS_PATH' ) ) {
	/** @define "ABSOLUTE_ADDONS_WIDGETS_PATH" "./widgets/" */
	/**
	 * Widgets Dir Ref.
	 *
	 * @var string
	 */
	define( 'ABSOLUTE_ADDONS_WIDGETS_PATH', ABSOLUTE_ADDONS_PATH . 'widgets/' );
}
if ( ! defined( 'ABSOLUTE_ADDONS_URL' ) ) {
	/**
	 * Plugin URL.
	 *
	 * @var string
	 */
	define( 'ABSOLUTE_ADDONS_URL', plugin_dir_url( ABSOLUTE_ADDONS_FILE ) );
}

// Define INT_MIN & INT_MIN for php 5.6 BackCompact.
if ( ! defined( 'ABSOLUTE_ADDONS_INT_MIN' ) ) {
	define( 'ABSOLUTE_ADDONS_INT_MIN', defined( 'PHP_INT_MIN' ) ? PHP_INT_MIN : -999999 ); // phpcs:ignore PHPCompatibility.Constants.NewConstants.php_int_minFound
}
if ( ! defined( 'ABSOLUTE_ADDONS_INT_MAX' ) ) {
	define( 'ABSOLUTE_ADDONS_INT_MAX', defined( 'PHP_INT_MAX' ) ? PHP_INT_MAX : 999999 ); // phpcs:ignore PHPCompatibility.Constants.NewConstants.php_int_maxFound
}

if ( ! class_exists( 'AbsoluteAddons\Absolute_Addons', false ) ) {
	require_once ABSOLUTE_ADDONS_PATH . "class-absolute-addons.php";
}

/**
 * Instantiate Absolute_Addons.
 * @return AbsoluteAddons\Absolute_Addons
 */
function absolute_addons() {
	return AbsoluteAddons\Absolute_Addons::instance();
}

// Fire UP.
absolute_addons();
// End of file class-absolute-addons.php
