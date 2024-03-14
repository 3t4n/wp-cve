<?php
/**
 * Plugin Name: payever - WooCommerce Gateway
 * Plugin URI: https://getpayever.com/
 * Description: Extends WooCommerce by Adding the payever Gateway.
 * Version: 2.4.2
 * Author: payever
 * Author URI: https://getpayever.com/
 *
 * Text Domain: payever-woocommerce-gateway
 * Domain Path: /languages/
 *
 * WC requires at least: 2.6.14
 * WC tested up to: 7.7.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Required minimums and constants
 */
define( 'WC_PAYEVER_PLUGIN_VERSION', '2.4.2' );
define( 'WC_PAYEVER_PLUGIN_MIN_PHP_VER', '5.6.0' );
define( 'WC_PAYEVER_PLUGIN_MIN_WC_VER', '2.6.14' );
define( 'WC_PAYEVER_PLUGIN_FILE', __FILE__ );
define( 'WC_PAYEVER_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WC_PAYEVER_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

if ( ! class_exists( 'WC_Payever_Payments' ) ) {
	/**
	 * Class WC_Payever_Payments
	 *
	 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
	 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
	 */
	class WC_Payever_Payments {
		/**
		 * The reference the *Singleton* instance of this class.
		 *
		 * @var $instance
		 */
		private static $instance;

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @return self::$instance The *Singleton* instance.
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Private clone method to prevent cloning of the instance of the
		 * *Singleton* instance.
		 *
		 * @return void
		 */
		private function __clone() {
		}

		/**
		 * Private unserialize method to prevent unserializing of the *Singleton*
		 * instance.
		 *
		 * @return void
		 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
		 */
		public function __wakeup() {
		}

		/**
		 * Notices (array)
		 *
		 * @var array
		 */
		public $notices = array();

		/**
		 * Protected constructor to prevent creating a new instance of the
		 * *Singleton* via the `new` operator from outside of this class.
		 */
		protected function __construct() {
			$this->includes();
			register_activation_hook(
				WC_PAYEVER_PLUGIN_FILE,
				array(
					WC_Payever_Synchronization::class,
					'migration',
				)
			);

			register_uninstall_hook(
				WC_PAYEVER_PLUGIN_FILE,
				array(
					WC_Payever_Synchronization::class,
					'uninstall',
				)
			);

			if ( ! wp_next_scheduled( 'payever_daily_event' ) ) {
				wp_schedule_event( time() + 24 * 60 * 60, 'daily', 'payever_daily_event' );
			}

			add_action(
				'payever_daily_event',
				array(
					WC_Payever_Plugin_Command_Cron::class,
					'execute_plugin_commands',
				)
			);

			if ( ! wp_next_scheduled( 'payever_hourly_event' ) ) {
				wp_schedule_event( time() + 60 * 60, 'hourly', 'payever_hourly_event' );
			}
			add_action(
				'payever_hourly_event',
				array(
					new WC_Payever_Synchronization_Cron(),
					'process_synchronization_queue',
				)
			);

			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'woocommerce_loaded', array( $this, 'woocommerce_loaded' ), 20 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_javascript_script' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_javascript_script' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_stylesheets' ) );
			add_action( 'woocommerce_blocks_loaded', array( $this, 'woocommerce_blocks_loaded' ) );
		}

		/**
		 * Init the plugin after plugins_loaded so environment variables are set.
		 */
		public function init() {
			// Init the gateway itself.
			$this->init_gateways();

			add_filter(
				'plugin_action_links_' . plugin_basename( __FILE__ ),
				array(
					$this,
					'payever_action_links',
				)
			);
		}

		/**
		 * WooCommerce Load handler.
		 *
		 * @return void
		 */
		public function woocommerce_loaded() {
			if ( class_exists( 'WC_Payever_Logger' ) ) {
				new WC_Payever_Logger();
			}
		}

		/**
		 * WooCommerce Load handler.
		 *
		 * @return void
		 */
		public function woocommerce_blocks_loaded() {
			if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
				return;
			}
			require_once 'includes/blocks/class-wc-payever-payments-blocks.php';
			add_action(
				'woocommerce_blocks_payment_method_type_registration',
				function ( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
					$payment_method_registry->register( new WC_Payever_Payments_Blocks() );
				}
			);
		}

		public function enqueue_javascript_script() {
			if ( is_checkout() ) {
				wp_enqueue_script(
					'payever_frontend',
					WC_PAYEVER_PLUGIN_URL . '/assets/js/checkout.js',
					array( 'jquery' )
				);
			}

			if ( is_admin() ) {
				wp_enqueue_script(
					'payever_capture',
					WC_PAYEVER_PLUGIN_URL . '/assets/js/admin/capture.js',
					array(
						'jquery',
						'wc-admin-order-meta-boxes',
						'wc-admin-meta-boxes',
						'wc-backbone-modal',
						'selectWoo',
						'wc-clipboard',
					)
				);

				wp_enqueue_script(
					'payever_cancel',
					WC_PAYEVER_PLUGIN_URL . '/assets/js/admin/cancel.js',
					array(
						'jquery',
						'wc-admin-order-meta-boxes',
						'wc-admin-meta-boxes',
						'wc-backbone-modal',
						'selectWoo',
						'wc-clipboard',
					)
				);

				wp_register_style( 'payever_admin', WC_PAYEVER_PLUGIN_URL . '/assets/css/payever_admin.css', '' );
				wp_enqueue_style( 'payever_admin' );
			}
		}

		public function enqueue_stylesheets() {
			wp_register_style( 'payever_frontend', WC_PAYEVER_PLUGIN_URL . '/assets/css/payever_frontend.css', '' );
			wp_enqueue_style( 'payever_frontend' );
		}

		public function payever_action_links( $links ) {
			$plugin_links = array(
				'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=payever_settings' ) ) . '">' . __(
					'Configuration',
					'payever-woocommerce-gateway'
				) . '</a>',
			);

			// Merge our new link with the default ones
			return array_merge( $plugin_links, $links );
		}

		/**
		 * Check if WooCommerce is active, and if it isn't, disable payever payments.
		 */
		public function admin_notices() {
			echo '<div id="notice" class="error"><p>';
			echo esc_html__( 'Upgrade finished.', 'payever-woocommerce-gateway' );
			echo /* translators: 1: url */ sprintf(
				__(
					'WooCommerce plugin must be active for the plugin <b>payever Payment Gateway for WooCommerce</b>. Kindly <a href="%1$s" target="_new">install & activate it</a>',
					'payever-woocommerce-gateway'
				),
				'http://www.woothemes.com/woocommerce/'
			);
			echo '</p></div>';
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {
			require_once WC_PAYEVER_PLUGIN_PATH . '/vendor/autoload.php';
			new WC_Payever_Hooks();
			new WC_Payever_Order_Changes();
			new WC_Payever_Fees();
			new WC_Payever_Plugin_Version();
			new WC_Payever_Outward_Actions();
			new WC_Payever_Finance_Express_Api();
			new WC_Payever_Widget();
			WC_Payever_Synchronization::init();
			WC_Payever_Migration::init();

			if ( is_admin() ) {
				new WC_Payever_Admin_Settings();
				new WC_Payever_Admin_Order_Edit();
				new WC_Payever_Admin_Shipping();
				new WC_Payever_Ajax();
			}
		}

		/**
		 * Initialize the gateway. Called very early - in the context of the plugins_loaded action
		 *
		 * @since 1.0.0
		 */
		public function init_gateways() {
			/* loads the payever language translation strings */
			load_plugin_textdomain( 'payever-woocommerce-gateway', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );

				return;
			}

			add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateways' ) );
		}

		public function add_gateways( $methods ) {
			if ( ! get_option( WC_Payever_Helper::PAYEVER_ACTIVE_PAYMENTS ) ) {
				WC_Payever_Synchronization::migration();
			}
			$active_payment_options = get_option( WC_Payever_Helper::PAYEVER_ACTIVE_PAYMENTS ) ?: array();

			foreach ( array_keys( $active_payment_options ) as $code ) {
				$current_method = new WC_Payever_Gateway( $code );
				$methods[]      = apply_filters( 'wc_payever_enhance_gateway', $current_method );
			}

			return $methods;
		}
	}

	WC_Payever_Payments::instance();
}
