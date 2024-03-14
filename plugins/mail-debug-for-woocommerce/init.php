<?php
/**
 * Plugin Name: Mail Debug for WooCommerce
 * Description: Mail Debug for WooCommerce allows you to debug WordPress and WooCommerce emails.
 * Author: Leanza Francesco
 * Author URI: http://leanzafrancesco.com
 * Version: 1.3.0
 * Text Domain: mail-debug-for-woocommerce
 * Domain Path: /languages/
 * WC requires at least: 4.0
 * WC tested up to: 7.2
 */

/*  Copyright 2018  Leanza Francesco (email : leanzafrancesco@gmail.com)

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


if ( ! defined( 'MDWC_FILE' ) ) {
	define( 'MDWC_FILE', __FILE__ );
}

if ( ! defined( 'MDWC_INIT' ) ) {
	define( 'MDWC_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'MDWC_VERSION' ) ) {
	define( 'MDWC_VERSION', '1.3.0' );
}

if ( ! defined( 'MDWC_URL' ) ) {
	define( 'MDWC_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'MDWC_DIR' ) ) {
	define( 'MDWC_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'MDWC_VIEWS_PATH' ) ) {
	define( 'MDWC_VIEWS_PATH', MDWC_DIR . 'views/' );
}

if ( ! defined( 'MDWC_ASSETS_URL' ) ) {
	define( 'MDWC_ASSETS_URL', MDWC_URL . 'assets' );
}

if ( ! class_exists( 'Mail_Debug_For_WooCommerce' ) ) {
	class Mail_Debug_For_WooCommerce {
		private static $_instance;

		public static function get_instance() {
			return ! is_null( self::$_instance ) ? self::$_instance : self::$_instance = new self();
		}

		private function __construct() {
			if ( ! function_exists( 'WC' ) ) {
				return;
			}

			load_plugin_textdomain( 'mail-debug-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			$this->_includes();

			$this->_load();
		}

		private function _includes() {
			require_once( 'includes/functions.mdwc.php' );
			require_once( 'includes/class.mdwc-debugger.php' );
			require_once( 'includes/class.mdwc-post-type-admin.php' );
			require_once( 'includes/class.mdwc-settings.php' );
		}

		private function _load() {
			add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );

			if ( is_admin() ) {
				mdwc_settings();
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			}

			MDWC_Post_Type_Admin::get_instance();
			MDWC_Debugger::get_instance();
		}

		public function admin_enqueue_scripts() {
			$screen = get_current_screen();
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_style( 'mdwc-admin-style', MDWC_ASSETS_URL . '/css/admin.css', array(), MDWC_VERSION );
			wp_register_script( 'mdwc-admin-preview', MDWC_ASSETS_URL . "/js/admin-preview{$suffix}.js", array(), MDWC_VERSION );
			wp_register_script( 'mdwc-admin', MDWC_ASSETS_URL . "/js/admin{$suffix}.js", array(), MDWC_VERSION );

			$mdwc = array(
				'i18n_delete_confirmation' => __( 'This action will delete all your Mail Debugs. Are you sure?', 'mail-debug-for-woocommerce' ),
			);

			wp_localize_script( 'mdwc-admin', 'mdwc', $mdwc );

			if ( ( isset( $_GET['post_type'] ) && 'mail-debug' === $_GET['post_type'] ) || 'mail-debug' === $screen->id ) {
				wp_enqueue_style( 'mdwc-admin-style' );
				wp_enqueue_script( 'mdwc-admin' );
			}

			if ( 'edit-mail-debug' === $screen->id ) {
				wp_enqueue_script( 'mdwc-admin-preview' );
			}
		}

		/**
		 * Declare support for WooCommerce features.
		 *
		 * @since 1.3.0
		 */
		public function declare_wc_features_support() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', MDWC_INIT, true );
			}
		}
	}
}

if ( ! function_exists( 'Mail_Debug_For_WooCommerce' ) ) {
	function Mail_Debug_For_WooCommerce() {
		return Mail_Debug_For_WooCommerce::get_instance();
	}

	add_action( 'plugins_loaded', 'Mail_Debug_For_WooCommerce', 20 );
}

