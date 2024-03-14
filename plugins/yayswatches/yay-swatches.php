<?php

/**
 * Plugin Name:       YaySwatches - Variation Swatches for WooCommerce
 * Plugin URI:        https://yaycommerce.com/yayswatches-variation-swatches-for-woocommerce/
 * Description:       Provide custom swatches for WooCommerce.
 * Version:           1.7.2
 * Author:            YayCommerce
 * Author URI:        https://yaycommerce.com
 * Text Domain:       yay-swatches
 * Domain Path:       /languages
 * Requires at least: 4.7
 * Requires PHP: 5.4
 * WC requires at least: 3.0.0
 * WC tested up to: 8.5.1
 *
 * @package yaycommerce/yayswatches
 */

namespace Yay_Swatches;

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'Yay_Swatches\\init' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Fallback.php';
	if ( isset( $_GET['activate'] ) ) { // phpcs:ignore
		unset( $_GET['activate'] ); // phpcs:ignore
	}
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
}

if ( ! defined( 'YAY_SWATCHES_VERSION' ) ) {
	define( 'YAY_SWATCHES_VERSION', '1.7.2' );
}

if ( ! defined( 'YAY_SWATCHES_FILE' ) ) {
	define( 'YAY_SWATCHES_FILE', __FILE__ );
}

if ( ! defined( 'YAY_SWATCHES_PLUGIN_URL' ) ) {
	define( 'YAY_SWATCHES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YAY_SWATCHES_PLUGIN_DIR' ) ) {
	define( 'YAY_SWATCHES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YAY_SWATCHES_PLUGIN_TEMPLATE' ) ) {
	define( 'YAY_SWATCHES_PLUGIN_TEMPLATE', YAY_SWATCHES_PLUGIN_DIR . '/includes/templates' );
}

if ( ! defined( 'YAY_SWATCHES_BASE_NAME' ) ) {
	define( 'YAY_SWATCHES_BASE_NAME', plugin_basename( __FILE__ ) );
}

spl_autoload_register(
	function ( $class ) {
		$prefix   = __NAMESPACE__; // project-specific namespace prefix
		$base_dir = __DIR__ . '/includes'; // base directory for the namespace prefix

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) { // does the class use the namespace prefix?
			return; // no, move to the next registered autoloader
		}

		$relative_class_name = substr( $class, $len );

		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php
		$file = $base_dir . str_replace( '\\', '/', $relative_class_name ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

if ( ! function_exists( 'Yay_Swatches\\init' ) ) {
	function init() {
		\Yay_Swatches\YayCommerceMenu\RegisterMenu::get_instance();
		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', array( \Yay_Swatches\Engine\ActDeact::class, 'install_yayswatches_admin_notice' ) );
			return;
		}
		add_action(
			'before_woocommerce_init',
			function() {
				if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
				}
			}
		);
		Initialize::get_instance();
		I18n::loadPluginTextdomain();
	}
}
add_action( 'plugins_loaded', 'Yay_Swatches\\init' );

register_activation_hook( YAY_SWATCHES_FILE, array( \Yay_Swatches\Engine\ActDeact::class, 'activate' ) );
register_deactivation_hook( YAY_SWATCHES_FILE, array( \Yay_Swatches\Engine\ActDeact::class, 'deactivate' ) );
