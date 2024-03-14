<?php
/**
 * Plugin Name: WooCommerce QuickPay
 * Plugin URI: http://wordpress.org/plugins/woocommerce-quickpay/
 * Description: Integrates your QuickPay payment gateway into your WooCommerce installation.
 * Version: 7.3.0
 * Author: Perfect Solution
 * Text Domain: woo-quickpay
 * Domain Path: /languages/
 * Author URI: http://perfect-solution.dk
 * Wiki: http://quickpay.perfect-solution.dk/
 * WC requires at least: 7.1.0
 * WC tested up to: 8.6
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WCQP_VERSION', '7.3.0' );
define( 'WCQP_URL', plugins_url( __FILE__ ) );
define( 'WCQP_PATH', plugin_dir_path( __FILE__ ) );

add_action( 'plugins_loaded', 'init_quickpay_gateway', 0 );

/**
 * Adds notice in case of WooCommerce being inactive
 */
function wc_quickpay_woocommerce_inactive_notice() {
	$class    = 'notice notice-error';
	$headline = __( 'WooCommerce QuickPay requires WooCommerce to be active.', 'woo-quickpay' );
	$message  = __( 'Go to the plugins page to activate WooCommerce', 'woo-quickpay' );
	printf( '<div class="%1$s"><h2>%2$s</h2><p>%3$s</p></div>', $class, $headline, $message );
}

function init_quickpay_gateway() {
	/**
	 * Required functions
	 */
	if ( ! function_exists( 'is_woocommerce_active' ) ) {
		require_once WCQP_PATH . 'woo-includes/woo-functions.php';
	}

	/**
	 * Check if WooCommerce is active, and if it isn't, disable Subscriptions.
	 *
	 * @since 1.0
	 */
	if ( ! is_woocommerce_active() ) {
		add_action( 'admin_notices', 'wc_quickpay_woocommerce_inactive_notice' );

		return;
	}

	// Import helper methods
	require_once WCQP_PATH . 'includes/template.php';

	// Import helper classes
	require_once WCQP_PATH . 'helpers/notices.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-install.php';
	require_once WCQP_PATH . 'classes/api/woocommerce-quickpay-api.php';
	require_once WCQP_PATH . 'classes/api/woocommerce-quickpay-api-transaction.php';
	require_once WCQP_PATH . 'classes/api/woocommerce-quickpay-api-payment.php';
	require_once WCQP_PATH . 'classes/api/woocommerce-quickpay-api-subscription.php';
	require_once WCQP_PATH . 'classes/utils/woocommerce-quickpay-order-utils.php';
	require_once WCQP_PATH . 'classes/utils/woocommerce-quickpay-order-payments-utils.php';
	require_once WCQP_PATH . 'classes/utils/woocommerce-quickpay-order-transaction-data-utils.php';
	require_once WCQP_PATH . 'classes/utils/woocommerce-quickpay-requests-utils.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-module.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-blocks-checkout.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-emails.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-admin-ajax.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-admin-orders.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-admin-orders-lists-table.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-admin-orders-meta.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-orders.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-subscriptions.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-subscriptions-change-payment-method.php';
	require_once WCQP_PATH . 'classes/modules/woocommerce-quickpay-subscriptions-early-renewals.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-statekeeper.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-exceptions.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-log.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-helper.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-address.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-settings.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-order.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-subscription.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-countries.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-views.php';
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-callbacks.php';
	require_once WCQP_PATH . 'helpers/permissions.php';
	require_once WCQP_PATH . 'helpers/requests.php';
	require_once WCQP_PATH . 'helpers/transactions.php';

	require_once WCQP_PATH . 'extensions/wpml.php';
	require_once WCQP_PATH . 'extensions/polylang.php';


	// Main class
	class WC_QuickPay extends WC_Payment_Gateway {

		/**
		 * $_instance
		 * @var mixed
		 * @access public
		 * @static
		 */
		public static $_instance = null;

		/**
		 * @var WC_QuickPay_Log
		 */
		public $log;

		/** @var string */
		public $instructions = '';

		/**
		 * get_instance
		 *
		 * Returns a new instance of self, if it does not already exist.
		 *
		 * @access public
		 * @static
		 * @return WC_QuickPay
		 */
		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}


		/**
		 * __construct function.
		 *
		 * The class construct
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->id           = 'quickpay';
			$this->method_title = 'QuickPay';
			$this->icon         = '';
			$this->has_fields   = true;

			$this->supports = [
				'subscriptions',
				'products',
				'subscription_cancellation',
				'subscription_reactivation',
				'subscription_suspension',
				'subscription_amount_changes',
				'subscription_date_changes',
				'subscription_payment_method_change_admin',
				'subscription_payment_method_change_customer',
				'refunds',
				'multiple_subscriptions',
				'pre-orders',
			];

			$this->log = new WC_QuickPay_Log();

			// Load the form fields and settings
			$this->init_form_fields();
			$this->init_settings();

			// Get gateway variables
			$this->title             = $this->s( 'title' );
			$this->description       = $this->s( 'description' );
			$this->instructions      = $this->s( 'instructions' );
			$this->order_button_text = $this->s( 'checkout_button_text' );

			do_action( 'woocommerce_quickpay_loaded' );
		}


		/**
		 * filter_load_instances function.
		 *
		 * Loads in extra instances of as separate gateways
		 *
		 * @access public static
		 * @return array
		 */
		public static function filter_load_instances( $methods ) {
			require_once WCQP_PATH . 'classes/instances/instance.php';

			$instances = self::get_gateway_instances();

			foreach ( $instances as $file_name => $class_name ) {
				$file_path = WCQP_PATH . 'classes/instances/' . $file_name . '.php';

				if ( file_exists( $file_path ) ) {
					require_once $file_path;
					$methods[] = $class_name;
				}
			}

			return $methods;
		}

		/**
		 * @return array
		 */
		public static function get_gateway_instances() {
			return [
				'anyday'                  => 'WC_QuickPay_Anyday',
				'apple-pay'               => 'WC_QuickPay_Apple_Pay',
				'fbg1886'                 => 'WC_QuickPay_FBG1886',
				'google-pay'              => 'WC_QuickPay_Google_Pay',
				'ideal'                   => 'WC_QuickPay_iDEAL',
				'klarna'                  => 'WC_QuickPay_Klarna',
				'mobilepay'               => 'WC_QuickPay_MobilePay',
				'mobilepay-subscriptions' => 'WC_QuickPay_MobilePay_Subscriptions',
				'paypal'                  => 'WC_QuickPay_PayPal',
				'quickpay-extra'          => 'WC_QuickPay_Extra',
				'resurs'                  => 'WC_QuickPay_Resurs',
				'sofort'                  => 'WC_QuickPay_Sofort',
				'swish'                   => 'WC_QuickPay_Swish',
				'trustly'                 => 'WC_QuickPay_Trustly',
				'viabill'                 => 'WC_QuickPay_ViaBill',
				'vipps'                   => 'WC_QuickPay_Vipps',
			];
		}


		/**
		 * hooks_and_filters function.
		 *
		 * Applies plugin hooks and filters
		 *
		 * @access public
		 * @return string
		 */
		public function hooks_and_filters() {
			WC_QuickPay_Admin_Ajax::get_instance();
			WC_QuickPay_Admin_Orders::get_instance();
			WC_QuickPay_Admin_Orders_Lists_Table::get_instance();
			WC_QuickPay_Admin_Orders_Meta::get_instance();
			WC_QuickPay_Blocks_Checkout::get_instance();
			WC_QuickPay_Emails::get_instance();
			WC_QuickPay_Orders::get_instance();
			WC_QuickPay_Subscriptions::get_instance();
			WC_QuickPay_Subscriptions_Change_Payment_Method::get_instance();
			WC_QuickPay_Subscriptions_Early_Renewals::get_instance();

			add_action( 'woocommerce_api_wc_' . $this->id, [ $this, 'callback_handler' ] );
			add_action( 'woocommerce_order_status_completed', [ $this, 'woocommerce_order_status_completed' ] );
			add_action( 'in_plugin_update_message-woocommerce-quickpay/woocommerce-quickpay.php', [ __CLASS__, 'in_plugin_update_message' ] );

			// WooCommerce Subscriptions hooks/filters
			if ( $this->supports( 'subscriptions' ) ) {
				add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, [ $this, 'scheduled_subscription_payment' ], 10, 2 );
				add_action( 'woocommerce_subscription_cancelled_' . $this->id, [ $this, 'subscription_cancellation' ] );
				add_action( 'woocommerce_subscription_payment_method_updated_to_' . $this->id, [ $this, 'on_subscription_payment_method_updated_to_quickpay', ], 10, 2 );
				add_filter( 'wc_subscriptions_renewal_order_data', [ $this, 'remove_renewal_meta_data' ], 10 );
				add_filter( 'woocommerce_subscription_payment_meta', [ $this, 'woocommerce_subscription_payment_meta' ], 10, 2 );
				add_action( 'woocommerce_subscription_validate_payment_meta_' . $this->id, [ $this, 'woocommerce_subscription_validate_payment_meta', ], 10, 2 );
			}

			// WooCommerce Pre-Orders
			add_action( 'wc_pre_orders_process_pre_order_completion_payment_' . $this->id, [ $this, 'process_pre_order_payments' ] );
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );

			// Make sure not to add these actions multiple times
			if ( ! has_action( 'init', 'WC_QuickPay_Helper::load_i18n' ) ) {
				add_action( 'admin_enqueue_scripts', 'WC_QuickPay_Helper::enqueue_stylesheet' );
				add_action( 'admin_enqueue_scripts', 'WC_QuickPay_Helper::enqueue_javascript_backend' );
				add_action( 'wp_ajax_quickpay_run_data_upgrader', 'WC_QuickPay_Install::ajax_run_upgrader' );
				add_action( 'in_plugin_update_message-woocommerce-quickpay/woocommerce-quickpay.php', [ __CLASS__, 'in_plugin_update_message' ] );

				add_action( 'woocommerce_email_before_order_table', [ $this, 'email_instructions' ], 10, 2 );

				if ( WC_QuickPay_Helper::option_is_enabled( $this->s( 'quickpay_orders_transaction_info', 'yes' ) ) ) {
					add_action( 'woocommerce_quickpay_accepted_callback', [ $this, 'callback_update_transaction_cache' ], 10, 2 );
				}

				add_action( 'admin_notices', [ $this, 'admin_notices' ] );
			}

			add_action( 'init', 'WC_QuickPay_Helper::load_i18n' );
			add_filter( 'woocommerce_gateway_icon', [ $this, 'apply_gateway_icons' ], 2, 3 );

			// Third party plugins
			add_filter( 'qtranslate_language_detect_redirect', 'WC_QuickPay_Helper::qtranslate_prevent_redirect', 10, 3 );
			add_filter( 'wpss_misc_form_spam_check_bypass', 'WC_QuickPay_Helper::spamshield_bypass_security_check', - 10, 1 );
		}


		/**
		 * s function.
		 *
		 * Returns a setting if set. Introduced to prevent undefined key when introducing new settings.
		 *
		 * @access public
		 *
		 * @param      $key
		 * @param null $default
		 *
		 * @return mixed
		 */
		public function s( $key, $default = null ) {
			if ( isset( $this->settings[ $key ] ) ) {
				return $this->settings[ $key ];
			}

			return apply_filters( 'woocommerce_quickpay_get_setting_' . $key, ! is_null( $default ) ? $default : '', $this );
		}

		/**
		 * Hook used to display admin notices
		 */
		public function admin_notices() {
			WC_QuickPay_Settings::show_admin_setup_notices();
			WC_QuickPay_Install::show_update_warning();
		}


		/**
		 * add_action_links function.
		 *
		 * Adds action links inside the plugin overview
		 *
		 * @access public static
		 * @return array
		 */
		public static function add_action_links( $links ) {
			$links = array_merge( [
				'<a href="' . WC_QuickPay_Settings::get_settings_page_url() . '">' . __( 'Settings', 'woo-quickpay' ) . '</a>',
			], $links );

			return $links;
		}

		/**
		 * Captures one or several transactions when order state changes to complete.
		 *
		 * @param $post_id
		 *
		 * @return void
		 */
		public function woocommerce_order_status_completed( $post_id ): void {
			// Instantiate new order object
			if ( ! $order = woocommerce_quickpay_get_order( $post_id ) ) {
				return;
			}

			// Only run logic on the correct instance to avoid multiple calls, or if all extra instances has not been loaded.
			if ( ( WC_QuickPay_Statekeeper::$gateways_added && $this->id !== $order->get_payment_method() ) || ! WC_QuickPay_Order_Payments_Utils::is_order_using_quickpay( $order ) ) {
				return;
			}

			// Check the gateway settings.
			if ( apply_filters( 'woocommerce_quickpay_capture_on_order_completion', WC_QuickPay_Helper::option_is_enabled( $this->s( 'quickpay_captureoncomplete' ) ), $order ) ) {
				// Capture only orders that are actual payments (regular orders / recurring payments)
				if ( ! WC_QuickPay_Subscription::is_subscription( $order ) ) {
					$transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $order );

					// Check if there is a transaction ID
					if ( $transaction_id ) {
						try {
							$payment = new WC_QuickPay_API_Payment();

							// Retrieve resource data about the transaction
							$payment->get( $transaction_id );

							// Check if the transaction can be captured
							if ( $payment->is_action_allowed( 'capture' ) ) {

								// In case a payment has been partially captured, we check the balance and subtracts it from the order
								// total to avoid exceptions.
								$amount_multiplied = WC_QuickPay_Helper::price_multiply( $order->get_total(), $payment->get_currency() ) - $payment->get_balance();
								$amount            = WC_QuickPay_Helper::price_multiplied_to_float( $amount_multiplied, $payment->get_currency() );

								$payment->capture( $transaction_id, $order, $amount );
							}
						} catch ( QuickPay_Capture_Exception $e ) {
							woocommerce_quickpay_add_runtime_error_notice( $e->getMessage() );
							$order->add_order_note( $e->getMessage() );
							$this->log->add( $e->getMessage() );
						} catch ( \Exception $e ) {
							$error = sprintf( 'Unable to capture payment on order #%s. Problem: %s', $order->get_id(), $e->getMessage() );
							woocommerce_quickpay_add_runtime_error_notice( $error );
							$order->add_order_note( $error );
							$this->log->add( $error );
						}
					}
				}
			}
		}


		/**
		 * Prints out the description of the gateway. Also adds two checkboxes for viaBill/creditcard for customers to choose how to pay.
		 *
		 * @return void
		 */
		public function payment_fields(): void {
			if ( $description = $this->get_description() ) {
				echo wpautop( wptexturize( $description ) );
			}
		}


		/**
		 * Processing payments on checkout
		 *
		 * @param $order_id
		 *
		 * @return array
		 */
		public function process_payment( $order_id ) {
			return $this->prepare_external_window_payment( woocommerce_quickpay_get_order( $order_id ) );
		}

		/**
		 * Processes a payment
		 *
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		private function prepare_external_window_payment( WC_Order $order ) {
			try {
				// Does the order need a new QuickPay payment?
				$needs_payment = true;

				// Default redirect to
				$redirect_to = $this->get_return_url( $order );

				/** @noinspection NotOptimalIfConditionsInspection */
				if ( wc_string_to_bool( WC_QP()->s( 'subscription_update_card_on_manual_renewal_payment' ) ) && WC_QuickPay_Subscription::is_renewal( $order ) ) {
					WC_Subscriptions_Change_Payment_Gateway::$is_request_to_change_payment = true;
				}

				// Instantiate a new transaction
				$api_transaction = woocommerce_quickpay_get_transaction_instance_by_order( $order );

				// If the order is a subscription or an attempt of updating the payment method
				if ( $api_transaction instanceof WC_QuickPay_API_Subscription ) {
					// Clean up any legacy data regarding old payment links before creating a new payment.
					WC_QuickPay_Order_Payments_Utils::delete_payment_id( $order );
					WC_QuickPay_Order_Payments_Utils::delete_payment_link( $order );
				}
				// If the order contains a product switch and does not need a payment, we will skip the QuickPay
				// payment window since we do not need to create a new payment nor modify an existing.
				else if ( WC_QuickPay_Order_Utils::contains_switch_order( $order ) && ! $order->needs_payment() ) {
					$needs_payment = false;
				}

				if ( $needs_payment ) {
					$redirect_to = woocommerce_quickpay_create_payment_link( $order );
				}

				// Perform redirect
				return [
					'result'   => 'success',
					'redirect' => $redirect_to,
				];

			} catch ( QuickPay_Exception $e ) {
				$e->write_to_logs();
				wc_add_notice( $e->getMessage(), 'error' );
			}
		}

		/**
		 * HOOK: Handles pre-order payments
		 */
		public function process_pre_order_payments( $order ): void {
			// Set order object
			$order = woocommerce_quickpay_get_order( $order );

			// Get transaction ID
			$transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $order );

			// Check if there is a transaction ID
			if ( $transaction_id ) {
				try {
					// Set payment object
					$payment = new WC_QuickPay_API_Payment();
					// Retrieve resource data about the transaction
					$payment->get( $transaction_id );

					// Check if the transaction can be captured
					if ( $payment->is_action_allowed( 'capture' ) ) {
						try {
							// Capture the payment
							$payment->capture( $transaction_id, $order );
						} // Payment failed
						catch ( QuickPay_API_Exception $e ) {
							$this->log->add( sprintf( "Could not process pre-order payment for order: #%s with transaction id: %s. Payment failed. Exception: %s", WC_QuickPay_Order_Utils::get_clean_order_number( $order ), $transaction_id, $e->getMessage() ) );

							$order->update_status( 'failed' );
						}
					}
				} catch ( QuickPay_API_Exception $e ) {
					$this->log->add( sprintf( "Could not process pre-order payment for order: #%s with transaction id: %s. Transaction not found. Exception: %s", WC_QuickPay_Order_Utils::get_clean_order_number( $order ), $transaction_id, $e->getMessage() ) );
				}
			}
		}

		/**
		 * Process refunds
		 * WooCommerce 2.2 or later
		 *
		 * @param int $order_id
		 * @param null|float $amount
		 * @param string $reason
		 *
		 * @return bool|WP_Error
		 */
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			try {
				if ( ! $order = woocommerce_quickpay_get_order( $order_id ) ) {
					throw new QuickPay_Exception( sprintf( 'Could not load the order with ID: %d', $order_id ) );
				}

				$transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $order );

				// Check if there is a transaction ID
				if ( ! $transaction_id ) {
					throw new QuickPay_Exception( sprintf( __( "No transaction ID for order: %s", 'woo-quickpay' ), $order_id ) );
				}

				// Create a payment instance and retrieve transaction information
				$payment = new WC_QuickPay_API_Payment();
				$payment->get( $transaction_id );

				// Check if the transaction can be refunded
				if ( ! $payment->is_action_allowed( 'refund' ) ) {
					if ( in_array( $payment->get_current_type(), [ 'authorize', 'recurring' ], true ) ) {
						throw new QuickPay_Exception( __( 'A non-captured payment cannot be refunded.', 'woo-quickpay' ) );
					}

					throw new QuickPay_Exception( __( 'Transaction state does not allow refunds.', 'woo-quickpay' ) );
				}

				// Perform a refund API request
				$payment->refund( (int) $transaction_id, $order, $amount === null ? null : (float) $amount );

				return true;
			} catch ( QuickPay_Exception $e ) {
				$e->write_to_logs();

				return new WP_Error( 'quickpay_refund_error', $e->getMessage() );
			}
		}

		/**
		 * Clear cart in case its not already done.
		 *
		 * @return void
		 */
		public function thankyou_page() {
			global $woocommerce;
			$woocommerce->cart->empty_cart();
		}

		/**
		 * scheduled_subscription_payment function.
		 *
		 * Runs every time a scheduled renewal of a subscription is required
		 *
		 * @access public
		 *
		 * @param $amount_to_charge
		 * @param WC_Order $renewal_order
		 *
		 * @return mixed|void|null
		 */
		public function scheduled_subscription_payment( $amount_to_charge, WC_Order $renewal_order ) {
			if ( ( $renewal_order->get_payment_method() === $this->id ) && $renewal_order->needs_payment() ) {
				// Create subscription instance
				$transaction = new WC_QuickPay_API_Subscription();

				/** @var WC_Subscription $subscription */
				// Get the subscription based on the renewal order
				$subscription = WC_QuickPay_Subscription::get_subscriptions_for_renewal_order( $renewal_order, true );

				// Get the transaction ID from the subscription
				$transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $subscription );

				// Capture a recurring payment with fixed amount
				$response = $this->process_recurring_payment( $transaction, $transaction_id, $amount_to_charge, $renewal_order );

				do_action( 'woocommerce_quickpay_scheduled_subscription_payment_after', $subscription, $renewal_order, $response, $transaction, $transaction_id, $amount_to_charge );

				return $response;
			}
		}

		/**
		 * Wrapper to process a recurring payment on an order/subscription
		 *
		 * @param WC_QuickPay_API_Subscription $transaction
		 * @param                              $subscription_transaction_id
		 * @param                              $amount_to_charge
		 * @param                              $order
		 *
		 * @return mixed
		 */
		public function process_recurring_payment( WC_QuickPay_API_Subscription $transaction, $subscription_transaction_id, $amount_to_charge, $order ) {
			$order = woocommerce_quickpay_get_order( $order );

			$response = null;

			try {
				// Capture a recurring payment with fixed amount
				[ $response ] = $transaction->recurring( $subscription_transaction_id, $order, $amount_to_charge );
			} catch ( QuickPay_Exception $e ) {
				WC_QuickPay_Order_Payments_Utils::increase_failed_payment_count( $order );
				// Set the payment as failed
				$order->update_status( 'failed', 'Automatic renewal of ' . $order->get_order_number() . ' failed. Message: ' . $e->getMessage() );

				// Write debug information to the logs
				$e->write_to_logs();
			}

			return $response;
		}

		/**
		 * Prevents the failed attempts count to be copied to renewal orders
		 *
		 * @param array $meta
		 *
		 * @return array
		 */
		public function remove_renewal_meta_data( array $meta ): array {
			$avoid_keys = [
				'_quickpay_failed_payment_count',
				'_quickpay_transaction_id',
				'_transaction_id',
				'TRANSACTION_ID', // Prevents the legacy transaction ID from being copied to renewal orders
			];

			foreach ( $avoid_keys as $avoid_key ) {
				if ( ! empty( $meta[ $avoid_key ] ) ) {
					unset( $meta[ $avoid_key ] );
				}
			}

			return $meta;
		}

		/**
		 * Declare gateway's meta data requirements in case of manual payment gateway changes performed by admins.
		 *
		 * @param array $payment_meta
		 *
		 * @param WC_Subscription $subscription
		 *
		 * @return array
		 */
		public function woocommerce_subscription_payment_meta( $payment_meta, $subscription ): array {
			$payment_meta['quickpay'] = [
				'post_meta' => [
					'_quickpay_transaction_id' => [
						'value' => WC_QuickPay_Order_Utils::get_transaction_id( $subscription ),
						'label' => __( 'QuickPay Transaction ID', 'woo-quickpay' ),
					],
				],
			];

			return $payment_meta;
		}

		/**
		 * Check if the transaction ID actually exists as a subscription transaction in the manager.
		 * If not, an exception will be thrown resulting in a validation error.
		 *
		 * @param array $payment_meta
		 *
		 * @param WC_Subscription $subscription
		 *
		 * @throws QuickPay_API_Exception
		 */
		public function woocommerce_subscription_validate_payment_meta( $payment_meta, $subscription ) {
			if ( isset( $payment_meta['post_meta']['_quickpay_transaction_id']['value'] ) ) {
				$transaction_id = $payment_meta['post_meta']['_quickpay_transaction_id']['value'];
				// Validate only if the transaction ID has changed
				$sub_transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $subscription );
				if ( $transaction_id !== $sub_transaction_id ) {
					$transaction = new WC_QuickPay_API_Subscription();
					$transaction->get( $transaction_id );

					// If transaction could be found, add a note on the order for history and debugging reasons.
					$subscription->add_order_note( sprintf( __( 'QuickPay Transaction ID updated from #%d to #%d', 'woo-quickpay' ), $sub_transaction_id, $transaction_id ), 0, true );
				}
			}
		}

		/**
		 * Triggered when customers are changing payment method to QuickPay.
		 *
		 * @param $subscription
		 * @param $old_payment_method
		 */
		public function on_subscription_payment_method_updated_to_quickpay( $subscription, $old_payment_method ): void {
			WC_QuickPay_Order_Payments_Utils::increase_payment_method_change_count( $subscription );
		}


		/**
		 * Cancels a transaction when the subscription is cancelled
		 *
		 * @param WC_Order $order - WC_Order object
		 *
		 * @return void
		 */
		public function subscription_cancellation( WC_Order $order ): void {
			if ( 'cancelled' !== $order->get_status() ) {
				return;
			}

			try {
				if ( WC_QuickPay_Subscription::is_subscription( $order ) && apply_filters( 'woocommerce_quickpay_allow_subscription_transaction_cancellation', true, $order, $this ) ) {
					$transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $order );

					$subscription = new WC_QuickPay_API_Subscription();
					$subscription->get( $transaction_id );

					if ( $subscription->is_action_allowed( 'cancel' ) ) {
						$subscription->cancel( $transaction_id );
					}
				}
			} catch ( QuickPay_Exception $e ) {
				$e->write_to_logs();
			}
		}

		/**
		 * on_order_cancellation function.
		 *
		 * Is called when a customer cancels the payment process from the QuickPay payment window.
		 *
		 * @access public
		 * @return void
		 */
		public function on_order_cancellation( $order_id ) {
			$order = new WC_Order( $order_id );

			// Redirect the customer to account page if the current order is failed
			if ( $order->get_status() === 'failed' ) {
				$payment_failure_text = sprintf( __( '<p><strong>Payment failure</strong> A problem with your payment on order <strong>#%i</strong> occured. Please try again to complete your order.</p>', 'woo-quickpay' ), $order_id );

				wc_add_notice( $payment_failure_text, 'error' );

				wp_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
			}

			$order->add_order_note( __( 'QuickPay Payment', 'woo-quickpay' ) . ': ' . __( 'Cancelled during process', 'woo-quickpay' ) );

			wc_add_notice( __( '<p><strong>%s</strong>: %s</p>', __( 'Payment cancelled', 'woo-quickpay' ), __( 'Due to cancellation of your payment, the order process was not completed. Please fulfill the payment to complete your order.', 'woo-quickpay' ) ), 'error' );
		}

		/**
		 * Is called after a payment has been submitted in the QuickPay payment window.
		 *
		 * @return void
		 */
		public function callback_handler(): void {
			try {
				// Get callback body
				$request_body = file_get_contents( "php://input" );

				// Decode the body into JSON
				$json = json_decode( $request_body, false, 512, JSON_THROW_ON_ERROR );

				// Instantiate payment object
				$payment = new WC_QuickPay_API_Payment( $json );

				// Fetch order number;
				$order_number = WC_QuickPay_Callbacks::get_order_id_from_callback( $json );

				// Fetch subscription post ID if present
				$subscription_id = WC_QuickPay_Callbacks::get_subscription_id_from_callback( $json );
				$subscription    = null;
				if ( $subscription_id !== null ) {
					$subscription = woocommerce_quickpay_get_subscription( $subscription_id );
				}

				if ( $payment->is_authorized_callback( $request_body ) ) {
					// Instantiate order object
					$order = woocommerce_quickpay_get_order( $order_number );

					// Get last transaction in operation history
					$transaction = end( $json->operations );

					// Is the transaction accepted and approved by QP / Acquirer?
					// Did we find an order?
					if ( $json->accepted && $order ) {
						do_action( 'woocommerce_quickpay_accepted_callback_before_processing', $order, $json );
						do_action( 'woocommerce_quickpay_accepted_callback_before_processing_status_' . $transaction->type, $order, $json );

						// Perform action depending on the operation status type
						try {
							switch ( $transaction->type ) {
								//
								// Cancel callbacks are currently not supported by the QuickPay API
								//
								case 'cancel' :
									if ( $subscription_id !== null && $subscription ) {
										do_action( 'woocommerce_quickpay_callback_subscription_cancelled', $subscription, $order, $transaction, $json );
									}

									if ( strtolower( $json->type ) === 'payment' ) {
										WC_QuickPay_Callbacks::payment_cancelled( $order, $json, $transaction );
									}

									break;

								case 'capture' :
									WC_QuickPay_Callbacks::payment_captured( $order, $json );
									break;

								case 'refund' :
									$order->add_order_note( sprintf( __( 'Refunded %s %s', 'woo-quickpay' ), WC_QuickPay_Helper::price_normalize( $transaction->amount, $json->currency ), $json->currency ) );
									break;

								case 'recurring':
									WC_QuickPay_Callbacks::payment_authorized( $order, $json );
									break;

								case 'authorize' :
									WC_QuickPay_Callbacks::authorized( $order, $json );

									// Subscription authorization
									if ( $subscription_id !== null && isset( $subscription ) && strtolower( $json->type ) === 'subscription' ) {
										// Write log
										WC_QuickPay_Callbacks::subscription_authorized( $subscription, $order, $json );
									} // Regular payment authorization
									else {
										WC_QuickPay_Callbacks::payment_authorized( $order, $json );
									}
									break;
							}

							do_action( 'woocommerce_quickpay_accepted_callback', $order, $json );
							do_action( 'woocommerce_quickpay_accepted_callback_status_' . $transaction->type, $order, $json );

						} catch ( QuickPay_API_Exception $e ) {
							$e->write_to_logs();
						}
					}

					// The transaction was not accepted.
					// Print debug information to logs
					else {
						// Write debug information
						$this->log->add( [
							'order'          => $order_number,
							'qp_status_code' => $transaction->qp_status_code,
							'qp_status_msg'  => $transaction->qp_status_msg,
							'aq_status_code' => $transaction->aq_status_code,
							'aq_status_msg'  => $transaction->aq_status_msg,
							'request'        => $request_body,
						] );

						if ( $order && ( $transaction->type === 'recurring' || 'rejected' !== $json->state ) ) {
							$order->update_status( 'failed', sprintf( 'Payment failed <br />QuickPay Message: %s<br />Acquirer Message: %s', $transaction->qp_status_msg, $transaction->aq_status_msg ) );
						}
					}
				} else {
					$this->log->add( sprintf( __( 'Invalid callback body for order #%s.', 'woo-quickpay' ), $order_number ) );
				}
			} catch ( JsonException $e ) {
				wp_send_json_error( 'Invalid request', 400 );
			}

		}

		/**
		 * @param WC_Order $order
		 * @param                   $json
		 */
		public function callback_update_transaction_cache( WC_Order $order, $json ): void {
			try {
				// Instantiating a payment transaction.
				// The type of transaction is currently not important for caching - hence no logic for handling subscriptions is added.
				$transaction = new WC_QuickPay_API_Payment( $json );
				$transaction->cache_transaction();
			} catch ( QuickPay_Exception $e ) {
				$this->log->add( sprintf( 'Could not cache transaction from callback for order: #%s -> %s', $order->get_id(), $e->getMessage() ) );
			}
		}

		/**
		 * Initiates the plugin settings form fields
		 */
		public function init_form_fields(): void {
			$this->form_fields = WC_QuickPay_Settings::get_fields();
		}


		/**
		 * @param array $form_fields
		 * @param bool $echo
		 *
		 * @return string|void
		 */
		public function generate_settings_html( $form_fields = array(), $echo = true ) {
			$html = sprintf( "<p><small>Version: %s</small>", WCQP_VERSION );
			$html .= "<p>" . sprintf( __( 'Allows you to receive payments via %s', 'woo-quickpay' ), $this->get_method_title() ) . "</p>";
			$html .= WC_QuickPay_Settings::clear_logs_section();

			ob_start();
			do_action( 'woocommerce_quickpay_settings_table_before' );
			$html .= ob_get_clean();

			$html .= parent::generate_settings_html( $form_fields, $echo );

			ob_start();
			do_action( 'woocommerce_quickpay_settings_table_after' );
			$html .= ob_get_clean();

			if ( $echo ) {
				echo $html; // WPCS: XSS ok.
			} else {
				return $html;
			}
		}


		/**
		 * Adds custom text to the order confirmation email.
		 *
		 * @param WC_Order $order
		 * @param boolean $sent_to_admin
		 *
		 * @return void /string/void
		 */
		public function email_instructions( WC_Order $order, bool $sent_to_admin ): void {
			$payment_method = $order->get_payment_method();

			if ( $payment_method !== 'quickpay' || $sent_to_admin || ( $order->get_status() !== 'processing' && $order->get_status() !== 'completed' ) ) {
				return;
			}

			if ( $this->instructions ) {
				echo wpautop( wptexturize( $this->instructions ) );
			}
		}


		/**
		 * FILTER: apply_gateway_icons function.
		 *
		 * Sets gateway icons on frontend
		 *
		 * @return void
		 */
		public function apply_gateway_icons( $icon, $id ) {
			if ( $id === $this->id ) {
				$icon = '';

				$icons = $this->s( 'quickpay_icons' );

				if ( ! empty( $icons ) ) {
					$icons_maxheight = $this->gateway_icon_size();

					foreach ( $icons as $key => $item ) {
						$icon .= $this->gateway_icon_create( $item, $icons_maxheight );
					}
				}
			}

			return $icon;
		}


		/**
		 * gateway_icon_create
		 *
		 * Helper to get the a gateway icon image tag
		 *
		 * @access protected
		 * @return string
		 */
		protected function gateway_icon_create( $icon, $max_height ) {
			$icon = str_replace( 'quickpay_', '', $icon );

			$icon = apply_filters( 'woocommerce_quickpay_checkout_gateway_icon', $icon );

			if ( file_exists( __DIR__ . '/assets/images/cards/' . $icon . '.svg' ) ) {
				$icon_url = WC_HTTPS::force_https_url( plugin_dir_url( __FILE__ ) . 'assets/images/cards/' . $icon . '.svg' );
			} else {
				$icon_url = WC_HTTPS::force_https_url( plugin_dir_url( __FILE__ ) . 'assets/images/cards/' . $icon . '.png' );
			}

			$icon_url = apply_filters( 'woocommerce_quickpay_checkout_gateway_icon_url', $icon_url, $icon );

			return '<img src="' . $icon_url . '" alt="' . esc_attr( $this->get_title() ) . '" style="max-height:' . $max_height . '"/>';
		}


		/**
		 * gateway_icon_size
		 *
		 * Helper to get the a gateway icon image max height
		 *
		 * @access protected
		 * @return void
		 */
		protected function gateway_icon_size() {
			$settings_icons_maxheight = $this->s( 'quickpay_icons_maxheight' );

			return ! empty( $settings_icons_maxheight ) ? $settings_icons_maxheight . 'px' : '20px';
		}


		/**
		 * Show plugin changes. Code adapted from W3 Total Cache.
		 *
		 * @param $args
		 *
		 * @return void
		 */
		public static function in_plugin_update_message( $args ): void {
			$transient_name = 'wcqp_upgrade_notice_' . $args['Version'];
			if ( false === ( $upgrade_notice = get_transient( $transient_name ) ) ) {
				$response = wp_remote_get( 'https://plugins.svn.wordpress.org/woocommerce-quickpay/trunk/README.txt' );

				if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
					$upgrade_notice = self::parse_update_notice( $response['body'] );
					set_transient( $transient_name, $upgrade_notice, DAY_IN_SECONDS );
				}
			}

			echo wp_kses_post( $upgrade_notice );
		}

		/**
		 *
		 * parse_update_notice
		 *
		 * Parse update notice from readme file.
		 *
		 * @param string $content
		 *
		 * @return string
		 */
		private static function parse_update_notice( $content ): string {
			// Output Upgrade Notice
			$matches        = null;
			$regexp         = '~==\s*Upgrade Notice\s*==\s*=\s*(.*)\s*=(.*)(=\s*' . preg_quote( WCQP_VERSION, '/' ) . '\s*=|$)~Uis';
			$upgrade_notice = '';

			if ( preg_match( $regexp, $content, $matches ) ) {
				$version = trim( $matches[1] );
				$notices = (array) preg_split( '~[\r\n]+~', trim( $matches[2] ) );

				if ( version_compare( WCQP_VERSION, $version, '<' ) ) {

					$upgrade_notice .= '<div class="wc_plugin_upgrade_notice">';

					foreach ( $notices as $index => $line ) {
						$upgrade_notice .= wp_kses_post( preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line ) );
					}

					$upgrade_notice .= '</div> ';
				}
			}

			return wp_kses_post( $upgrade_notice );
		}

		/**
		 * path
		 *
		 * Returns a plugin URL path
		 *
		 * @param $path
		 *
		 * @return string
		 */
		public function plugin_url( $path ): string {
			return plugins_url( $path, __FILE__ );
		}

		/**
		 * @param string $path
		 *
		 * @return string
		 */
		public function plugin_path( string $path ): string {
			return __DIR__ . $path;
		}
	}

	/**
	 * Make the object available for later use
	 *
	 * @return WC_QuickPay
	 */
	function WC_QP(): WC_QuickPay {
		return WC_QuickPay::get_instance();
	}

	// Instantiate
	WC_QP();
	WC_QP()->hooks_and_filters();

	// Add the gateway to WooCommerce
	function add_quickpay_gateway( $methods ) {
		$methods[] = 'WC_QuickPay';

		WC_QuickPay_Statekeeper::$gateways_added = true;

		return apply_filters( 'woocommerce_quickpay_load_instances', $methods );
	}

	add_filter( 'woocommerce_payment_gateways', 'add_quickpay_gateway' );
	add_filter( 'woocommerce_quickpay_load_instances', 'WC_QuickPay::filter_load_instances' );
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'WC_QuickPay::add_action_links' );
}

register_activation_hook( __FILE__, static function () {
	require_once WCQP_PATH . 'classes/woocommerce-quickpay-install.php';

	// Run the installer on the first install.
	if ( WC_QuickPay_Install::is_first_install() ) {
		WC_QuickPay_Install::install();
	}
} );

add_action( 'before_woocommerce_init', function () {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
