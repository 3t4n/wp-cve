<?php
/**
 * Handles the plugin activation and deactivation process and admin notices for Stock Sync with Google Sheet for WooCommerce.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.2.2
 */

// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined('ABSPATH') || exit;

if ( ! class_exists('\StockSyncWithGoogleSheetForWooCommerce\Install') ) {

	/**
	 * Class Install.
	 * Handles the plugin activation and deactivation process and admin notices for Stock Sync with Google Sheet for WooCommerce.
	 *
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 */
	class Install extends Base {

		/**
		 * Instance of the class.
		 *
		 * @var self
		 */
		public static $instance;

		/**
		 * Initialize the class.
		 *
		 * @return void
		 */
		public static function init() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}
			self::$instance->shop_recovery();
			register_activation_hook(SSGSW_FILE, [ self::$instance, 'activate' ]);
			register_deactivation_hook(SSGSW_FILE, [ self::$instance, 'deactivate' ]);

			add_action('pre_current_active_plugins', [ self::$instance, 'admin_notices' ]);

			self::$instance->app->reset_options(false);
			
		}

		/**
		 * Activate the plugin.
		 *
		 * @return void
		 */
		public function activate() {

			$this->reset_auto_redirection();
			$this->initialize_authorization_token();
			$sheet_url = get_option( 'ssgsw_spreadsheet_url', '');
			if ( empty($sheet_url) ) {
				update_option( 'ssgsw_new_user_activated_key', '1' );
			}
			if ( ! ssgsw_get_option( 'hide_upgrade_notice' ) ) {
				set_transient( 'ssgsw_hide_upgrade_notice', true, DAY_IN_SECONDS * 7 );
			}
		}
		/**
		 * Shop recovery method for restorin product
		 */
		public function shop_recovery() {
			$sheet_url    = get_option( 'ssgsw_spreadsheet_url', '');
			$get_recovery = get_option( 'ssgsw_shop_recovery', false );
			if ( ! empty( $sheet_url ) && false === $get_recovery ) {
				global $wpdb;
				$query = $wpdb->prepare("
					UPDATE {$wpdb->prefix}posts
					SET post_type = 'product_variation'
					WHERE post_type = 'product'
					AND post_parent != %d
				", 0 );
				$result = $wpdb->query($query);
				update_option( 'ssgsw_shop_recovery', true );
			}
		}
		/**
		 * Deactivate the plugin.
		 *
		 * @return void
		 */
		public function deactivate() {
			$this->reset_auto_redirection();
			if ( get_option('ssgsw_install_times') ) {
				delete_option('ssgsw_install_times');
			}
		}

		/**
		 * Reset auto redirection.
		 *
		 * @return void
		 */
		public function reset_auto_redirection() {
			ssgsw_update_option('redirect_to_admin_page', 1);
		}

		/**
		 * Initializes the authorization token.
		 *
		 * @return void
		 */
		public function initialize_authorization_token() {
			$token = ssgsw_get_option('token');
			if ( empty($token) ) {
				$token = bin2hex(random_bytes(14));
				ssgsw_update_option('token', $token);
			}
		}

		/**
		 * Prints admin notices.
		 *
		 * @return void
		 */
		public function admin_notices() {

			if ( ssgsw()->is_woocommerce_activated() ) {
				return;
			}

			if ( ! current_user_can('activate_plugins') ) {
				return;
			}

			$woocommerce = 'woocommerce/woocommerce.php';
			$plugin_name = __('Stock Sync with Google Sheet for WooCommerce', 'stock-sync-with-google-sheet-for-woocommerce');

			if ( ssgsw()->is_woocommerce_installed() ) {
				$activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $woocommerce . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $woocommerce);

				$message     = wp_sprintf( '<strong>%s</strong> requires <strong>WooCommerce</strong> plugin to be activated.', $plugin_name );
				$button_text = __('Activate WooCommerce', 'stock-sync-with-google-sheet-for-woocommerce');
			} else {
				$activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=woocommerce'), 'install-plugin_woocommerce');
				$message        = wp_sprintf( '<strong>%s</strong> requires <strong>WooCommerce</strong> plugin to be installed and activated.', $plugin_name );
				$button_text    = __('Install WooCommerce', 'stock-sync-with-google-sheet-for-woocommerce');
			}

			$button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';

			printf('<div class="error"><p>%1$s %2$s</p></div>', wp_kses_post( $message ), wp_kses_post ( $button ) );
		}
	}

	// Initialize the class.
	Install::init();
}
