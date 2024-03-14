<?php
/**
 * Interakt Add-on Loader.
 *
 * @package interakt-add-on-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'INTRKT_Loader' ) ) {

	/**
	 * Class INTRKT_Loader.
	 */
	final class INTRKT_Loader {


		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance = null;

		/**
		 * Member Variable
		 *
		 * @var utils
		 */
		public $utils = null;

		/**
		 *  Initiator
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self();

				/**
				 * Interakt loaded.
				 *
				 * Fires when Interakt was fully loaded and instantiated.
				 *
				 * @since 1.0.0
				 */
				do_action( 'intrkt_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->intrkt_define_constants();

			// Activation hook.
			register_activation_hook( INTRKT_FILE, array( $this, 'intrkt_activation_reset' ) );

			add_action( 'plugins_loaded', array( $this, 'intrkt_load_plugin' ), 99 );

			// update database value for abandon checkout value for plugin version 1.0.6
			add_action( 'admin_init', array( $this, 'intrkt_after_upgrade_completed' ) );
		}

		/**
		 * Defines all constants
		 *
		 * @since 1.0.0
		 */
		public function intrkt_define_constants() {
			define( 'INTRKT_BASE', plugin_basename( INTRKT_FILE ) );
			define( 'INTRKT_DIR', plugin_dir_path( INTRKT_FILE ) );
			define( 'INTRKT_URL', plugins_url( '/', INTRKT_FILE ) );
			define( 'INTRKT_SLUG', 'Interakt' );
			define( 'INTRKT_VER', '1.0.7' );
			define( 'INTRKT_CONTACT_NUMBER', '+917021512345' );
			define( 'INTRKT_CONTACT_EMAIL', 'support@interakt.ai' );
			define( 'INTRKT_TERMS_CONDITION', 'https://interakt.shop/terms-of-service/' );
			define( 'INTRKT_PRIVACY_POLICY', 'https://interakt.shop/privacy-policy/?_ga=2.126940107.759827972.1667192371-305847239.1628594047' );
			define( 'INTRKT_CLIENT_ID', '38b44d92-c975-46e1-813f-8bc833bc7fe8' );
			define( 'INTRKT_CLIENT_SECRET', 'hYF06V1eE0JAPWWkPsU_YK_LnKxmPhbO' );
			define( 'INTRKT_API_HOST', 'https://api.interakt.ai' );
			define( 'INTRKT_REDIRECT_URL', site_url( 'wp-json/intrkt/v1/oauth' ) );
			define( 'INTRKT_OAUTH_URL', 'https://app.interakt.ai/third-party/integration/start' );
			define( 'INTRKT_ORDER_STATUS_TABLE', 'intrkt_order_status' );
			define( 'INTRKT_WOOCOMMERCE_DOC', 'https://interakt.shop/resource-center/how-to-integrate-whatsapp-business-api-with-woocommerce-via-interakts-plugin/' );
			define( 'INTRKT_WITHOUT_COUNTRY_CODE', 'without_country_code' );
			define( 'INTRKT_WITH_COUNTRY_CODE', 'with_country_code' );
			define( 'INTRKT_DEFAULT_COUNTRY_CODE', '+91' );
			define( 'INTRKT_DEBUG_MODE', 'false' );
			define( 'INTRKT_IS_DB_UPDATE_REQUIRED', 'true' );
			define( 'INTRKT_CURRENT_PLUGIN_NAME', 'Abandoned Checkout Recovery & Order Notifications for WooCommerce' );
			define( 'INTRKT_OTHER_PLUGIN_PATH', 'whatsapp-order-notifications-and-api/whatsapp-order-notifications-and-api.php' );
		}

		/**
		 * Loads plugin files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function intrkt_load_plugin() {
			$installed_plugin = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : ''; // phpcs:ignore WordPress.Security
			if ( INTRKT_OTHER_PLUGIN_PATH === $installed_plugin ) {
				add_action( 'admin_notices', array( $this, 'intrkt_other_plugin_activate_notice' ) );
				return;
			}
			if ( ! class_exists( 'WooCommerce' ) ) {
				add_action( 'admin_notices', array( $this, 'intrkt_missing_wc_notice' ) );
				return;
			}
			$this->intrkt_load_helper_files_components();
			$this->intrkt_load_core_files();
			add_action( 'admin_notices', array( $this, 'intrkt_integration_pending_notice' ) );
		}
		/**
		 * Load Helper Files and Components.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function intrkt_load_helper_files_components() {
			include_once INTRKT_DIR . 'includes/class-intrkt-helper.php';
			$this->utils = Intrkt_Helper::get_instance();
		}

		/**
		 * Load Core Components.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function intrkt_load_core_files() {

			/* Interakt setting class */
			include_once INTRKT_DIR . 'includes/class-intrktsetting.php';
			/**API calls */
			include_once INTRKT_DIR . 'includes/class-intrkt-api.php';
			/**Handle CoD */
			include_once INTRKT_DIR . 'includes/class-intrkt-cod.php';
		}


		/**
		 * Activation Reset
		 */
		public function intrkt_activation_reset() {

			$this->intrkt_update_default_settings();
			$this->intrkt_initialize_required_tables();
		}


		/**
		 *  Set the default settings.
		 */
		public function intrkt_update_default_settings() {
			$current_user     = wp_get_current_user();
			$default_settings = array(
				'intrkt_log_data' => 'false',
			);
			foreach ( $default_settings as $option_key => $option_value ) {
				if ( ! get_option( $option_key ) ) {
					update_option( $option_key, $option_value );
				}
			}
		}

		/**
		 * Create new database tables for plugin.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function intrkt_initialize_required_tables() {
			include_once INTRKT_DIR . 'includes/class-intrkt-database.php';
			$db = Intrkt_Database::get_instance();
			$db->intrkt_create_tables();
			$db->insert_default_data();
		}
		/**
		 * WooCommerce fallback notice.
		 *
		 * @return void
		 */
		public function intrkt_missing_wc_notice() {
			/* translators: %s WC download URL link. */
			echo '<div class="error"><p><strong>' . sprintf( esc_html__( '%1$s requires WooCommerce to be installed and active. You can download %2$s here.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ), esc_html( INTRKT_CURRENT_PLUGIN_NAME ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
		}

		/**
		 * Other plugin fallback notice.
		 *
		 * @return void
		 */
		public function intrkt_other_plugin_activate_notice() {
			/* translators: %s WC download URL link. */
			echo '<div class="error"><p><strong>' . sprintf( esc_html__( '%s is installed please deactivate this plugin to activate the other.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ), esc_html( INTRKT_CURRENT_PLUGIN_NAME ) ) . '</strong></p></div>';
		}

		/**
		 * Interakt integration pending notice.
		 *
		 * @return void
		 */
		public function intrkt_integration_pending_notice() {
			$is_integration_enable  = $this->utils->intrkt_get_account_integration_status();
			$is_intrkt_setting_page = $this->utils->is_intrkt_setting_page();
			if ( 'true' === $is_integration_enable || true === $is_intrkt_setting_page ) {
				return;
			}
			?>
				<div id="" class="notice is-dismissible woocommerce-info intrkt-pending-notice" style="padding:15px;">
					<div class="intrkt-logo-wrap">
						<img src="<?php echo esc_url( INTRKT_URL . '/admin/images/interakt_logo.jpg' ); ?>" width='100' height='100'" alt="">
					</div>
					<div class="intrkt-notes">
						<p class= 'intrkt-title'>
							<?php echo esc_html__( 'Interakt for WooCommerce is installed, and ready to go!', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>
						</p>
						<span class= 'intrkt-tagline'>
							<?php
								echo esc_html__( 'To set up automated WhatsApp notifications for abandoned checkouts & order confirmations, click below.', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
							?>
						</span>
						<span class="submit">
							<a href="<?php echo esc_url( 'admin.php?page=' . INTRKT_SLUG ); ?>" class="button-primary" style="float:right;"><?php echo esc_html__( 'Setup', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></a>
						</span>
					</div>
				</div>
				<?php
		}

		/**
		 * Update database value fo abandon cart checkout.
		 *
		 * @since 1.0.6
		 */

		public function intrkt_after_upgrade_completed() {
			$intrkt_get_update_db_value = get_option('intrkt_update_db_value');
			if ( ! $intrkt_get_update_db_value ) {
				global $wpdb;
				$intrkt_order_status = $wpdb->prefix . INTRKT_ORDER_STATUS_TABLE;
				$is_table_exist = $wpdb->get_var( "SHOW TABLES LIKE '$intrkt_order_status'" ); //phpcs:ignore
				if ( empty( $is_table_exist ) ) {
					return false;
				}
				$get_intrkt_abandon_checkout = $wpdb->get_row( "SELECT * FROM $intrkt_order_status WHERE intrkt_status = 'intrkt_abandon_checkout' AND order_status = 'any'", ARRAY_A );
				if ( !empty( $get_intrkt_abandon_checkout ) ) {
					$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						$intrkt_order_status,
						array(
							'order_status' => 'wc-failed,wc-pending',
							'payment_mode' => $get_intrkt_abandon_checkout['payment_mode'],
						),
						array(
							'intrkt_status' => $get_intrkt_abandon_checkout['intrkt_status'],
						)
					);
					update_option('intrkt_update_db_value', 1);
				}
			}
		}
	}

	/**
	 *  Prepare if class 'INTRKT_Loader' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	INTRKT_Loader::get_instance();
}


if ( ! function_exists( 'intrkt_load' ) ) {
	/**
	 * Get global class.
	 *
	 * @return object
	 */
	function intrkt_load() {
		return INTRKT_Loader::get_instance();
	}
}

