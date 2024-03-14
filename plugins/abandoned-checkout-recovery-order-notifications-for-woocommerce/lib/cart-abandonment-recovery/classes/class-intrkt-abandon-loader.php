<?php
/**
 * Abandon cart Loader.
 *
 * @package Intrkt-Cart-Abandonment-Recovery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'INTRKT_ABANDON_Loader' ) ) {

	/**
	 * Class INTRKT_ABANDON_Loader.
	 */
	final class INTRKT_ABANDON_Loader {


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
				 * Interakt CA loaded.
				 *
				 * Fires when Interakt CA was fully loaded and instantiated.
				 *
				 * @since 1.0.0
				 */
				do_action( 'intrkt_abandon_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->intrkt_define_constants();

			// Activation hook.
			register_activation_hook( INTRKT_ABANDON_FILE, array( $this, 'intrkt_abandon_activation_reset' ) );

			// deActivation hook.
			register_deactivation_hook( INTRKT_ABANDON_FILE, array( $this, 'intrkt_abandon_deactivation_reset' ) );

			add_action( 'plugins_loaded', array( $this, 'intrkt_abandon_load_plugin' ), 99 );
		}

		/**
		 * Defines all constants
		 *
		 * @since 1.0.0
		 */
		public function intrkt_define_constants() {
			define( 'INTRKT_ABANDON_BASE', plugin_basename( INTRKT_ABANDON_FILE ) );
			define( 'INTRKT_ABANDON_DIR', plugin_dir_path( INTRKT_ABANDON_FILE ) . 'lib/cart-abandonment-recovery/' );
			define( 'INTRKT_ABANDON_URL', plugins_url( '/', INTRKT_ABANDON_FILE ) . 'lib/cart-abandonment-recovery/' );
			define( 'INTRKT_ABANDON_VER', '1.2.18' );
			define( 'INTRKT_ABANDON_SLUG', 'intrkt_abandon' );
			define( 'INTRKT_ABANDON_CART_ABANDONMENT_TABLE', 'intrkt_abandon_cart_abandonment' );
		}

		/**
		 * Loads plugin files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function intrkt_abandon_load_plugin() {
			$installed_plugin = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : ''; // phpcs:ignore WordPress.Security
			if ( INTRKT_OTHER_PLUGIN_PATH === $installed_plugin ) {
				return;
			}
			if ( ! class_exists( 'WooCommerce' ) ) {
				return;
			}
			$this->intrkt_abandon_load_helper_files_components();
			$this->intrkt_abandon_load_core_components();
			/**
			 * Interakt Init.
			 *
			 * Fires when Interakt is instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'intrkt_abandon_init' );
		}

		/**
		 * Create new database tables for plugin updates.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function intrkt_abandon_initialize_cart_abandonment_tables() {

			include_once INTRKT_ABANDON_DIR . 'modules/cart-abandonment/classes/class-intrkt-abandon-database.php';
			$db = INTRKT_ABANDON_Database::get_instance();
			$db->intrkt_create_tables();
		}

		/**
		 * Load Helper Files and Components.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function intrkt_abandon_load_helper_files_components() {
			include_once INTRKT_ABANDON_DIR . 'classes/class-intrkt-abandon-utils.php';
			$this->utils = INTRKT_ABANDON_Utils::get_instance();
		}

		/**
		 * Load Core Components.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function intrkt_abandon_load_core_components() {

			/* Cart abandonment templates class */
			include_once INTRKT_ABANDON_DIR . 'modules/cart-abandonment/classes/class-intrkt-abandon-module-loader.php';

		}


		/**
		 * Activation Reset
		 */
		public function intrkt_abandon_activation_reset() {
			$this->intrkt_update_default_settings();
			$this->intrkt_abandon_initialize_cart_abandonment_tables();
		}


		/**
		 *  Set the default cart abandonment settings.
		 */
		public function intrkt_update_default_settings() {

			$current_user     = wp_get_current_user();
			$email_from       = ( isset( $current_user->user_firstname ) && ! empty( $current_user->user_firstname ) ) ? $current_user->user_firstname . ' ' . $current_user->user_lastname : 'Admin';
			$default_settings = array(
				'intrkt_abandon_status'                => 'on',
				'intrkt_gdpr_status'                   => 'off',
				'intrkt_coupon_code_status'            => 'off',
				'intrkt_zapier_tracking_status'        => 'off',
				'intrkt_delete_plugin_data'            => 'off',
				'intrkt_cut_off_time'                  => 15,
				'intrkt_from_name'                     => $email_from,
				'intrkt_from_email'                    => $current_user->user_email,
				'intrkt_reply_email'                   => $current_user->user_email,
				'intrkt_discount_type'                 => 'percent',
				'intrkt_coupon_amount'                 => 10,
				'intrkt_zapier_cart_abandoned_webhook' => '',
				'intrkt_gdpr_message'                  => 'Your email & cart are saved so we can send email reminders about this order.',
				'intrkt_coupon_expiry'                 => 0,
				'intrkt_coupon_expiry_unit'            => 'hours',
				'intrkt_excludes_orders'               => array( 'processing', 'completed' ),

			);

			foreach ( $default_settings as $option_key => $option_value ) {
				if ( ! get_option( $option_key ) ) {
					update_option( $option_key, $option_value );
				}
			}
		}

		/**
		 * Deactivation Reset
		 */
		public function intrkt_abandon_deactivation_reset() {
			wp_clear_scheduled_hook( 'intrkt_abandon_update_order_status_action' );
		}
	}

	/**
	 *  Prepare if class 'INTRKT_ABANDON_Loader' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	INTRKT_ABANDON_Loader::get_instance();
}


if ( ! function_exists( 'INTRKT_ca' ) ) {
	/**
	 * Get global class.
	 *
	 * @return object
	 */
	function intrkt_abandon() {
		return INTRKT_ABANDON_Loader::get_instance();
	}
}

