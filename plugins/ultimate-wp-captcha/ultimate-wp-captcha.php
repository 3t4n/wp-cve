<?php
/**
 * Plugin Name: Ultimate WP Captcha
 * Plugin URI: https://khanzeeshan.in
 * Description: Ultimate WP Captcha plugin is an effective security solution that prevents spam Login and registration of your WordPress website, WooCommerce and LearnDash. We are offer you to integrated Google reCaptcha and hCaptcha.
 * Author: khanzeeshan
 * Author URI: https://khanzeeshan.in
 * Version: 1.1.8
 * Text Domain: ultimate-wp-captcha
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package uwc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Required minimums and constants
 */
define( 'UWC_VERSION', '1.1.1' ); // WRCS: DEFINED_VERSION.
define( 'UWC_MAIN_FILE', __FILE__ );
define( 'UWC_PLUGIN_BASENAME', plugin_basename( UWC_MAIN_FILE ) );
define( 'UWC_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'UWC_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
add_action( 'plugins_loaded', 'uwc_init' );
/**
 * Required minimums and constants.
 */
function uwc_init() {
	load_plugin_textdomain( 'ultimate-wp-captcha', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
	if ( ! class_exists( 'Ultimat_Wp_Captcha' ) ) {

		/**
		 * Ultimat_Wp_Captcha class.
		 */
		class Ultimat_Wp_Captcha {
			/**
			 * Instance of this class.
			 *
			 * @var Singleton The reference the *Singleton* instance of this class
			 */
			private static $instance;

			/**
			 * Returns the *Singleton* instance of this class.
			 *
			 * @return Singleton The *Singleton* instance.
			 */
			public static function get_instance() {
				if ( null === self::$instance ) {
					self::$instance = new self();
				}
				return self::$instance;
			}

			/**
			 * Protected constructor to prevent creating a new instance of the
			 * *Singleton* via the `new` operator from outside of this class.
			 */
			private function __construct() {
				add_action( 'admin_init', array( $this, 'install' ) );
				$this->init();
				add_filter( 'plugin_action_links_' . UWC_PLUGIN_BASENAME, array( $this, 'uwc_action_links' ) );
			}

			/**
			 * Include required core files used in admin and on the frontend.
			 *
			 * @version 1.0.0
			 * @since   1.0.0
			 */
			public function init() {
				require_once dirname( __FILE__ ) . '/includes/class-uwc-setting-page.php';
				require_once dirname( __FILE__ ) . '/includes/function/helper-function.php';
				require_once dirname( __FILE__ ) . '/includes/class-uwc-captcha-form-render.php';
				require_once dirname( __FILE__ ) . '/includes/class-uwc-captcha-form-action-handler.php';
			}

			/**
			 * Link to UWC settings page from plugins screen.
			 *
			 * @since 1.1.1
			 * @version 1.1.1
			 * @param string[] $actions An array of plugin action links.
			 * @return array
			 */
			public function uwc_action_links( $actions ) {
				$uwc_link = array(
					'settings' => '<a href="' . admin_url( 'admin.php?page=ultimate-wp-captcha' ) . '" aria-label="' . esc_attr__( 'View UWC settings', 'ultimate-wp-captcha' ) . '">' . esc_html__( 'Settings', 'ultimate-wp-captcha' ) . '</a>',
				);
				return array_merge( $uwc_link, $actions );
			}

			/**
			 * Updates the plugin version in db
			 *
			 * @since 1.0.0
			 * @version 1.0.0
			 */
			public function update_plugin_version() {
				delete_option( 'uwc_version' );
				update_option( 'uwc_version', UWC_VERSION );
			}

			/**
			 * Handles upgrade routines.
			 *
			 * @since 1.0.0
			 * @version 1.0.0
			 */
			public function install() {
				if ( ! is_plugin_active( plugin_basename( __FILE__ ) ) ) {
					return;
				}

				if ( UWC_VERSION !== get_option( 'uwc_version' ) ) {
					do_action( 'uwc_updated' );
					$this->update_plugin_version();
				}
			}
		}

		Ultimat_Wp_Captcha::get_instance();
	}
}
