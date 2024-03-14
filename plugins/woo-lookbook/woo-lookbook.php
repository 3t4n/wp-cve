<?php
/**
 * Plugin Name: LookBook for WooCommerce
 * Plugin URI: https://villatheme.com/extensions/woocommerce-lookbook/
 * Description: Allows you to create realistic lookbooks of your products. Help your customers visualize what they purchase from you.
 * Version: 1.1.1
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * Text Domain: woo-lookbook
 * Domain Path: /languages
 * Copyright 2018-2023 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 6.3
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 * Requires PHP: 7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'WOO_F_LOOKBOOK_VERSION', '1.1.1' );
define( 'WOO_F_LOOKBOOK_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
/**
 * Detect plugin. For use on Front End only.
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce-lookbook/woocommerce-lookbook.php' ) ) {
	return;
}

/**
 * Class WOO_LOOKBOOK
 */
class WOO_F_LOOKBOOK {
	public function __construct() {
//		register_activation_hook( __FILE__, array( $this, 'install' ) );
//		register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );
//		add_action( 'admin_notices', array( $this, 'global_note' ) );
//		add_action( 'init', array( $this, 'init' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		//Compatible with High-Performance order storage (COT)
		add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );

	}

	public function init() {
		if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
			require_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-lookbook" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "support.php";
		}

		$environment = new VillaTheme_Require_Environment( [
				'plugin_name'     => 'LookBook for WooCommerce',
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

		$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-lookbook" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
		require_once $init_file;

		add_image_size( 'lookbook', 400, 400, false );
	}

	public function before_woocommerce_init() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	/**
	 * Notify if WooCommerce is not activated
	 */
	function global_note() {
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			?>
			<div id="message" class="error">
				<p><?php _e( 'Please install and activate WooCommerce to use LookBook for WooCommerce.', 'woo-lookbook' ); ?></p>
			</div>
			<?php
		}

	}

	/**
	 * When active plugin Function will be call
	 */
	public function install() {
		global $wp_version;
		if ( version_compare( $wp_version, "4.4", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 2.9 or higher." );
		}
	}

	/**
	 * When deactive function will be call
	 */
	public function uninstall() {

	}
}

new WOO_F_LOOKBOOK();