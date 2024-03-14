<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH PayPal Express Checkout for WooCommerce
 * @since  1.0.0
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/*
 * @package Yithemes
 * @since  1.0.0
 */

if ( ! class_exists( 'YITH_PayPal_EC' ) ) {
	/**
	 * Class YITH_PayPal_EC
	 */
	class YITH_PayPal_EC {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_PayPal_EC
		 */
		protected static $instance;

		/**
		 * Gateway id
		 *
		 * @var string
		 */
		public static $gateway_id = 'yith-paypal-ec';

		/**
		 * Helper
		 *
		 * @var \YITH_PayPal_EC_Helper
		 */
		public $ec;

		/**
		 * YITH_PayPal_EC_API_Handler
		 *
		 * @var \YITH_PayPal_EC_API_Handler
		 */
		public $api;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_PayPal_EC
		 * @since 1.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0
		 */
		public function __construct() {

		}

		/**
		 * Run the plugin.
		 *
		 * @since 1.0
		 */
		public function run() {

			$this->load();

			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			// load the plugin framework.
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			// add the gateway on woocommerce payment gateways list.
			add_filter( 'woocommerce_payment_gateways', array( $this, 'add_yith_paypal_ec' ), 10 );

			// check if the gateway is enabled.
			if ( 'yes' !== $this->ec->enabled ) {
				return;
			}

			// check if API are set and are valid if the gateway is on production environment.
			if ( ! ( 'live' === $this->ec->env && ! $this->ec->valid_api_settings() ) ) {
				$this->api = new YITH_PayPal_EC_API_Handler( $this->ec->env, $this->ec->api_username, $this->ec->api_password, $this->ec->api_signature );
			}

			add_action( 'admin_notices', array( $this, 'show_admin_warning' ) );
			add_action( 'wp_ajax_yith_paypal_ec_dismiss_notice_message', array( $this, 'ajax_dismiss_notice' ) );

			add_filter( 'allowed_redirect_hosts', array( $this, 'add_paypal_hosts' ) );

			// if there's PayPal Standard or Express checkout mark is disabled remove the gateway.
			add_action( 'woocommerce_available_payment_gateways', array( $this, 'disable_gateways' ), 100 );

			add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );

			// Declare support with HPOS system for WooCommerce 8.
			add_action( 'before_woocommerce_init', array( $this, 'declare_hpos_support' ) );
		}


		/**
		 * Declare HPOS support
		 *
		 * @since 3.2.0
		 * @return void
		 */
		public function declare_hpos_support() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YITH_PAYPAL_EC_INIT );
			}
		}

		/**
		 * Start the game.
		 *
		 * @since  1.0
		 */
		public function load() {
			// Load Gateway Class.
			require_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-gateway.php';
			require_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-api-handler.php';

			// Load helper class.
			$this->ec = include YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-helper.php';

			if ( $this->is_admin() ) {
				require_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-admin.php';
				YITH_PayPal_EC_Admin();
			} else {
				require_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-frontend.php';
				YITH_PayPal_EC_Frontend();
			}

			require_once YITH_PAYPAL_EC_INC . 'integrations/class.yith-paypal-ec-integration.php';
			YITH_PayPal_EC_Integration();

		}

		/**
		 * AJAX handler for dismiss notice action.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function ajax_dismiss_notice() {
			if ( empty( $_POST['dismiss_action'] ) ) {
				return;
			}

			check_ajax_referer( 'yith_paypal_ec_dismiss_notice', 'nonce' );

			switch ( $_POST['dismiss_action'] ) {
				case 'yith_paypal_ec_dismiss_warning_message':
					update_option( 'yith_paypal_ec_warning_message_dismissed', 'yes' );
					break;
				case 'yith_paypal_ec_dismiss_subscription_warning_message':
					update_option( 'yith_paypal_ec_subscription_warning_message_dismissed', 'yes' );
			}

			wp_die();
		}

		/**
		 * Show admin warning
		 *
		 * @since 1.0
		 * @access public
		 */
		public function show_admin_warning() {

			if ( 'yes' !== get_option( 'yith_paypal_ec_warning_message_dismissed', 'no' ) && 'live' === $this->ec->env && ! $this->ec->valid_api_settings() ) {
				$warning_message = sprintf( '%s <a href="%s">%s</a>.', __( 'The plugin YITH PayPal Express Checkout for WooCommerce is ready to work. Add your PayPal credentials ', 'yith-paypal-express-checkout-for-woocommerce' ), YITH_PayPal_EC_Admin()->get_setting_url(), __( 'here', 'yith-paypal-express-checkout-for-woocommerce' ) );

				?>
				<div class="notice notice-warning is-dismissible yith-paypal-ec-warning-message">
					<p>
						<strong><?php echo wp_kses_post( $warning_message ); ?></strong>
					</p>
				</div>
				<script>
					(function ($) {
						$('.yith-paypal-ec-warning-message').on('click', '.notice-dismiss', function () {
							jQuery.post("<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", {
								action: "yith_paypal_ec_dismiss_notice_message",
								dismiss_action: "yith_paypal_ec_dismiss_warning_message",
								nonce: "<?php echo esc_js( wp_create_nonce( 'yith_paypal_ec_dismiss_notice' ) ); ?>"
							});
						});
					})(jQuery);
				</script>
				<?php
			}

			if ( 'yes' !== get_option( 'yith_paypal_ec_subscription_warning_message_dismissed' ) && defined( 'YITH_YWSBS_PREMIUM' ) && version_compare( YITH_YWSBS_VERSION, '1.4.6', '<' ) ) {
				$warning_message = __( 'The plugin YITH PayPal Express Checkout for WooCommerce needs the version 1.4.6 of YITH WooCommerce Subscription Premium.', 'yith-paypal-express-checkout-for-woocommerce' );

				?>
				<div class="notice notice-warning is-dismissible yith-paypal-ec-subscription-warning-message">
					<p>
						<strong><?php echo wp_kses_post( $warning_message ); ?></strong>
					</p>
				</div>
				<script>
					(function ($) {
						$('.yith-paypal-ec-subscription-warning-message').on('click', '.notice-dismiss', function () {
							jQuery.post("<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", {
								action: "yith_paypal_ec_dismiss_notice_message",
								dismiss_action: "yith_paypal_ec_dismiss_subscription_warning_message",
								nonce: "<?php echo esc_js( wp_create_nonce( 'yith_paypal_ec_dismiss_notice' ) ); ?>"
							});
						});
					})(jQuery);
				</script>
				<?php
			}
		}

		/**
		 * Add PayPal hosts in the white list of safe redirect.
		 *
		 * @param array $hosts Hosts.
		 *
		 * @return array
		 * @since 1.0
		 */
		public function add_paypal_hosts( $hosts ) {
			$hosts[] = 'www.sandbox.paypal.com';
			$hosts[] = 'sandbox.paypal.com';
			$hosts[] = 'www.paypal.com';
			$hosts[] = 'paypal.com';
			$hosts[] = 'ipnpb.sandbox.paypal.com';
			$hosts[] = 'ipnpb.paypal.com';

			return $hosts;
		}

		/**
		 * Add the Gateway on the WooCommerce Gateway List.
		 *
		 * @param array $gateways Gateways list.
		 *
		 * @return array
		 *
		 * @since  1.0
		 */
		public function add_yith_paypal_ec( $gateways ) {
			$new_gateway     = 'YITH_Gateway_Paypal_Express_Checkout';
			$paypal_position = array_search( 'WC_Gateway_Paypal', $gateways, true );
			if ( $paypal_position ) {
				array_splice( $gateways, $paypal_position + 1, 0, $new_gateway );
			} else {
				$gateways[] = $new_gateway;
			}

			return $gateways;
		}

		/**
		 * Manage the gateways at checkout
		 *
		 * @param array $gateways Gateway list.
		 *
		 * @return array
		 * @since  1.0
		 */
		public function disable_gateways( $gateways ) {

			$sbs_on_cart = false;
			if ( isset( WC()->cart ) && defined( 'YITH_YWSBS_PREMIUM' ) ) {
				$sbs_on_cart = is_callable( 'YWSBS_Subscription_Cart::cart_has_subscriptions' ) ? YWSBS_Subscription_Cart::cart_has_subscriptions() : YITH_WC_Subscription()->cart_has_subscriptions();
			}

			if ( ! is_checkout() || 'no' === $this->ec->on_checkout || ( $sbs_on_cart && 'no' === $this->ec->reference_transaction ) ) {
				unset( $gateways[ self::$gateway_id ] );
				return $gateways;
			}

			if ( isset( WC()->session->yith_paypal_session ) && isset( $gateways[ self::$gateway_id ] ) ) {
				// leaves only this gateway when the session is active during a cart payment.
				$gateways = array( self::$gateway_id => $gateways[ self::$gateway_id ] );
			} elseif ( isset( $gateways['paypal'] ) ) {
				// removes the standard PayPal gateway on checkout page if this gateway is enabled.
				unset( $gateways['paypal'] );
			}

			return $gateways;
		}

		/**
		 * Return if yith express checkout can be used to pay
		 *
		 * @return bool
		 * @since  1.0
		 */
		public function ec_is_enabled() {
			return 'yes' === $this->ec->enabled && 'yes' === $this->ec->on_checkout;
		}

		/**
		 * Check if is admin.
		 *
		 * @since  1.0
		 * @access public
		 * @return boolean
		 */
		public function is_admin() {
			$context_check = isset( $_REQUEST['context'] ) && 'frontend' === sanitize_text_field( wp_unslash( $_REQUEST['context'] ) ); //phpcs:ignore
			$is_admin      = is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX && $context_check );

			return apply_filters( 'yith_paypal_ec_check_is_admin', $is_admin );
		}

		/**
		 * Load localisation files.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'yith-paypal-express-checkout-for-woocommerce', false, YITH_PAYPAL_EC_DIR . '/languages' );
		}

		/**
		 * Load YIT Plugin Framework
		 *
		 * @access public
		 *
		 * @return void
		 * @since  1.0
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/***
		 * Declare support for WooCommerce features.
		 */
		public function declare_wc_features_support() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YITH_PAYPAL_EC_INIT, true );
			}
		}

	}

	/**
	 * Unique access to instance of YITH_PayPal_EC class
	 *
	 * @return \YITH_PayPal_EC
	 */
	function yith_paypal_ec() {
		return YITH_PayPal_EC::get_instance();
	}
}
