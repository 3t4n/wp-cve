<?php
/**
 * Plugin Name: Bopo - WooCommerce Product Bundle Builder
 * Plugin URI: https://villatheme.com/extensions/bopo-woocommerce-product-bundle-builder/
 * Description: The effective way to create product bundle for WooCommerce
 * Version: 1.1.0
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * Text Domain: woo-bopo-bundle
 * Domain Path: /languages
 * Copyright 2022-2024 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 6.4
 * WC requires at least: 5.0
 * WC tested up to: 8.4
 * Requires PHP: 7.0
 */

defined( 'ABSPATH' ) || exit;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active( 'bopo-woocommerce-product-bundle-builder/bopo-woocommerce-product-bundle-builder.php' ) ) {
	return;
}

if ( ! defined( 'VI_WOO_BOPO_BUNDLE_VERSION' ) ) {
	define( 'VI_WOO_BOPO_BUNDLE_VERSION', '1.1.0' );
}

if ( ! class_exists( 'VI_WOO_BOPO_BUNDLE' ) ) {
	class VI_WOO_BOPO_BUNDLE {
		public $plugin_name = 'Bopo - WooCommerce Product Bundle Builder';

		public function __construct() {
//			register_activation_hook( __FILE__, array( $this, 'install' ) );
//			register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );

			add_action( 'plugins_loaded', array( $this, 'init' ) );

			//compatible with 'High-Performance order storage (COT)'
			add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );
		}

		public function before_woocommerce_init() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}

		public function init() {
			if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
				require_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "bopo-woo-product-bundle-builder/includes/support.php";
			}

			$environment = new VillaTheme_Require_Environment( [
					'plugin_name'     => $this->plugin_name,
					'php_version'     => '7.0',
					'wp_version'      => '5.0',
					'wc_version'      => '5.0',
					'require_plugins' => [
						[
							'slug' => 'woocommerce',
							'name' => 'WooCommerce',
						],
					]
				]
			);

			if ( $environment->has_error() ) {
				return;
			}

			$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "bopo-woo-product-bundle-builder" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "includes.php";
			require_once $init_file;
		}

		/**
		 * When active plugin Function will be call
		 */
		public function install() {
			global $wp_version;
			if ( version_compare( $wp_version, "5.1", "<" ) ) {
				deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
				wp_die( "This plugin requires WordPress version 5.1 or higher." );
			}
		}

		/**
		 * When deActive function will be call
		 */
		public function uninstall() {

		}
	}
}
new VI_WOO_BOPO_BUNDLE();