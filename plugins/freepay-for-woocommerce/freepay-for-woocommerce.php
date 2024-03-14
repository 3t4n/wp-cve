<?php
/**
 * Plugin Name: FreePay for WooCommerce
 * Plugin URI: http://wordpress.org/plugins/freepay-for-woocommerce/
 * Description: Integrates your FreePay payment gateway into your WooCommerce installation.
 * Version: 2.0.2
 * Author: Freepay
 * Text Domain: freepay-for-woocommerce
 * Domain Path: /languages
 * Author URI: https://freepay.dk
 * 
 * WC requires at least: 4.0.0
 * WC tested up to: 8.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WCFP_VERSION', '2.0.2' );
define( 'WCFP_URL', plugins_url( __FILE__ ) );
define( 'WCFP_PATH', plugin_dir_path( __FILE__ ) );

add_action( 'plugins_loaded', 'init_freepay_gateway', 0 );

/**
 * Adds notice in case of WooCommerce being inactive
 */
function wc_freepay_woocommerce_inactive_notice() {
	$class    = 'notice notice-error';
	$headline = esc_html__( 'Woo FreePay requires WooCommerce to be active.', 'freepay-for-woocommerce' );
	$message  = esc_html__( 'Go to the plugins page to activate WooCommerce', 'freepay-for-woocommerce' );
	printf( '<div class="%1$s"><h2>%2$s</h2><p>%3$s</p></div>', $class, $headline, $message );
}

function init_freepay_gateway() {
	$pluginChecked = false;

	if(function_exists('is_multisite') && is_multisite()){
		if(!function_exists( 'is_plugin_active_for_network')){
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if(!is_plugin_active_for_network( 'woocommerce/woocommerce.php' )) {
			add_action( 'admin_notices', 'wc_freepay_woocommerce_inactive_notice' );	
			return;
		}

		$pluginChecked = true;
	}

	if ( !$pluginChecked && !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		add_action( 'admin_notices', 'wc_freepay_woocommerce_inactive_notice' );

		return;
	}

	// Import helper classes
	require_once WCFP_PATH . 'helpers/notices.php';
	require_once WCFP_PATH . 'classes/woo-freepay-install.php';
	require_once WCFP_PATH . 'classes/api/woo-freepay-api.php';
	require_once WCFP_PATH . 'classes/api/woo-freepay-api-transaction.php';
	require_once WCFP_PATH . 'classes/api/woo-freepay-api-payment.php';
	require_once WCFP_PATH . 'classes/api/woo-freepay-api-subscription.php';
	require_once WCFP_PATH . 'classes/woo-freepay-statekeeper.php';
	require_once WCFP_PATH . 'classes/woo-freepay-exceptions.php';
	require_once WCFP_PATH . 'classes/woo-freepay-log.php';
	require_once WCFP_PATH . 'classes/woo-freepay-helper.php';
	require_once WCFP_PATH . 'classes/woo-freepay-address.php';
	require_once WCFP_PATH . 'classes/woo-freepay-settings.php';
	require_once WCFP_PATH . 'classes/woo-freepay-countries.php';
	require_once WCFP_PATH . 'classes/utils/woo-freepay-order-utils.php';
	require_once WCFP_PATH . 'classes/utils/woo-freepay-payment-utils.php';
	require_once WCFP_PATH . 'classes/utils/woo-freepay-subscription-utils.php';
	require_once WCFP_PATH . 'classes/woo-freepay-views.php';
	require_once WCFP_PATH . 'classes/woo-freepay-callbacks.php';
	require_once WCFP_PATH . 'helpers/transactions.php';
	require_once WCFP_PATH . 'helpers/requests.php';


	// Main class
	class WC_FreePay extends WC_Payment_Gateway {

		/**
		 * $_instance
		 * @var mixed
		 * @access public
		 * @static
		 */
		public static $_instance = null;

		/**
		 * @var WC_FreePay_Log
		 */
		public $log;

		/**
		 * get_instance
		 *
		 * Returns a new instance of self, if it does not already exist.
		 *
		 * @access public
		 * @static
		 * @return WC_FreePay
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
			$this->id           = 'freepay';
			$this->method_title = 'FreePay';
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
				'subscription_payment_method_change_customer',
				'refunds',
				'multiple_subscriptions',
			];

			$this->log = new WC_FreePay_Log();

			// Load the form fields and settings
			$this->init_form_fields();
			$this->init_settings();

			// Get gateway variables
			$this->title             = $this->s( 'title' );
			$this->description       = $this->s( 'description' );

			do_action( 'woo_freepay_loaded' );
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
			add_action( 'woocommerce_api_wc_' . $this->id, [ $this, 'callback_handler' ] );
			add_action( 'woocommerce_order_status_completed', [ $this, 'woocommerce_order_status_completed' ] );
			add_action( 'woocommerce_before_thankyou', [ $this, 'woocommerce_order_before_thankyou' ], 10, 1 );

			// WooCommerce Subscriptions hooks/filters
			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, [ $this, 'scheduled_subscription_payment' ], 10, 2 );
			add_action( 'woocommerce_subscription_cancelled_' . $this->id, [ $this, 'subscription_cancellation' ] );
			add_action( 'woocommerce_subscription_payment_method_updated_to_' . $this->id, [ $this, 'on_subscription_payment_method_updated_to_freepay', ], 10, 2 );
			add_filter( 'wcs_renewal_order_meta_query', [ $this, 'remove_failed_freepay_attempts_meta_query' ], 10 );
			add_filter( 'wcs_renewal_order_meta_query', [ $this, 'remove_legacy_transaction_id_meta_query' ], 10 );
			add_filter( 'woocommerce_subscription_payment_meta', [ $this, 'woocommerce_subscription_payment_meta' ], 10, 2 );
			add_action( 'woocommerce_subscription_validate_payment_meta_' . $this->id, [ $this, 'woocommerce_subscription_validate_payment_meta', ], 10, 2 );
			add_filter( 'wps_sfw_supported_payment_gateway_for_woocommerce', [ $this, 'add_supported_gateway_for_wps_sfw' ], 10, 2 );
			add_action( 'wps_sfw_other_payment_gateway_renewal', array( $this, 'wps_sfw_process_subscription_payment' ), 10, 3 );
			add_action( 'wps_sfw_subscription_cancel', array( $this, 'wps_sfw_cancel_subscription' ), 10, 2 );

			if ( ! has_action( 'init', 'WC_FreePay_Helper::load_i18n' ) ) {
				if ( is_admin() ) {
					add_action( 'admin_enqueue_scripts', 'WC_FreePay_Helper::enqueue_stylesheet' );
					add_action( 'admin_enqueue_scripts', 'WC_FreePay_Helper::enqueue_javascript_backend' );
					add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
					add_action( 'wp_ajax_freepay_manual_transaction_actions', [ $this, 'ajax_freepay_manual_transaction_actions' ] );
					add_action( 'wp_ajax_freepay_empty_logs', [ $this, 'ajax_empty_logs' ] );
					add_action( 'wp_ajax_freepay_flush_cache', [ $this, 'ajax_flush_cache' ] );
					add_filter( 'woocommerce_admin_order_actions', [ $this, 'add_custom_order_status_actions_button'], 10, 3 );
					add_action( 'admin_head', [ $this, 'add_custom_order_status_actions_button_css' ] );
				}

				add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 10, 2 );

				if ( true /*WC_FreePay_Helper::option_is_enabled( $this->s( 'freepay_orders_transaction_info', 'yes' ) ) */) {
					add_filter( 'manage_edit-shop_order_columns', [ $this, 'filter_shop_order_posts_columns' ], 10, 1 );
					add_filter( 'manage_shop_order_posts_custom_column', [ $this, 'apply_custom_order_data' ], 10, 2 );
					add_filter( 'manage_shop_subscription_posts_custom_column', [ $this, 'apply_custom_order_data' ], 10, 2 );
					add_action( 'woo_freepay_accepted_callback', [ $this, 'callback_update_transaction_cache' ], 10, 2 );

					add_filter( 'woocommerce_shop_order_list_table_columns', function ( $columns ) {
						return WC_FreePay_Helper::array_insert_after( 'shipping_address', $columns, 'freepay_transaction_info', __( 'Payment', 'freepay-for-woocommerce' ) );
					} );
					
					add_action( 'woocommerce_shop_order_list_table_custom_column', [ $this, 'apply_custom_order_data' ], 10, 2 );
				}

				add_action( 'admin_notices', [ $this, 'admin_notices' ] );
			}

			add_action( 'init', 'WC_FreePay_Helper::load_i18n' );
			add_filter( 'woocommerce_gateway_icon', [ $this, 'apply_gateway_icons' ], 2, 3 );

			// Third party plugins
			add_filter( 'qtranslate_language_detect_redirect', 'WC_FreePay_Helper::qtranslate_prevent_redirect', 10, 3 );
			add_filter( 'wpss_misc_form_spam_check_bypass', 'WC_FreePay_Helper::spamshield_bypass_security_check', - 10, 1 );

			if(!empty($this->s('freepay_payment_decline_message'))) {
				add_filter( 'woocommerce_order_cancelled_notice', [ $this, 'overrideCancelMessage' ], 10, 1 );
			}

			add_action( 'woocommerce_cancelled_order', 'WC_FreePay_Order_Utils::logDeclinePaymentData' );
		}

		function add_supported_gateway_for_wps_sfw($wps_supported_method, $payment_method) {
			if($payment_method == 'freepay') {
				array_push($wps_supported_method, $payment_method);
			}

			return $wps_supported_method;
		}

		function overrideCancelMessage() {
			return $this->s('freepay_payment_decline_message');
		}

		function add_custom_order_status_actions_button_css() {
			$action_slug = "freepay_capture"; // The key slug defined for your action button
		
			echo '<style>.wc-action-button-'.$action_slug.'::after { font-family: woocommerce !important; content: "\e01e" !important; }</style>';
		}

		function add_custom_order_status_actions_button( $actions, $order ) {
			try {
				// Display the button for all orders that have a 'processing' status
				if ( $order->has_status( array( 'processing' ) ) ) {

					$transaction_id = WC_FreePay_Order_Utils::get_transaction_id($order);
					$transaction = new WC_FreePay_API_Payment();

					try {
						$transaction->maybe_load_transaction_from_cache( $transaction_id );
					} catch ( FreePay_API_Exception $e ) {
						return $actions;
					} catch ( FreePay_Exception $e ) {
						return $actions;
					}					

					$status = $transaction->get_state();
					
					if($status != "capture" && $status != "partcapture") {
						// The key slug defined for your action button
						$action_slug = 'freepay_capture';
								
						// Set the action button
						$actions[$action_slug] = array(
							'url'		=> wp_nonce_url( admin_url( 'admin-ajax.php?action=freepay_manual_transaction_actions&freepay_sync=1&freepay_action=capture&freepay_postid=' . $order->get_id() ), 'freepay-payment-capture' ),
							'name'      => __( 'Capture', 'freepay-for-woocommerce' ),
							'action'    => $action_slug,
						);
					}
				}
			} catch ( FreePay_API_Exception $e ) {
				$this->log->add( sprintf( 'Order list: #%s - %s', $order->get_id(), $e->getMessage() ) );
			} catch ( FreePay_Exception $e ) {
				$this->log->add( sprintf( 'Order list: #%s - %s', $order->get_id(), $e->getMessage() ) );
			}

			return $actions;
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

			return apply_filters( 'woo_freepay_get_setting_' . $key, ! is_null( $default ) ? $default : '', $this );
		}

		/**
		 * Hook used to display admin notices
		 */
		public function admin_notices() {
			WC_FreePay_Settings::show_admin_setup_notices();
			//WC_FreePay_Install::show_update_warning();
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
				'<a href="' . WC_FreePay_Settings::get_settings_page_url() . '">' . __( 'Settings', 'freepay-for-woocommerce' ) . '</a>',
			], $links );

			return $links;
		}


		/**
		 * ajax_freepay_manual_transaction_actions function.
		 *
		 * Ajax method taking manual transaction requests from wp-admin.
		 *
		 * @access public
		 * @return void
		 */
		public function ajax_freepay_manual_transaction_actions() {
			if ( isset( $_REQUEST['freepay_action'] ) and isset( $_REQUEST['freepay_postid'] ) ) {
				$param_action = $_REQUEST['freepay_action'];

				$supportedActions = [ 'capture', 'refund', 'cancel' ];
				if(!in_array($param_action, $supportedActions)) {
					throw new FreePay_API_Exception( sprintf( "Unsupported action: %s.", $param_action ) );
				}

				$param_post   = (int)$_REQUEST['freepay_postid'];

				$order = wc_get_order( $param_post );

				try {
					$transaction_id = WC_FreePay_Order_Utils::get_transaction_id($order);

					// Subscription
					if ( WC_FreePay_Subscription_Utils::is_wcs_subscription( $order ) ) {
						$payment = new WC_FreePay_API_Subscription();
					} // Payment
					else {
						$payment = new WC_FreePay_API_Payment();
					}

					$payment->get( $transaction_id );

					// Check if the action method is available in the payment class
					if ( method_exists( $payment, $param_action ) ) {
						// Fetch amount if sent.
						$amount = isset( $_REQUEST['freepay_amount'] ) ? WC_FreePay_Helper::price_custom_to_multiplied( $_REQUEST['freepay_amount'] ) : $payment->get_remaining_balance();

						// Call the action method and parse the transaction id and order object
						call_user_func_array( [ $payment, $param_action ], [
							$transaction_id,
							$order,
							WC_FreePay_Helper::price_multiplied_to_float( $amount ),
						] );

						$payment->get( $transaction_id );
						$payment->cache_transaction();
					} else {
						throw new FreePay_API_Exception( sprintf( "Unsupported action: %s.", $param_action ) );
					}
				} catch ( FreePay_Exception $e ) {
					echo $e->getMessage();
					$e->write_to_logs();
					exit;
				} catch ( FreePay_API_Exception $e ) {
					echo $e->getMessage();
					$e->write_to_logs();
					exit;
				}
			}

			if($_REQUEST['freepay_sync'] === '1') {
				wp_safe_redirect( admin_url( 'edit.php?post_type=shop_order' ) );
				exit;
			}
		}

		/**
		 * ajax_empty_logs function.
		 *
		 * Ajax method to empty the debug logs
		 *
		 * @access public
		 * @return json
		 */
		public function ajax_empty_logs() {
			if ( WC_FreePay_Helper::can_user_empty_logs() ) {
				$this->log->clear();
				echo json_encode( [ 'status' => 'success', 'message' => 'Logs successfully emptied' ] );
				exit;
			}
		}

		/**
		 * ajax_empty_logs function.
		 *
		 * Ajax method to empty the debug logs
		 *
		 * @access public
		 * @return json
		 */
		public function ajax_flush_cache() {
			global $wpdb;
			if ( WC_FreePay_Helper::can_user_flush_cache() ) {
				$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wcfp_transaction_%' OR option_name LIKE '_transient_timeout_wcfp_transaction_%'" );
				echo json_encode( [ 'status' => 'success', 'message' => 'The transaction cache has been cleared.' ] );
				exit;
			}
		}

		public function woocommerce_order_before_thankyou( $order_id ) {
			// Instantiate new order object
			$order = wc_get_order( $order_id );
			
			// Only run logic on the correct instance to avoid multiple calls, or if all extra instances has not been loaded.
			if ( ( WC_FreePay_Statekeeper::$gateways_added && $this->id !== $order->get_payment_method() ) || ! WC_FreePay_Order_Utils::has_freepay_payment($order) ) {
				return;
			}

			$isFailedOrder = false;

			if($order) {
				$isFailedOrder = $order->get_status() == 'failed';

				$order->read_meta_data(true);
				$step_idx = WC_FreePay_Order_Utils::get_authorization_step($order);

				if(!empty($step_idx) && !$isFailedOrder) {
					$order->add_order_note( __( 'Payment authorization already started (Accept url)', 'freepay-for-woocommerce' ) );
					return;
				}
				else {
					$order->add_order_note( __( 'Payment authorization step 1 (Accept url)', 'freepay-for-woocommerce' ) );
					WC_FreePay_Order_Utils::set_authorization_step($order, 1);
				}
			}

			$isSubscription = false;
			$isAuthorizationPresent = false;
			$authorizationId = null;

			if(array_key_exists('authorizationIdentifier', $_GET)) {
				$authorizationId = $_GET["authorizationIdentifier"];
			}

			if ( ! empty( $authorizationId ) && $authorizationId != '00000000-0000-0000-0000-000000000000' ) {
				$isAuthorizationPresent = true;
			}

			if(!$isAuthorizationPresent && array_key_exists('savedCardIdentifier', $_GET)) {
				$subscription_id = $_GET['savedCardIdentifier'];
				$isSubscription = true;

				if ( ! empty( $subscription_id ) && $subscription_id != '00000000-0000-0000-0000-000000000000' ) {
					$authorizationId = $subscription_id;
				}
			}

			if($order && !empty($authorizationId)) {
				if($isSubscription) {
					$payment = new WC_FreePay_API_Subscription();
				}
				else {
					$payment = new WC_FreePay_API_Payment();
				}
				
				$payment->get( $authorizationId );

				if($payment->is_valid( $order_id, WC_FreePay_Helper::price_multiply($order->get_total()) )) {
					$order->payment_complete( $authorizationId );

					$transaction['authorizationIdentifier'] = $authorizationId;
					WC_FreePay_Callbacks::save_transaction_id_fallback( $order, $transaction );

					$order->add_order_note( sprintf( __( 'Payment authorized on Accept. Transaction ID: %s', 'freepay-for-woocommerce' ), $authorizationId ) );

					if($isFailedOrder) {
						wp_redirect( $_SERVER['REQUEST_URI'] );
					}
				}
				else {
					WC_FreePay_Order_Utils::set_authorization_step($order, 0);

					//subscription with failed order (renew + update)
					if($isFailedOrder && $isSubscription) {
						sleep(10);
						wp_redirect( $_SERVER['REQUEST_URI'] );
					}
				}
			}
			else {
				WC_FreePay_Order_Utils::set_authorization_step($order, 0);
			}
		}

		/**
		 * woocommerce_order_status_completed function.
		 *
		 * Captures one or several transactions when order state changes to complete.
		 *
		 * @access public
		 * @return void
		 */
		public function woocommerce_order_status_completed( $post_id ) {
			// Instantiate new order object
			$order = wc_get_order( $post_id );
			
			// Only run logic on the correct instance to avoid multiple calls, or if all extra instances has not been loaded.
			if ( ( WC_FreePay_Statekeeper::$gateways_added && $this->id !== $order->get_payment_method() ) || ! WC_FreePay_Order_Utils::has_freepay_payment($order) ) {
				return;
			}

			// Check the gateway settings.
			if ( apply_filters( 'woo_freepay_capture_on_order_completion', WC_FreePay_Helper::option_is_enabled( $this->s( 'freepay_captureoncomplete' ) ), $order ) ) {
				// Capture only orders that are actual payments (regular orders / recurring payments)
				if ( ! WC_FreePay_Subscription_Utils::is_wcs_subscription( $order ) ) {
					$transaction_id = WC_FreePay_Order_Utils::get_transaction_id($order);
					$payment        = new WC_FreePay_API_Payment();

					// Check if there is a transaction ID
					if ( $transaction_id ) {
						try {
							// Retrieve resource data about the transaction
							$payment->get( $transaction_id );

							if ( $payment->can_i_capture() ) {
								// In case a payment has been partially captured, we check the balance and subtracts it from the order
								// total to avoid exceptions.
								$amount_multiplied = WC_FreePay_Helper::price_multiply( $order->get_total() ) - $payment->get_balance();
								$amount            = WC_FreePay_Helper::price_multiplied_to_float( $amount_multiplied );

								if($payment->get_remaining_balance() > 0 && $payment->get_balance() == 0) {
									//check capture balance as multi capture is not supported
									$payment->capture( $transaction_id, $order, $amount );
									$payment->get( $transaction_id );
									$payment->cache_transaction();
								}
							}
						} catch ( FreePay_Capture_Exception $e ) {
							$order->update_status( 'failed' );
							woo_freepay_add_runtime_error_notice( $e->getMessage() );
							$order->add_order_note( $e->getMessage() );
							$this->log->add( $e->getMessage() );
						} catch ( \Exception $e ) {
							$error = sprintf( 'Unable to capture payment on order #%s. Problem: %s', $order->get_id(), $e->getMessage() );
							woo_freepay_add_runtime_error_notice( $error );
							$order->add_order_note( $error );
							$this->log->add( $error );
						}
					}
				}
			}
		}


		/**
		 * payment_fields function.
		 *
		 * Prints out the description of the gateway.
		 *
		 * @access public
		 * @return void
		 */
		public function payment_fields() {
			if ( $this->description ) {
				echo wpautop( wptexturize( $this->description ) );
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
			return $this->prepare_external_window_payment( woo_freepay_get_order($order_id) );
		}

		/**
		 * Processes a payment if embedded payments are disabled.
		 *
		 * @param WC_Order $order
		 *
		 * @return array
		 */
		private function prepare_external_window_payment( $order ) {
			try {

				// Does the order need a new FreePay payment?
				$needs_payment = true;

				// Default redirect to
				$redirect_to = $this->get_return_url( $order );

				// Instantiate a new transaction
				$api_transaction = woo_freepay_get_transaction_instance_by_order( $order );

				// If the order is a subscripion or an attempt of updating the payment method
				if ( $api_transaction instanceof WC_FreePay_API_Subscription ) {
					// Clean up any legacy data regarding old payment links before creating a new payment.
					WC_FreePay_Payment_Utils::delete_payment_link($order);
				}
				// If the order contains a product switch and does not need a payment, we will skip
				// the FreePay payment window since we do not need to create a new payment nor modify an existing.
				else if ( WC_FreePay_Order_Utils::order_contains_switch($order) && ! $order->needs_payment() ) {
					$needs_payment = false;
				}

				if ( $needs_payment ) {
					$redirect_to = woo_freepay_create_payment_link( $order );
				}

				// Perform redirect
				return [
					'result'   => 'success',
					'redirect' => $redirect_to,
				];

			} catch ( FreePay_Exception $e ) {
				$e->write_to_logs();
				wc_add_notice( $e->getMessage(), 'error' );
			}
		}

		/**
		 * Process refunds
		 * WooCommerce 2.2 or later
		 *
		 * @param int $order_id
		 * @param float $amount
		 * @param string $reason
		 *
		 * @return bool|WP_Error
		 */
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			try {
				$order = wc_get_order( $order_id );

				$transaction_id = WC_FreePay_Order_Utils::get_transaction_id($order);

				// Check if there is a transaction ID
				if ( ! $transaction_id ) {
					throw new FreePay_Exception( sprintf( __( "No transaction ID for order: %s", 'freepay-for-woocommerce' ), $order_id ) );
				}

				// Create a payment instance and retrieve transaction information
				$payment = new WC_FreePay_API_Payment();
				$payment->get( $transaction_id );

				if(!$payment->can_i_refund()) {
					throw new FreePay_Exception( __( 'Transaction state does not allow refunds.', 'freepay-for-woocommerce' ) );
				}

				// Perform a refund API request
				$payment->refund( $transaction_id, $order, $amount, $reason );

				return true;
			} catch ( FreePay_Exception $e ) {
				$e->write_to_logs();

				return new WP_Error( 'freepay_refund_error', $e->getMessage() );
			}
		}

		/**
		 * Clear cart in case its not already done.
		 *
		 * @return [type] [description]
		 */
		public function thankyou_page() {
			global $woocommerce;
			$woocommerce->cart->empty_cart();
		}

		public function wps_sfw_cancel_subscription($wps_subscription_id, $status) {
			if ( 'Cancel' == $status ) {
				if(!WC_FreePay_Helper::is_HPOS_enabled()) {
					update_post_meta( $wps_subscription_id, 'wps_subscription_status', 'cancelled' );
				}
				else {
					$subscription_order = wc_get_order($wps_subscription_id);
					if($subscription_order) {
						$subscription_order->update_meta_data( 'wps_subscription_status', 'cancelled' );
						$subscription_order->save_meta_data();
					}
				}
			}
		}

		public function wps_sfw_process_subscription_payment( $order, $subscription_id, $payment_method ) {
			if ( $order && is_object( $order ) ) {
				if(!WC_FreePay_Helper::is_HPOS_enabled()) {
					$order_id = $order->get_id();
					$payment_method = get_post_meta( $order_id, '_payment_method', true );
					$wps_sfw_renewal_order = get_post_meta( $order_id, 'wps_sfw_renewal_order', true );
				}
				else
				{
					$order->read_meta_data(true);
					$wps_sfw_renewal_order = $order->get_meta( 'wps_sfw_renewal_order', true );
					$payment_method = $order->get_meta( '_payment_method' );
				}				

				if ( $this->id == $payment_method && 'yes' == $wps_sfw_renewal_order ) {
					$subscription_order = wc_get_order($subscription_id);
					$freepay_transaction_id = $subscription_order->get_meta( '_freepay_transaction_id', true );

					if ( isset( $freepay_transaction_id ) && ! empty( $freepay_transaction_id ) ) {
						if ( 0 == $order->get_total() ) {
							$order->payment_complete();
							return;
						}

						// Create subscription instance
						$transaction = new WC_FreePay_API_Subscription();
		
						// Capture a recurring payment with fixed amount
						return $this->process_recurring_payment( $transaction, $freepay_transaction_id, $order->get_total(), $order, true );
					}
				}
			}
		}

		/**
		 * scheduled_subscription_payment function.
		 *
		 * @param $amount_to_charge
		 * @param WC_Order $renewal_order
		 * 
		 * Runs every time a scheduled renewal of a subscription is required
		 *
		 * @access public
		 * @return The API response
		 */
		public function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {
			if ( $renewal_order->get_payment_method() === $this->id ) {
				if ( $renewal_order->needs_payment() ) {
					// Create subscription instance
					$transaction = new WC_FreePay_API_Subscription();

					/** @var WC_Subscription $subscription */
					// Get the subscription based on the renewal order
					$subscription = WC_FreePay_Subscription_Utils::get_subscriptions_for_renewal_order( $renewal_order, true );

					// Make new instance to properly get the transaction ID with built in fallbacks.
					$subscription_order = wc_get_order( $subscription->get_id() );

					// Get the transaction ID from the subscription
					$transaction_id = WC_FreePay_Order_Utils::get_transaction_id($subscription_order);

					// Capture a recurring payment with fixed amount
					return $this->process_recurring_payment( $transaction, $transaction_id, $amount_to_charge, $renewal_order, true );
				}
			}
		}

		/**
		 * Wrapper to process a recurring payment on an order/subscription
		 *
		 * @param WC_FreePay_API_Subscription $transaction
		 * @param                              $subscription_transaction_id
		 * @param                              $amount_to_charge
		 * @param WC_Order  $order
		 *
		 * @return mixed
		 */
		public function process_recurring_payment( WC_FreePay_API_Subscription $transaction, $subscription_transaction_id, $amount_to_charge, $order, $is_renewal = true ) {
			$response = null;
			try {
				// Capture a recurring payment with fixed amount
				$response = $transaction->recurring( $subscription_transaction_id, $order, $amount_to_charge );

				// Process the recurring payment on the orders
				WC_FreePay_Subscription_Utils::process_recurring_response( $response[0], $order, $subscription_transaction_id, $is_renewal );

				// Reset failed attempts.
				WC_FreePay_Payment_Utils::reset_failed_freepay_payment_count($order);
			} catch ( FreePay_Exception $e ) {
				WC_FreePay_Payment_Utils::increase_failed_freepay_payment_count($order);

				// Set the payment as failed
				$order->update_status( 'failed', 'Automatic renewal of ' . $order->get_order_number() . ' failed. Message: ' . $e->getMessage() );

				// Write debug information to the logs
				$e->write_to_logs();
			} catch ( FreePay_API_Exception $e ) {
				WC_FreePay_Payment_Utils::increase_failed_freepay_payment_count($order);

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
		 * @param $order_meta_query
		 *
		 * @return string
		 */
		public function remove_failed_freepay_attempts_meta_query( $order_meta_query ) {
			$order_meta_query .= " AND `meta_key` NOT IN ('" . WC_FreePay_Payment_Utils::META_FAILED_PAYMENT_COUNT . "')";
			$order_meta_query .= " AND `meta_key` NOT IN ('_freepay_transaction_id')";
			$order_meta_query .= " AND `meta_key` NOT IN ('_transaction_id')";

			return $order_meta_query;
		}

		/**
		 * Prevents the legacy transaction ID from being copied to renewal orders
		 *
		 * @param $order_meta_query
		 *
		 * @return string
		 */
		public function remove_legacy_transaction_id_meta_query( $order_meta_query ) {
			$order_meta_query .= " AND `meta_key` NOT IN ('TRANSACTION_ID')";

			return $order_meta_query;
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
		public function woocommerce_subscription_payment_meta( $payment_meta, $subscription ) {
			$order                    = wc_get_order( $subscription->get_id() );
			$payment_meta['freepay'] = [
				'post_meta' => [
					'_freepay_transaction_id' => [
						'value' => WC_FreePay_Order_Utils::get_transaction_id($order),
						'label' => __( 'FreePay Transaction ID', 'freepay-for-woocommerce' ),
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
		 * @throws FreePay_API_Exception
		 */
		public function woocommerce_subscription_validate_payment_meta( $payment_meta, $subscription ) {
			if ( isset( $payment_meta['post_meta']['_freepay_transaction_id']['value'] ) ) {
				$transaction_id = $payment_meta['post_meta']['_freepay_transaction_id']['value'];
				$order          = wc_get_order( $subscription->get_id() );

				// Validate only if the transaction ID has changed
				if ( $transaction_id !== WC_FreePay_Order_Utils::get_transaction_id($order) ) {
					$transaction = new WC_FreePay_API_Subscription();
					$transaction->get( $transaction_id );

					// If transaction could be found, add a note on the order for history and debugging reasons.
					$subscription->add_order_note( sprintf( __( 'FreePay Transaction ID updated from #%d to #%d', 'freepay-for-woocommerce' ), WC_FreePay_Order_Utils::get_transaction_id($order), $transaction_id ), 0, true );
				}
			}
		}

		/**
		 * Triggered when customers are changing payment method to FreePay.
		 *
		 * @param $new_payment_method
		 * @param $subscription
		 * @param $old_payment_method
		 */
		public function on_subscription_payment_method_updated_to_freepay( $subscription, $old_payment_method ) {
			$order = wc_get_order( $subscription->get_id() );
			WC_FreePay_Payment_Utils::increase_payment_method_change_count($order);
		}


		/**
		 * subscription_cancellation function.
		 *
		 * Cancels a transaction when the subscription is cancelled
		 *
		 * @access public
		 *
		 * @param WC_Order $order - WC_Order object
		 *
		 * @return void
		 */
		public function subscription_cancellation( $order ) {
			$order = woo_freepay_get_order($order);
			
			if ( 'cancelled' !== $order->get_status() ) {
				return;
			}

			try {
				if ( WC_FreePay_Subscription_Utils::is_wcs_subscription( $order ) && apply_filters( 'woo_freepay_allow_subscription_transaction_cancellation', true, $order, $this ) ) {
					$transaction_id = WC_FreePay_Order_Utils::get_transaction_id($order);

					if(!empty($transaction_id)) {
						$subscription = new WC_FreePay_API_Subscription();
						$subscription->get( $transaction_id );

						$subscription->cancel( $transaction_id, $order );
					}
				}
			} catch ( FreePay_Exception $e ) {
				$e->write_to_logs();
			} catch ( FreePay_API_Exception $e ) {
				$e->write_to_logs();
			}
		}

		/**
		 * callback_handler function.
		 *
		 * Is called after a payment has been submitted in the FreePay payment window.
		 *
		 * @access public
		 * @return void
		 */
		public function callback_handler() {
			sleep(5);

			// Get callback body
			$request_body = file_get_contents( "php://input" );
			parse_str($request_body, $responseData);
			parse_str($_SERVER['QUERY_STRING'], $queries);

			$isAuthorizationPresent = false;
			if(array_key_exists('authorizationIdentifier', $responseData)) {
				$authorizationId = $responseData['authorizationIdentifier'];
			}

			if(array_key_exists('savedCardIdentifier', $responseData)) {
				$subscription_id = $responseData['savedCardIdentifier'];
			}

			if(array_key_exists('paymentIdentifier', $responseData)) {
				$paymentId = $responseData['paymentIdentifier'];
			}

			$order_id = $queries['order_id'];
			$is_renew = $queries['is_renew'] == '1';
			$is_subscription = false;

			if ( ! empty( $authorizationId ) && $authorizationId != '00000000-0000-0000-0000-000000000000' ) {
				$isAuthorizationPresent = true;
			}

			if ( ! empty( $subscription_id ) && $subscription_id != '00000000-0000-0000-0000-000000000000' ) {
				if(!$isAuthorizationPresent) {
					$authorizationId = $subscription_id;
				}
				
				$is_subscription = true;
			}

			if($is_subscription) {
				$payment = new WC_FreePay_API_Subscription();
				$payment->get( $subscription_id );
			}
			else {
				$payment = new WC_FreePay_API_Payment();
				$payment->get( $authorizationId );
			}

			$order_id_by_key = wc_get_order_id_by_order_key($queries['order_key']);
			$order = wc_get_order( $order_id_by_key );

			if(empty($order) && is_int($order_id)) {
				$order = wc_get_order( $order_id );
			}

			if(empty($order)) {
				// Write debug information
				$this->log->separator();
				$this->log->add( "Error: Order not found. Unsuccessful callback response:" . $request_body );
				$this->log->separator();

				http_response_code(500);
				return 'Error: order not found';
			}

			$is_change_card = false;
			if($payment->is_zero_subscription() && $queries['c_card'] == "1") {
				$is_change_card  = true;
				
				$order->add_order_note( __( 'Payment authorization step 2 (Callback)', 'freepay-for-woocommerce' ) );
				WC_FreePay_Order_Utils::set_authorization_step($order, 2);
			}

			if($order && !$is_change_card) {
				$order->read_meta_data(true);
				$step_idx = WC_FreePay_Order_Utils::get_authorization_step($order);

				if(empty($step_idx)) {
					$order->add_order_note( __( 'Payment authorization step 2 (Callback)', 'freepay-for-woocommerce' ) );
					WC_FreePay_Order_Utils::set_authorization_step($order, 2);
				}
			}

			if($order && !empty($authorizationId) && $payment->is_valid( $order_id )) {
				WC_FreePay_Payment_Utils::set_payment_identifier( $order, $paymentId );

				try {
					WC_FreePay_Callbacks::authorized( $order, $responseData );

					// Subscription authorization
					if ( $is_subscription ) {
						// Write log
						WC_FreePay_Callbacks::subscription_authorized( $order->get_id(), $responseData, $isAuthorizationPresent, $is_change_card, $is_renew );

						if($isAuthorizationPresent && !$is_change_card) {
							$wps_sfw_subscription_id = $order->get_meta( 'wps_subscription_id', true );

							if(!empty($wps_sfw_subscription_id)) {
								$parent_order_id = $order->get_id();
							}
							else {
								$wcsOrder = wcs_get_subscription( $order->get_id() );
								$parent_order_id = end($wcsOrder->get_related_orders('ids', 'parent'));
							}

							$parent_order = wc_get_order( $parent_order_id );

							WC_FreePay_Callbacks::payment_authorized( $parent_order, $responseData );
						}

					} // Regular payment authorization
					else {
						WC_FreePay_Callbacks::payment_authorized( $order, $responseData );
					}
				} catch ( FreePay_API_Exception $e ) {
					$e->write_to_logs();
				}
			}
			else {
				// Write debug information
				$this->log->separator();
				$this->log->add( "Unsuccessful callback response:" . $request_body );
				$this->log->separator();
			}
		}

		/**
		 * @param WC_Order $order
		 * @param                   $json
		 */
		public function callback_update_transaction_cache( $order, $json ) {
			try {
				// Instantiating a payment transaction.
				// The type of transaction is currently not important for caching - hence no logic for handling subscriptions is added.
				$transaction = new WC_FreePay_API_Payment( $json );
				$transaction->cache_transaction();
			} catch ( FreePay_Exception $e ) {
				$this->log->add( sprintf( 'Could not cache transaction from callback for order: #%s -> %s', $order->get_id(), $e->getMessage() ) );
			}
		}

		/**
		 * init_form_fields function.
		 *
		 * Initiates the plugin settings form fields
		 *
		 * @access public
		 * @return array
		 */
		public function init_form_fields() {
			$this->form_fields = WC_FreePay_Settings::get_fields();
		}


		/**
		 * admin_options function.
		 *
		 * Prints the admin settings form
		 *
		 * @access public
		 * @return string
		 */
		public function admin_options() {
			echo "<h3>FreePay - {$this->id}, v" . WCFP_VERSION . "</h3>";
			echo "<p>" . __( 'Allows you to receive payments via FreePay.', 'freepay-for-woocommerce' ) . "</p>";

			WC_FreePay_Settings::clear_logs_section();

			do_action( 'woo_freepay_settings_table_before' );

			echo "<table class=\"form-table\">";
			$this->generate_settings_html();
			echo "</table>";

			do_action( 'woo_freepay_settings_table_after' );
		}


		/**
		 * add_meta_boxes function.
		 *
		 * Adds the action meta box inside the single order view.
		 *
		 * @access public
		 * @return void
		 */
		public function add_meta_boxes($unused, $post_or_order) {
			$screen_orders = get_edit_order_screen_id();
			$screen_subs   = get_edit_subscription_screen_id();

			if ( is_current_admin_screen( [ $screen_orders, $screen_subs ] ) ) {
				$order = woo_freepay_get_order( $post_or_order );

				if ( WC_FreePay_Order_Utils::has_freepay_payment($order) ) {
					add_meta_box( 'freepay-payment-actions', __( 'FreePay Payment', 'freepay-for-woocommerce' ), [
						&$this,
						'meta_box_payment',
					], $screen_orders, 'side', 'high' );
					add_meta_box( 'freepay-payment-actions', __( 'FreePay Subscription', 'freepay-for-woocommerce' ), [
						&$this,
						'meta_box_subscription',
					], $screen_subs, 'side', 'high' );
				}
			}
		}


		/**
		 * meta_box_payment function.
		 *
		 * Inserts the content of the API actions meta box - Payments
		 *
		 * @access public
		 * @return void
		 */
		public function meta_box_payment($post_or_order_object) {
			if ( ! $order = woo_freepay_get_order( $post_or_order_object ) ) {
				return;
			}

			$transaction_id = WC_FreePay_Order_Utils::get_transaction_id($order);

			do_action( 'woo_freepay_meta_box_payment_before_content', $order );
			if ( $transaction_id && WC_FreePay_Order_Utils::has_freepay_payment($order) ) {
				$state = null;
				try {
					$transaction = new WC_FreePay_API_Payment();

					try {
						$transaction->get( $transaction_id );
					}
					catch (FreePay_API_Exception $e) {
					}

					if( $transaction->exists() ) {
						$transaction->cache_transaction();

						$state = $transaction->get_state();
						$state_name = $transaction->get_state_name($state);

						echo "<p class=\"woo-freepay-{$state}\"><strong>" . __( 'Current payment state', 'freepay-for-woocommerce' ) . ": " . $state_name . "</strong></p>";

						echo "<h4><strong>" . __( 'Actions', 'freepay-for-woocommerce' ) . "</strong></h4>";
						echo "<ul class=\"order_action\">";
						if($transaction->can_i_capture()) {
							echo "<li class=\"fp-full-width\"><a class=\"button button-primary\" data-action=\"capture\" data-confirm=\"" . __( 'You are about to CAPTURE this payment', 'freepay-for-woocommerce' ) . "\">" . sprintf( __( 'Capture Full Amount (%s)', 'freepay-for-woocommerce' ), $transaction->get_formatted_remaining_balance() ) . "</a></li>";
							printf( "<li class=\".fp-balance\"><span class=\".fp-balance__label\">%s:</span><span class=\".fp-balance__amount\"><span class='.fp-balance__currency'>%s</span> %s</span></li>", __( 'Remaining balance', 'freepay-for-woocommerce' ), $transaction->get_currency(), $transaction->get_formatted_remaining_balance() );
							printf( "<li class=\".fp-balance last\"><span class=\".fp-balance__label\">%s:</span><span class=\".fp-balance__amount\"><input id='fp-balance__amount-field' type='text' value='%s' /></span></li>", __( 'Capture amount', 'freepay-for-woocommerce' ), $transaction->get_formatted_remaining_balance() );

							$partialCaptureMessage = __( 'You are about to PARTIALLY capture this payment.', 'freepay-for-woocommerce' );
							if(WC_FreePay_Helper::option_is_enabled( $this->s( 'freepay_captureoncomplete' ) )) {
								$partialCaptureMessage = __( 'You are about to PARTIALLY capture this payment. NOTE: Automatic capturing on order completion is disabled for this order, to prevent unintended overcharging of customers.', 'freepay-for-woocommerce' );
							}

							echo "<li class=\"fp-full-width\"><a class=\"button\" data-action=\"captureAmount\" data-confirm=\"" . $partialCaptureMessage . "\">" . __( 'Capture Specified Amount', 'freepay-for-woocommerce' ) . "</a></li>";
						}

						if($transaction->can_i_cancel()) {
							echo "<li class=\"fp-full-width\"><a class=\"button\" data-action=\"cancel\" data-confirm=\"" . __( 'You are about to CANCEL this payment', 'freepay-for-woocommerce' ) . "\">" . __( 'Cancel', 'freepay-for-woocommerce' ) . "</a></li>";
						}

						echo "</ul>";

						printf( '<p><small><strong>%s:</strong> %s <span class="fp-meta-card"><img src="%s" /></span></small>', __( 'Transaction ID', 'freepay-for-woocommerce' ), $transaction_id, WC_Freepay_Helper::get_payment_type_logo( $transaction->get_brand() ) );
					}
				} catch ( FreePay_API_Exception $e ) {
					$e->write_to_logs();
					if ( $state !== 'initial' ) {
						$e->write_standard_warning();
					}
				} catch ( FreePay_Exception $e ) {
					$e->write_to_logs();
					if ( $state !== 'initial' ) {
						$e->write_standard_warning();
					}
				}
			}

			// Show payment ID and payment link for orders that have not yet
			// been paid. Show this information even if the transaction ID is missing.
			$payment_id = WC_FreePay_Payment_Utils::get_payment_identifier($order);
			if ( isset( $payment_id ) && ! empty( $payment_id ) ) {
				printf( '<p><small><strong>%s:</strong> %s</small>', __( 'Payment ID', 'freepay-for-woocommerce' ), $payment_id );
			}

			$payment_link = WC_FreePay_Payment_Utils::get_payment_link($order);
			if ( isset( $payment_link ) && ! empty( $payment_link ) ) {
				printf( '<p><small><strong>%s:</strong> <br /><input type="text" style="%s"value="%s" readonly /></small></p>', __( 'Payment Link', 'freepay-for-woocommerce' ), 'width:100%', $payment_link );
			}

			do_action( 'woo_freepay_meta_box_payment_after_content', $order );
		}


		/**
		 * meta_box_payment function.
		 *
		 * Inserts the content of the API actions meta box - Subscriptions
		 *
		 * @access public
		 * @return void
		 */
		public function meta_box_subscription($post_or_subscription_object) {
			if ( ! $order = woo_freepay_get_order( $post_or_subscription_object ) ) {
				return;
			}

			$transaction_id = WC_FreePay_Order_Utils::get_transaction_id($order);
			$state          = null;

			do_action( 'woo_freepay_meta_box_subscription_before_content', $order );

			if ( $transaction_id && WC_FreePay_Order_Utils::has_freepay_payment($order) ) {
				try {

					$transaction = new WC_FreePay_API_Subscription();
					$transaction->get( $transaction_id );
					$status = null;
					$state  = $transaction->get_state();
					try {
						$status = $transaction->get_current_type() . ' (' . __( 'subscription', 'freepay-for-woocommerce' ) . ')';
					} catch ( FreePay_API_Exception $e ) {
						throw new FreePay_API_Exception( $e->getMessage() );
						$status = $state;
					}

					echo "<p class=\"woo-freepay-{$status}\"><strong>" . __( 'Current payment state', 'freepay-for-woocommerce' ) . ": " . $status . "</strong></p>";

					printf( '<p><small><strong>%s:</strong> %s <span class="fp-meta-card"><img src="%s" /></span></small>', __( 'Transaction ID', 'freepay-for-woocommerce' ), $transaction_id, WC_Freepay_Helper::get_payment_type_logo( $transaction->get_brand() ) );
				} catch ( FreePay_API_Exception $e ) {
					$e->write_to_logs();
					$e->write_standard_warning();
				}
			}

			do_action( 'woo_freepay_meta_box_subscription_after_content', $order );
		}

		/**
		 * Adds a separate column for payment info
		 *
		 * @param array $show_columns
		 *
		 * @return array
		 */
		public function filter_shop_order_posts_columns( $show_columns ) {
			$column_name   = 'freepay_transaction_info';
			$column_header = __( 'Payment', 'freepay-for-woocommerce' );

			return WC_FreePay_Helper::array_insert_after( 'shipping_address', $show_columns, $column_name, $column_header );
		}

		/**
		 * apply_custom_order_data function.
		 *
		 * Applies transaction ID and state to the order data overview
		 *
		 * @access public
		 * @return void
		 */
		public function apply_custom_order_data( $column, $post_id_or_order_object ) {
			$order = woo_freepay_get_order( $post_id_or_order_object );
			$order_type = \Automattic\WooCommerce\Utilities\OrderUtil::get_order_type( $order );

			// Show transaction ID on the overview
			if ( ( $order_type == 'shop_order' && $column == 'freepay_transaction_info' ) || ( $order_type == 'shop_subscription' && $column == 'order_title' ) ) {
				// Insert transaction id and payment status if any
				$transaction_id = WC_FreePay_Order_Utils::get_transaction_id($order);

				try {
					if ( $transaction_id && WC_FreePay_Order_Utils::has_freepay_payment($order) ) {

						if ( WC_FreePay_Subscription_Utils::is_wcs_subscription( $order ) ) {
							$transaction = new WC_FreePay_API_Subscription();
						} else {
							$transaction = new WC_FreePay_API_Payment();
						}

						// Get transaction data
						$transaction->maybe_load_transaction_from_cache( $transaction_id );

						if ( WC_FreePay_Subscription_Utils::subscription_is_renewal_failure($order) ) {
							$status = __( 'Failed renewal', 'freepay-for-woocommerce' );
						} else {
							$status = $transaction->get_state();
						}

						$brand = $transaction->get_brand();

						WC_FreePay_Views::get_view( 'html-order-table-transaction-data.php', [
							'transaction_id'             => $transaction_id,
							'transaction_brand'          => $brand,
							'transaction_brand_logo_url' => WC_FreePay_Helper::get_payment_type_logo( $brand ),
							'transaction_status'         => $status,
							'transaction_is_test'        => $transaction->is_test(),
							'is_cached'                  => $transaction->is_loaded_from_cached(),
						] );
					}
				} catch ( FreePay_API_Exception $e ) {
					$this->log->add( sprintf( 'Order list: #%s - %s', $order->get_id(), $e->getMessage() ) );
				} catch ( FreePay_Exception $e ) {
					$this->log->add( sprintf( 'Order list: #%s - %s', $order->get_id(), $e->getMessage() ) );
				}

			}
		}

		/**
		 * FILTER: apply_gateway_icons function.
		 *
		 * Sets gateway icons on frontend
		 *
		 * @access public
		 * @return void
		 */
		public function apply_gateway_icons( $icon, $id ) {
			if ( $id == $this->id ) {
				$icon = '';

				$icons = $this->s( 'freepay_icons' );

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
			if ( file_exists( __DIR__ . '/assets/images/cards/' . $icon . '.svg' ) ) {
				$icon_url = $icon_url = WC_HTTPS::force_https_url( plugin_dir_url( __FILE__ ) . 'assets/images/cards/' . $icon . '.svg' );
			} else {
				$icon_url = WC_HTTPS::force_https_url( plugin_dir_url( __FILE__ ) . 'assets/images/cards/' . $icon . '.png' );
			}

			$icon_url = apply_filters( 'woo_freepay_checkout_gateway_icon_url', $icon_url, $icon );

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
			$settings_icons_maxheight = $this->s( 'freepay_icons_maxheight' );

			return ! empty( $settings_icons_maxheight ) ? $settings_icons_maxheight . 'px' : '20px';
		}


		/**
		 *
		 * get_gateway_currency
		 *
		 * Returns the gateway currency
		 *
		 * @access public
		 *
		 * @param WC_Order $order
		 *
		 * @return void
		 */
		public function get_gateway_currency( WC_Order $order ) {
			if( has_filter('wpml_object_id') && $this->s( 'freepay_currency' ) == 'WPML' ) {
				return $order->get_currency();
			}
			else if ($this->s( 'freepay_currency' ) == 'AUTO') {
				return $order->get_currency();
			}
			else {
				$currency = apply_filters( 'woo_freepay_currency', $this->s( 'freepay_currency' ), $order );
			}

			return $currency;
		}


		/**
		 *
		 * get_gateway_language
		 *
		 * Returns the gateway language
		 *
		 * @access public
		 * @return string
		 */
		public function get_gateway_language() {
			if($this->s( 'freepay_language' ) == 'auto') {
				return "";
			}

			$language = apply_filters( 'woo_freepay_language', $this->s( 'freepay_language' ) );

			return $language;
		}

		/**
		 * path
		 *
		 * Returns a plugin URL path
		 *
		 * @param $path
		 *
		 * @return mixed
		 */
		public function plugin_url( $path ) {
			return plugins_url( $path, __FILE__ );
		}
	}

	/**
	 * Make the object available for later use
	 *
	 * @return WC_FreePay
	 */
	function WC_FP_MAIN() {
		return WC_FreePay::get_instance();
	}

	// Instantiate
	WC_FP_MAIN();
	WC_FP_MAIN()->hooks_and_filters();

	add_action( 'woocommerce_blocks_loaded',
		function () {
			if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
				require_once 'classes/woo-freepay-blocks-support.php';
				add_action(
					'woocommerce_blocks_payment_method_type_registration',
					function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
						$payment_method_registry->register( new WC_Gateway_FreePay_Blocks_Support() );
					}
				);
			}
		},
		5
	);

	// Add the gateway to WooCommerce
	function add_freepay_gateway( $methods ) {
		$methods[] = 'WC_FreePay';

		WC_FreePay_Statekeeper::$gateways_added = true;

		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_freepay_gateway' );
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'WC_FreePay::add_action_links' );
}

/**
 * Run installer
 *
 * @param string __FILE__ - The current file
 * @param function - Do the installer/update logic.
 */
register_activation_hook( __FILE__, function () {
	require_once WCFP_PATH . 'classes/woo-freepay-install.php';

	// Run the installer on the first install.
	if ( WC_FreePay_Install::is_first_install() ) {
		WC_FreePay_Install::install();
	}
} );

add_action( 'before_woocommerce_init', function () {
	if ( !WC_FreePay_Subscription_Utils::sfw_plugin_is_active() && class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );