<?php
/**
 * Plugin Name: EU Cookies Bar
 * Plugin URI: https://villatheme.com/extensions/eu-cookies-bar
 * Description: Simple cookie bar to make your website GDPR(General Data Protection Regulation) compliant(EU Cookie Law) and more.
 * Version: 1.0.13
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * Text Domain: eu-cookies-bar
 * Copyright 2018-2023 VillaTheme.com. All rights reserved.
 * Requires at least: 4.4
 * Tested up to: 6.3
 * Requires PHP: 7.0
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'EU_COOKIES_BAR_VERSION', '1.0.13' );

/**
 * Class EU_COOKIES_BAR
 */
class EU_COOKIES_BAR {
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init() {
		$plugin_dir = plugin_dir_path( __FILE__ );

		if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
			include_once $plugin_dir . 'includes/support.php';
		}

		$environment = new \VillaTheme_Require_Environment( [
				'plugin_name' => 'EU Cookies Bar',
				'php_version' => '7.0',
				'wp_version'  => '5.0',
			]
		);

		if ( $environment->has_error() ) {
			return;
		}

		require_once $plugin_dir . 'includes/define.php';
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
}

new EU_COOKIES_BAR();