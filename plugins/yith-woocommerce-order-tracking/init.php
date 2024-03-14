<?php
/**
 * Plugin Name: YITH WooCommerce Order & Shipment Tracking
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-order-tracking/
 * Description: Enter the order shipping and tracking information in your WooCommerce orders. Share the tracking info with your customers and improve your customer experience.
 * Author: YITH
 * Text Domain: yith-woocommerce-order-tracking
 * Version: 2.23.0
 * Author URI: http://yithemes.com/
 * WC requires at least: 8.4
 * WC tested up to: 8.6
 *
 * @package YITH\OrderTracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! defined( 'YITH_YWOT_PLUGIN_NAME' ) ) {
	define( 'YITH_YWOT_PLUGIN_NAME', 'YITH WooCommerce Order & Shipment Tracking' );
}

if ( ! defined( 'YITH_YWOT_FREE_INIT' ) ) {
	define( 'YITH_YWOT_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_YWOT_SLUG' ) ) {
	define( 'YITH_YWOT_SLUG', 'yith-woocommerce-order-tracking' );
}

if ( ! defined( 'YITH_YWOT_VERSION' ) ) {
	define( 'YITH_YWOT_VERSION', '2.23.0' );
}

if ( ! defined( 'YITH_YWOT_FILE' ) ) {
	define( 'YITH_YWOT_FILE', __FILE__ );
}

if ( ! defined( 'YITH_YWOT_DIR' ) ) {
	define( 'YITH_YWOT_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_YWOT_URL' ) ) {
	define( 'YITH_YWOT_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YITH_YWOT_ASSETS_URL' ) ) {
	define( 'YITH_YWOT_ASSETS_URL', YITH_YWOT_URL . 'assets' );
}

if ( ! defined( 'YITH_YWOT_INCLUDES_PATH' ) ) {
	define( 'YITH_YWOT_INCLUDES_PATH', YITH_YWOT_DIR . 'includes' );
}

if ( ! defined( 'YITH_YWOT_TEMPLATE_PATH' ) ) {
	define( 'YITH_YWOT_TEMPLATE_PATH', YITH_YWOT_DIR . 'templates' );
}

if ( ! defined( 'YITH_YWOT_VIEW_PATH' ) ) {
	define( 'YITH_YWOT_VIEW_PATH', YITH_YWOT_DIR . 'views' );
}

if ( ! defined( 'YITH_YWOT_ASSETS_IMAGES_URL' ) ) {
	define( 'YITH_YWOT_ASSETS_IMAGES_URL', YITH_YWOT_ASSETS_URL . '/images/' );
}

if ( ! function_exists( 'yith_ywot_install' ) ) {
	/**
	 * Check WC installation
	 */
	function yith_ywot_install() {
		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'yith_ywot_install_woocommerce_admin_notice' );
		} elseif ( defined( 'YITH_YWOT_PREMIUM' ) ) {
			add_action( 'admin_notices', 'yith_ywot_install_free_admin_notice' );
			deactivate_plugins( plugin_basename( __FILE__ ) );
		} else {
			do_action( 'yith_ywot_init' );
		}
	}

	add_action( 'plugins_loaded', 'yith_ywot_install', 11 );
}

if ( ! function_exists( 'yith_ywot_install_woocommerce_admin_notice' ) ) {
	/**
	 * Print a notice if WooCommerce is not installed.
	 */
	function yith_ywot_install_woocommerce_admin_notice() {
		?>
		<div class="error">
			<p>
				<?php
					// translators: %s is the plugin name.
					echo esc_html( sprintf( __( '%s is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-order-tracking' ), YITH_YWOT_PLUGIN_NAME ) );
				?>
			</p>
		</div>
		<?php
	}
}

if ( ! function_exists( 'yith_ywot_install_free_admin_notice' ) ) {
	/**
	 * Print a notice if the premium version is activated.
	 */
	function yith_ywot_install_free_admin_notice() {
		?>
		<div class="error">
			<p>
				<?php
					// translators: %s is the plugin name.
					echo esc_html( sprintf( __( 'You can\'t activate the free version of %s while you are using the premium one.', 'yith-woocommerce-order-tracking' ), YITH_YWOT_PLUGIN_NAME ) );
				?>
			</p>
		</div>
		<?php
	}
}

if ( ! function_exists( 'yith_ywot_init' ) ) {
	/**
	 * Load text domain and start plugin
	 */
	function yith_ywot_init() {
		load_plugin_textdomain( 'yith-woocommerce-order-tracking', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Load required classes and functions.
		require_once YITH_YWOT_INCLUDES_PATH . '/class.yith-woocommerce-order-tracking.php';
		require_once YITH_YWOT_INCLUDES_PATH . '/functions.php';

		global $YWOT_Instance; // phpcs:ignore
		$YWOT_Instance = new Yith_WooCommerce_Order_Tracking(); // phpcs:ignore
	}

	add_action( 'yith_ywot_init', 'yith_ywot_init' );
}

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_YWOT_DIR . 'plugin-fw/init.php' ) ) {
	require_once YITH_YWOT_DIR . 'plugin-fw/init.php';
}
yit_maybe_plugin_fw_loader( YITH_YWOT_DIR );

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );
