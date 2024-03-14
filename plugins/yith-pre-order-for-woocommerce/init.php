<?php
/**
 * Plugin Name: YITH Pre-Order for WooCommerce
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-pre-order
 * Description: Thanks to <code><strong>YITH Pre-Order for WooCommerce</strong></code> you can improve right away the sales of unavailable items, offering your customers the chance to purchase the products and receive them only after they are officially on sale. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce on <strong>YITH</strong></a>.
 * Version: 2.20.0
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-pre-order-for-woocommerce
 * Domain Path: /languages/
 * WC requires at least: 8.3
 * WC tested up to: 8.5
 *
 * @package YITH\PreOrder
 */

/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/* === DEFINE === */
! defined( 'YITH_WCPO_VERSION' ) && define( 'YITH_WCPO_VERSION', '2.20.0' );
! defined( 'YITH_WCPO_FREE_INIT' ) && define( 'YITH_WCPO_FREE_INIT', plugin_basename( __FILE__ ) );
! defined( 'YITH_WCPO_SLUG' ) && define( 'YITH_WCPO_SLUG', 'yith-pre-order-for-woocommerce' );
! defined( 'YITH_WCPO_FILE' ) && define( 'YITH_WCPO_FILE', __FILE__ );
! defined( 'YITH_WCPO_PATH' ) && define( 'YITH_WCPO_PATH', plugin_dir_path( __FILE__ ) );
! defined( 'YITH_WCPO_URL' ) && define( 'YITH_WCPO_URL', plugins_url( '/', __FILE__ ) );
! defined( 'YITH_WCPO_ASSETS_URL' ) && define( 'YITH_WCPO_ASSETS_URL', YITH_WCPO_URL . 'assets/' );
! defined( 'YITH_WCPO_ASSETS_JS_URL' ) && define( 'YITH_WCPO_ASSETS_JS_URL', YITH_WCPO_URL . 'assets/js/' );
! defined( 'YITH_WCPO_TEMPLATE_PATH' ) && define( 'YITH_WCPO_TEMPLATE_PATH', YITH_WCPO_PATH . 'templates/' );
! defined( 'YITH_WCPO_WC_TEMPLATE_PATH' ) && define( 'YITH_WCPO_WC_TEMPLATE_PATH', YITH_WCPO_PATH . 'templates/woocommerce/' );
! defined( 'YITH_WCPO_OPTIONS_PATH' ) && define( 'YITH_WCPO_OPTIONS_PATH', YITH_WCPO_PATH . 'plugin-options' );

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WCPO_PATH . 'plugin-fw/init.php' ) ) {
	require_once YITH_WCPO_PATH . 'plugin-fw/init.php';
}
yit_maybe_plugin_fw_loader( YITH_WCPO_PATH );

register_deactivation_hook( YITH_WCPO_FILE, 'ywpo_rewrite_rules' );

if ( ! function_exists( 'ywpo_declare_wc_features_support' ) ) {
	/**
	 * Declare support for WooCommerce features.
	 */
	function ywpo_declare_wc_features_support() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YITH_WCPO_FREE_INIT, true );
		}
	}
}

if ( ! function_exists( 'ywpo_declare_wc_cart_checkout_blocks_support' ) ) {
	add_action( 'before_woocommerce_init', 'ywpo_declare_wc_cart_checkout_blocks_support' );
	/**
	 * Declare incompatibility for Cart and Checkout blocks. Compatibility in progress.
	 */
	function ywpo_declare_wc_cart_checkout_blocks_support() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, false );
		}
	}
}

if ( ! function_exists( 'ywpo_rewrite_rules' ) ) {
	/**
	 * When deactivating the plugin, delete the flag option for updating permalink structure
	 */
	function ywpo_rewrite_rules() {
		delete_option( 'yith-ywpo-flush-rewrite-rules' );
	}
}

/* Start the plugin on plugins_loaded */

if ( ! function_exists( 'yith_ywpo_install' ) ) {
	/**
	 * Install the plugin
	 */
	function yith_ywpo_install() {

		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'yith_ywpo_install_woocommerce_admin_notice' );
		} else {
			add_action( 'before_woocommerce_init', 'ywpo_declare_wc_features_support' );
			do_action( 'yith_ywpo_init' );
		}
	}
	add_action( 'plugins_loaded', 'yith_ywpo_install', 11 );
}

if ( ! function_exists( 'yith_ywpo_install_woocommerce_admin_notice' ) ) {
	/**
	 * Admin notice in case WooCommerce is not activated.
	 */
	function yith_ywpo_install_woocommerce_admin_notice() {
		?>
		<div class="error">
			<p><?php esc_html_e( 'YITH Pre Order for WooCommerce is enabled but not effective. It requires WooCommerce in order to work.', 'yith-pre-order-for-woocommerce' ); ?></p>
		</div>
		<?php
	}
}

add_action( 'yith_ywpo_init', 'yith_ywpo_init' );

if ( ! function_exists( 'yith_ywpo_init' ) ) {
	/**
	 * Start the plugin
	 */
	function yith_ywpo_init() {
		/**
		 * Load text domain
		 */
		load_plugin_textdomain( 'yith-pre-order-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		require_once YITH_WCPO_PATH . 'includes/class-yith-pre-order.php';
		return YITH_Pre_Order();
	}
}

