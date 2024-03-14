<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Plugin Name: YITH WooCommerce Frequently Bought Together
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-frequently-bought-together/
 * Description: The <code><strong>YITH WooCommerce Frequently Bought Together</strong></code> allows increasing the average spent of your store by showing a box with the products purchased together more frequently. In this way, users are encouraged to add more products to their cart just like on Amazon. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
 * Version: 1.34.0
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-woocommerce-frequently-bought-together
 * Domain Path: /languages/
 * WC requires at least: 8.4
 * WC tested up to: 8.6
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogether
 * @version 1.34.0
 */

/*
Copyright 2024  YITH  (email : plugins@yithemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Add message if WooCommerce is not installed.
 *
 * @since 1.0.0
 * @return void
 */
function yith_wfbt_free_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php esc_html_e( 'YITH WooCommerce Frequently Bought Together is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-frequently-bought-together' ); ?></p>
	</div>
	<?php
}


/**
 * Add message if premium version is installed.
 *
 * @since 1.0.0
 * @return void
 */
function yith_wfbt_install_free_admin_notice() {
	?>
	<div class="error">
		<p><?php esc_html_e( 'You can\'t activate the free version of YITH WooCommerce Frequently Bought Together while you are using the premium one.', 'yith-woocommerce-frequently-bought-together' ); ?></p>
	</div>
	<?php
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );


if ( ! defined( 'YITH_WFBT_VERSION' ) ) {
	define( 'YITH_WFBT_VERSION', '1.34.0' );
}

if ( ! defined( 'YITH_WFBT_FREE_INIT' ) ) {
	define( 'YITH_WFBT_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WFBT_INIT' ) ) {
	define( 'YITH_WFBT_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WFBT' ) ) {
	define( 'YITH_WFBT', true );
}

if ( ! defined( 'YITH_WFBT_FILE' ) ) {
	define( 'YITH_WFBT_FILE', __FILE__ );
}

if ( ! defined( 'YITH_WFBT_URL' ) ) {
	define( 'YITH_WFBT_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YITH_WFBT_DIR' ) ) {
	define( 'YITH_WFBT_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_WFBT_TEMPLATE_PATH' ) ) {
	define( 'YITH_WFBT_TEMPLATE_PATH', YITH_WFBT_DIR . 'templates' );
}

if ( ! defined( 'YITH_WFBT_ASSETS_URL' ) ) {
	define( 'YITH_WFBT_ASSETS_URL', YITH_WFBT_URL . 'assets' );
}

if ( ! defined( 'YITH_WFBT_SLUG' ) ) {
	define( 'YITH_WFBT_SLUG', 'yith-woocommerce-frequently-bought-together' );
}

if ( ! defined( 'YITH_WFBT_META' ) ) {
	define( 'YITH_WFBT_META', '_yith_wfbt_ids' );
}

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WFBT_DIR . 'plugin-fw/init.php' ) ) {
	require_once YITH_WFBT_DIR . 'plugin-fw/init.php';
}
yit_maybe_plugin_fw_loader( YITH_WFBT_DIR );

/**
 * Init.
 *
 * @since 1.0.0
 * @return void
 */
function yith_wfbt_free_init() {

	load_plugin_textdomain( 'yith-woocommerce-frequently-bought-together', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	// Load required classes and functions.
	require_once 'includes/class.yith-wfbt.php';

	// Let's start the game!
	YITH_WFBT();
}

add_action( 'yith_wfbt_free_init', 'yith_wfbt_free_init' );

/**
 * Install.
 *
 * @since 1.0.0
 * @return void
 */
function yith_wfbt_free_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'yith_wfbt_free_install_woocommerce_admin_notice' );
	} elseif ( defined( 'YITH_WFBT_PREMIUM' ) ) {
		add_action( 'admin_notices', 'yith_wfbt_install_free_admin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	} else {
		do_action( 'yith_wfbt_free_init' );
	}
}

add_action( 'plugins_loaded', 'yith_wfbt_free_install', 11 );
