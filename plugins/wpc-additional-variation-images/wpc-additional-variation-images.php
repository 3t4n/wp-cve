<?php
/*
Plugin Name: WPC Additional Variation Images for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Additional Variation Images allows users to configure a distinct set of images per variation of variable products.
Version: 1.1.4
Author: WPClever
Author URI: https://wpclever.net
Text Domain: wpc-additional-variation-images
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 6.4
WC requires at least: 3.0
WC tested up to: 8.5
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WPCVI_VERSION' ) && define( 'WPCVI_VERSION', '1.1.4' );
! defined( 'WPCVI_FILE' ) && define( 'WPCVI_FILE', __FILE__ );
! defined( 'WPCVI_PATH' ) && define( 'WPCVI_PATH', plugin_dir_path( __FILE__ ) );
! defined( 'WPCVI_URI' ) && define( 'WPCVI_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WPCVI_REVIEWS' ) && define( 'WPCVI_REVIEWS', 'https://wordpress.org/support/plugin/wpc-additional-variation-images/reviews/?filter=5' );
! defined( 'WPCVI_SUPPORT' ) && define( 'WPCVI_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=wpcpq&utm_campaign=wporg' );
! defined( 'WPCVI_CHANGELOG' ) && define( 'WPCVI_CHANGELOG', 'https://wordpress.org/plugins/wpc-additional-variation-images/#developers' );
! defined( 'WPCVI_DISCUSSION' ) && define( 'WPCVI_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-additional-variation-images' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WPCVI_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';

if ( ! function_exists( 'wpcvi_init' ) ) {
	add_action( 'plugins_loaded', 'wpcvi_init', 11 );

	function wpcvi_init() {
		load_plugin_textdomain( 'wpc-additional-variation-images', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'wpcvi_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPCleverWpcvi' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWpcvi {
				public function __construct() {
					require_once 'includes/class-backend.php';
					require_once 'includes/class-frontend.php';
				}
			}

			new WPCleverWpcvi();
		}
	}
}

if ( ! function_exists( 'wpcvi_notice_wc' ) ) {
	function wpcvi_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Additional Variation Images</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
