<?php
/**
 * Plugin Name: Sales Countdown Timer
 * Plugin URI: https://villatheme.com/extensions/sales-countdown-timer/
 * Description: Create a sense of urgency with a countdown to the beginning or end of sales, store launch or other events for higher conversions.
 * Version: 1.1.1
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * Text Domain: sales-countdown-timer
 * Copyright 2018 - 2024 VillaTheme.com. All rights reserved.
 * Tested up to: 6.4
 * WC requires at least: 5.0
 * WC tested up to: 8.4
 * Requires PHP: 7.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'SALES_COUNTDOWN_TIMER_VERSION', '1.1.1' );
/**
 * Detect plugin. For use on Front End only.
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'sctv-sales-countdown-timer/sctv-sales-countdown-timer.php' ) ) {
	return;
}

/**
 * Class SALES_COUNTDOWN_TIMER
 */
class SALES_COUNTDOWN_TIMER {
	public function __construct() {
//		register_activation_hook( __FILE__, array( $this, 'install' ) );
//		register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		//Compatible with High-Performance order storage (COT)
		add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );
	}

	public function init() {
		if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
			require_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "sales-countdown-timer" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "support.php";
		}

		$environment = new VillaTheme_Require_Environment( [
				'plugin_name'     => 'Sales Countdown Timer',
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

		$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "sales-countdown-timer" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
		require_once $init_file;
	}

	public function before_woocommerce_init() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	/**
	 * When active plugin Function will be call
	 */
	public function install() {
		global $wp_version;
		if ( version_compare( $wp_version, "4.4", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 4.4 or higher." );
		}
	}

	/**
	 * When deactive function will be call
	 */
	public function uninstall() {

	}
}

new SALES_COUNTDOWN_TIMER();