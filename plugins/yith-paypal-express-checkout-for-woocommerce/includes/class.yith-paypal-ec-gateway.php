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
} // Exit if accessed directly.



if ( ! class_exists( 'YITH_Gateway_Paypal_Express_Checkout' ) ) {
	/**
	 * Class YITH_Gateway_Paypal_Express_Checkout
	 */
	class YITH_Gateway_Paypal_Express_Checkout extends WC_Payment_Gateway_CC {

		/**
		 * Single instance of YITH_PayPal_EC_API_Handler
		 *
		 * @var \YITH_PayPal_EC_API_Handler
		 */
		protected $api;
		/**
		 * Environment
		 *
		 * @var string
		 */
		protected $env;

		/**
		 * Test mode
		 *
		 * @var bool
		 */
		protected $testmode = 1;

		/**
		 * Helper class
		 *
		 * @var YITH_PayPal_EC_Helper
		 */
		protected $helper;

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->id = YITH_PayPal_EC::$gateway_id;

			$this->has_fields         = false;
			$this->order_button_text  = $this->get_order_button_text();
			$this->method_title       = apply_filters( 'yith_paypal_ec_method_title', esc_html__( 'YITH Paypal Express Checkout', 'yith-paypal-express-checkout-for-woocommerce' ) );
			$this->method_description = apply_filters( 'yith_paypal_ec_method_description', esc_html__( 'Take payments via Paypal Express Checkout.', 'yith-paypal-express-checkout-for-woocommerce' ) );
			$this->supports           = array(
				'products',
				'refunds',
				'yith_subscriptions',
				'yith_subscriptions_scheduling',
				'yith_subscriptions_pause',
				'yith_subscriptions_multiple',
				'yith_subscriptions_payment_date',
				'yith_subscriptions_recurring_amount',
			);

			if ( 'yes' === $this->get_option( 'reference_transaction', 'yes' ) ) {
				$this->supports = array(
					'products',
					'refunds',
					'yith_subscriptions',
					'yith_subscriptions_scheduling',
					'yith_subscriptions_pause',
					'yith_subscriptions_multiple',
					'yith_subscriptions_payment_date',
					'yith_subscriptions_recurring_amount',
				);
			} else {
				$this->supports = array(
					'products',
					'refunds',
				);
			}

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Title and Description.
			$this->title       = $this->get_option( 'title' );
			$this->description = $this->get_option( 'gateway_description' );

			// Enabled and Environment Option.
			$this->enabled  = $this->get_option( 'enabled' ) !== '' ? $this->get_option( 'enabled' ) : 'no';
			$this->env      = $this->get_option( 'env' ) !== '' ? $this->get_option( 'env' ) : 'sandbox';
			$this->testmode = 'sandbox' === $this->env;

			if ( $this->testmode ) {
				// translators: link to sandbox.
				$this->description .= ' ' . sprintf( __( 'SANDBOX ENABLED. You can only use sandbox testing accounts. See the <a href="%s">PayPal Sandbox Testing Guide</a> for more details.', 'yith-paypal-express-checkout-for-woocommerce' ), 'https://developer.paypal.com/docs/classic/lifecycle/ug_sandbox/' );
				$this->description  = trim( $this->description );
			}

			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

			$this->helper = yith_paypal_ec()->ec;
			if ( ! $this->helper->is_valid_for_use() ) {
				$this->enabled = 'no';
			}

			// API Credentials.
			$this->api_username  = $this->get_option( $this->env . '_api_username' );
			$this->api_password  = $this->get_option( $this->env . '_api_password' );
			$this->api_signature = $this->get_option( $this->env . '_api_signature' );
			$this->api_subject   = $this->get_option( $this->env . '_api_subject' );
			$this->api_endpoint  = ( 'sandbox' === $this->env ) ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';

			// wc-api handler for express checkout transactions.
			if ( ! has_action( 'woocommerce_api_yith_paypal_ec' ) ) {
				add_action( 'woocommerce_api_yith_paypal_ec', array( $this, 'wc_api' ) );
			}

			include_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-api-handler.php';
			include_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-request.php';
			include_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-response.php';
			include_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-response-payment.php';
			include_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-exception.php';
			$this->api = new YITH_PayPal_EC_API_Handler();

			// Actions used for Authorize payments.
			add_action( 'woocommerce_order_status_on-hold_to_processing', array( $this, 'request_payment' ) );
			add_action( 'woocommerce_order_status_on-hold_to_completed', array( $this, 'request_payment' ) );
			add_action( 'woocommerce_order_status_on-hold_to_cancelled', array( $this, 'delete_payment' ) );
			add_action( 'woocommerce_order_status_on-hold_to_refunded', array( $this, 'delete_payment' ) );

			add_action( 'woocommerce_checkout_billing', array( $this, 'set_checkout_user_details' ), 9 );

			// Load the integration with YITH WooCommerce Subscription Premium.
			if ( class_exists( 'YITH_WC_Subscription' ) ) {
				if ( version_compare( YITH_YWSBS_VERSION, '1.4.6', '<' ) ) {
					add_action( 'yith_paypal_ec_request_a_payment', array( $this, 'renew_subscriptions' ) );
				} else {
					add_action( 'ywsbs_pay_renew_order_with_' . $this->id, array( $this, 'renew_subscriptions' ), 10, 2 );
				}
			}

			add_action( 'woocommerce_admin_order_totals_after_total', array( $this, 'display_order_fee' ) );
			self::ipn_handler();

		}

		/**
		 * Get the transaction URL.
		 *
		 * @param WC_Order $order Order object.
		 * @return string
		 */
		public function get_transaction_url( $order ) {
			if ( $this->testmode ) {
				$this->view_transaction_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id=%s';
			} else {
				$this->view_transaction_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id=%s';
			}
			return parent::get_transaction_url( $order );
		}

		/**
		 * Initialize form fields for the admin
		 *
		 * @since 1.0
		 */
		public function init_form_fields() {
			$this->form_fields = include 'admin/gateway-settings.php';
		}

		/**
		 * Init settings for gateways.
		 *
		 * @since 1.0
		 */
		public function init_settings() {
			parent::init_settings();
			$this->enabled = ! empty( $this->settings['enabled'] ) && 'yes' === $this->settings['enabled'] ? 'yes' : 'no';
		}


		/**
		 * Process payment
		 *
		 * @param int $order_id Order id.
		 *
		 * @return array
		 */
		public function process_payment( $order_id ) {

			try {
				if ( isset( WC()->session->yith_paypal_session ) && WC()->session->yith_paypal_session['token'] ) {
					$result = array(
						'result'   => 'success',
						'redirect' => $this->api->get_request_url_from_cart( $order_id, $this->testmode ),
					);
				} else {
					$result = array(
						'result'   => 'success',
						'redirect' => $this->api->get_request_url( $order_id, $this->testmode ),
					);
				}

				return $result;
			} catch ( YITH_PayPal_EC_Exception $e ) {
				wc_add_notice( $e->getMessage(), 'error' );
			}
		}

		/**
		 * Return the icon
		 *
		 * @return string
		 */
		public function get_icon() {
			return $this->helper->get_icon();
		}


		/**
		 * Called WC API
		 *
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function wc_api() {

			$request = $_REQUEST; //phpcs:ignore
			// calls set express checkout from cart and returns the token to javascript.
			if ( isset( $request['yith_paypal_set_express_checkout'] ) && ! isset( $request['woocommerce-order-pay'] ) ) {
				try {
					$set_express_checkout_response = $this->api->set_express_checkout_args_from_cart();

					if ( ! $set_express_checkout_response->has_error() ) {
						wp_send_json(
							array(
								'result' => 'success',
								'token'  => $set_express_checkout_response->get_token(),
							)
						);
						exit;
					}
				} catch ( YITH_PayPal_EC_Exception $e ) {
					// translators: Placeholder is the error message.
					$this->helper->log_add_message( sprintf( esc_html__( 'An error occurred %s', 'yith-paypal-express-checkout-for-woocommerce' ), $e->getMessage() ) );
					$this->helper->clear_session();
					wp_send_json(
						array(
							'result' => 'failed',
							'error'  => $e->getMessage(),
						)
					);
					exit();
				}
			} elseif ( isset( $request['yith_paypal_set_express_checkout'] ) && isset( $request['woocommerce-order-pay'] ) ) {
				$order_id = wc_get_order_id_by_order_key( sanitize_text_field( wp_unslash( $request['woocommerce-order-pay'] ) ) );
				$order    = wc_get_order( $order_id );
				if ( $order instanceof WC_Order ) {
					try {
						$set_express_checkout_response = $this->api->set_express_checkout_args_from_order( $order );

						if ( ! $set_express_checkout_response->has_error() ) {
							wp_send_json(
								array(
									'result' => 'success',
									'token'  => $set_express_checkout_response->get_token(),
								)
							);
							exit;
						}
					} catch ( YITH_PayPal_EC_Exception $e ) {
						// translators: Placeholder is the error message.
						$this->helper->log_add_message( sprintf( esc_html__( 'An error occurred %s', 'yith-paypal-express-checkout-for-woocommerce' ), $e->getMessage() ) );
						$this->helper->clear_session();
						wp_send_json(
							array(
								'result' => 'failed',
								'error'  => $e->getMessage(),
							)
						);
						exit();
					}
				}
			}

			// calls get_express_checkout_details from cart and then does the redirect to checkout page.
			if ( isset( $request['yith_paypal_do_express_checkout'] ) && ! isset( $request['order_key'] ) ) {

				unset( WC()->session->yith_paypal_session );
				$token = sanitize_text_field( wp_unslash( $request['token'] ) );
				try {
					$checkout_details = $this->api->call_get_express_checkout_details( $token );

					if ( ! $checkout_details->has_error() ) {

						$paypal_session = array(
							'token'               => $token,
							'shipping_info'       => $checkout_details->get_shipping_details(),
							'from'                => 'cart',
							'transaction_details' => $checkout_details->get_response_parameters(),
						);
						WC()->session->set( 'yith_paypal_session', $paypal_session );
						wp_safe_redirect( wc_get_checkout_url() );
						exit;
					}
				} catch ( YITH_PayPal_EC_Exception $e ) {
					// translators: Placeholder is the error message.
					$this->helper->log_add_message( sprintf( esc_html__( 'An error occurred %s', 'yith-paypal-express-checkout-for-woocommerce' ), $e->getMessage() ) );
					$this->helper->clear_session();
					wc_add_notice( $e->getMessage(), 'error' );
					wp_safe_redirect( wc_get_checkout_url() );
					exit;
				}
			}

			// manage the transition from checkout page.
			if ( ! isset( $request['yith_paypal_ec_back'] ) || ! isset( $request['token'] ) ) {
				return;
			}

			$token = sanitize_text_field( wp_unslash( $request['token'] ) );

			if ( isset( $request['order_key'] ) ) {
				$request['order_id'] = wc_get_order_id_by_order_key( sanitize_text_field( wp_unslash( $request['order_key'] ) ) );
			}

			$order_id = isset( $request['order_id'] ) ? sanitize_text_field( wp_unslash( $request['order_id'] ) ) : 0;

			try {
				/**
				 * YITH_PayPal_EC_Response
				 *
				 * @var YITH_PayPal_EC_Respons $checkout_details
				 */
				$checkout_details = $this->api->call_get_express_checkout_details( $token );

				if ( $checkout_details->has_error() ) {
					throw new YITH_PayPal_EC_Exception( $checkout_details );
				}

				$order             = ! $order_id ? $checkout_details->get_order() : wc_get_order( $order_id );
				$with_subscription = false !== $this->api->needs_billing_agreement( $order );
				if ( $with_subscription && 1 != $checkout_details->get_billing_agreement_accepted_status() ) { //phpcs:ignore
					// translators: Placeholder is number of order.
					$this->helper->log_add_message( sprintf( esc_html__( 'Billing Agreement was not accepted for the order %d', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id() ) );
					$this->helper->clear_session();
					throw new YITH_PayPal_EC_Exception( esc_html__( 'Billing Agreement was not accepted.', 'yith-paypal-express-checkout-for-woocommerce' ) );
				}

				$payer_id = $checkout_details->get_payer_id();
				$custom   = wp_json_encode(
					array(
						'order_id'  => $order->get_id(),
						'order_key' => $order->get_order_key(),
						'url'       => get_home_url(),
					)
				);
				$this->save_payer_info( $order, $checkout_details, $with_subscription );

				if ( ! is_null( $order ) ) {
					$args = array(
						'payer_id'       => $payer_id,
						'payment_action' => $this->helper->payment_action,
						'custom'         => $custom,
					);

					// we need to process an initial payment.
					if ( $order->get_total() > 0 || ! $with_subscription ) {
						/**
						 * YITH_PayPal_EC_Response_Payment
						 *
						 * @var YITH_PayPal_EC_Response_Payment $payment_response
						 */
						$payment_response = $this->api->call_do_express_checkout_payment( $token, $order, $args );
					} else {
						/**
						 * YITH_PayPal_EC_Response_Payment
						 *
						 * @var YITH_PayPal_EC_Response_Payment $payment_response
						 */
						$payment_response = $this->api->call_create_billing_agreement( $token );
					}

					if ( $payment_response->has_error() ) {
						$error = $payment_response->get_errors( true );

						if ( 10486 === (int) $error['code'] ) {
							$redirect_url  = ( 'sandbox' === $this->env ) ? 'https://www.sandbox.' : 'https://www.';
							$redirect_url .= 'paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token;

							wp_safe_redirect( $redirect_url );
							die();
						}

						// change the status to order.
						// translators: Placeholder 1. error code 2. error message.
						$order->update_status( 'failed', sprintf( esc_html__( 'YITH PayPal EC error: (%1$d) %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $error['code'], $error['message'] ) );
						// translators: Placeholder 1. order id 2. error code 3. error message.
						$this->helper->log_add_message( sprintf( esc_html__( 'Failed payment for order %1$d - %2$s - %3$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id(), $error['code'], $error['message'] ) );
						throw new YITH_PayPal_EC_Exception( $payment_response );
					}

					$with_subscription && $this->save_billing_agreement_id( $order, $payment_response->get_billing_agreement_id() );

					if ( 0 == $order->get_total() ) {  //phpcs:ignore
						$order->payment_complete();
						$redirect_url = add_query_arg( 'utm_nooverride', '1', $order->get_checkout_order_received_url() );
						wp_safe_redirect( $redirect_url );
					} else {
						if ( $with_subscription ) {
							do_action( 'yith_paypal_ec_process_order_payment_with_billing_agreement', $order, $payment_response, false );
						} else {
							$this->process_order_payment( $order, $payment_response );
						}
					}
				}
			} catch ( YITH_PayPal_EC_Exception $e ) {
				// translators: Placeholder is the error message.
				$this->helper->log_add_message( sprintf( esc_html__( 'An error occurred %s', 'yith-paypal-express-checkout-for-woocommerce' ), $e->getMessage() ) );
				$this->helper->clear_session();
				wc_add_notice( $e->getMessage(), 'error' );
				wp_safe_redirect( wc_get_checkout_url() );
			}
		}

		/**
		 * Process the order payment
		 *
		 * @param WC_Order                                              $order Order.
		 * @param YITH_PayPal_EC_Response_Payment_Reference_Transaction $payment_response Payment response.
		 *
		 * @since 1.0.0
		 */
		public function process_order_payment( $order, $payment_response ) {

			$redirect_url   = '';
			$payment_status = $payment_response->get_response_payment_parameter( 'PAYMENTSTATUS' );
			$transaction_id = $payment_response->get_response_payment_parameter( 'TRANSACTIONID' );
			$payment_type   = $payment_response->get_response_payment_parameter( 'PAYMENTTYPE' );
			$payment_fee    = $payment_response->get_response_payment_parameter( 'FEEAMT' );

			if ( ! empty( $payment_fee ) ) {
				$order->update_meta_data( '_yith_ppec_fee', $payment_fee );
			}

			$order->update_meta_data( 'Payment type', $payment_type );
			$order->update_meta_data( '_transaction_id', $transaction_id );

			if ( 'pending' === strtolower( $payment_status ) ) {
				$message = $payment_response->get_response_payment_parameter( 'PENDINGREASON' );

				if ( 'authorization' === $message ) {
					// translators: placeholder order id.
					$this->helper->log_add_message( sprintf( esc_html__( 'Payment authorized for order %d', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id() ) );
					$order_note = esc_html__( 'YITH PayPal EC - Payment authorized. Change the order status to processing or complete to capture funds.', 'yith-paypal-express-checkout-for-woocommerce' );
				} else {
					// translators: placeholder order id and message.
					$this->helper->log_add_message( sprintf( esc_html__( 'Pending payment for order %1$d - %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id(), $message ) );
					// translators: placeholder message that arrives from PayPal.
					$order_note = sprintf( esc_html__( 'YITH PayPal EC: %s', 'yith-paypal-express-checkout-for-woocommerce' ), $message );
				}

				if ( 'echeck' === $payment_type ) {
					// translators: Placeholder date and order id.
					$this->helper->log_add_message( sprintf( esc_html__( 'Echeck expected on %1$s for order %2$d', 'yith-paypal-express-checkout-for-woocommerce' ), date_i18n( wc_date_format(), ywsbs_date_to_time( $payment_response->get_response_payment_parameter( 'EXPECTEDECHECKCLEARDATE' ) ) ), $order->get_id() ) );
					// translators: Placeholder date.
					$status_note = sprintf( esc_html__( 'Echeck expected on %s', 'yith-paypal-express-checkout-for-woocommerce' ), date_i18n( wc_date_format(), ywsbs_date_to_time( $payment_response->get_response_payment_parameter( 'EXPECTEDECHECKCLEARDATE' ) ) ) );
				}

				$order_status = apply_filters( 'ywsbs_paypal_ec_pending_payment_order_status', 'on-hold', $order, $payment_response );

				// mark order as held.
				if ( ! $order->has_status( $order_status ) ) {
					$order->update_status( $order_status, $order_note );
				} else {
					$order->add_order_note( $order_note );
				}

				$redirect_url = add_query_arg( 'utm_nooverride', '1', $order->get_checkout_order_received_url() );

			} elseif ( ! $payment_response->transaction_approved() ) {
				$status_note = '';
				$new_status  = '';

				$error = $payment_response->get_errors( true );
				if ( is_array( $error ) ) {
					$status_note = $error['message'];

					switch ( $error['code'] ) {
						case '10201':
							$new_status = 'cancelled';
							break;
						default:
							$new_status = 'on-hold';
					}
				}
				// translators: Placeholder 1. order id 2. status.
				$this->helper->log_add_message( sprintf( esc_html__( 'Payment failed for order %1$d: %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id(), $status_note ) );
				// translators: Placeholder status of payment.
				! empty( $new_status ) && $order->update_status( $new_status, sprintf( __( 'YITH PayPal EC payment failed: %s', 'yith-paypal-express-checkout-for-woocommerce' ), $status_note ) );

			} elseif ( $payment_response->transaction_approved() ) {
				// translators: Placeholder 1. transaction id.
				$order->add_order_note( sprintf( esc_html__( 'YITH PayPal EC payment (ID: %s)', 'yith-paypal-express-checkout-for-woocommerce' ), $transaction_id ) );
				// translators: Placeholder 1. order id 2.transaction id.
				$this->helper->log_add_message( sprintf( esc_html__( 'Payment done for order %1$d (ID: %2$s)', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id(), $transaction_id ) );

				do_action( 'ywsbs_process_order_payment_before_complete', $order, $payment_response );

				$order->payment_complete( $transaction_id );

				$redirect_url = add_query_arg( 'utm_nooverride', '1', $order->get_checkout_order_received_url() );

			}

			$order->save();

			if ( ! empty( $redirect_url ) ) {
				$this->helper->clear_session();
				wp_safe_redirect( esc_url_raw( $redirect_url ) );
				die;
			}

		}

		/**
		 * Charge the customer
		 *
		 * @param int $order_id Order id.
		 *
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function request_payment( $order_id ) {
			$order          = wc_get_order( $order_id );
			$payment_method = $order->get_payment_method();
			if ( yith_paypal_ec::$gateway_id === $payment_method ) {
				$transaction_id = $order->get_transaction_id();

				if ( ! empty( $transaction_id ) ) {
					$response_details = $this->api->call_get_transaction_details( $transaction_id );

					if ( ! $response_details->has_error() && 'Pending' === $response_details->get_payment_status() && 'authorization' === $response_details->get_pending_reason() ) {
						$args = array(
							'transaction_id' => $transaction_id,
							'amount'         => $order->get_total(),
							'currency'       => $order->get_currency(),
						);

						$response_capture = $this->api->call_do_capture( $args );

						if ( $response_capture->has_error() ) {
							// translators: 1 order id 2. error code 3. error message.
							$this->helper->log_add_message( sprintf( esc_html__( 'Unable to complete payment for order %1$d, %2$s - %3$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id, $response_capture->get_error_code(), $response_capture->get_error_message() ) );
							$order->add_order_note( esc_html__( 'Unable to complete payment', 'yith-paypal-express-checkout-for-woocommerce' ) . ' ' . $response_capture->get_error_message() );
						}

						if ( 'Completed' === $response_capture->get_payment_status() ) {
							$new_transaction = $response_capture->get_transaction_id();
							$payment_fee     = $response_capture->get_fee_amount();

							// translators: 1 order id 2.transaction id.
							$this->helper->log_add_message( sprintf( esc_html__( 'YITH PayPal EC - payment completed for order %1$d - Transaction id: %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id, $new_transaction ) );
							// translators: 1. transaction id.
							$order->add_order_note( sprintf( esc_html__( 'YITH PayPal EC - payment completed. Transaction ID: %s', 'yith-paypal-express-checkout-for-woocommerce' ), $new_transaction ) );
							try {
								$order->set_transaction_id( $new_transaction );
							} catch ( WC_Data_Exception $exception ) {
								// translators: 1 order id 2.transaction id.
								$this->helper->log_add_message( sprintf( esc_html__( 'YITH PayPal EC payment - new transaction_id not saved for order %1$d - Transaction id: %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id, $new_transaction ) );
								$order->add_order_note( sprintf( esc_html__( 'YITH PayPal EC payment - new transaction_id not saved.', 'yith-paypal-express-checkout-for-woocommerce' ), $exception->getMessage() ) );
							}
							if ( ! empty( $payment_fee ) ) {
								$order->update_meta_data( '_yith_ppec_fee', $payment_fee );
							}

							$order->payment_complete( $new_transaction );
							$order->save();
						}
					}
				}
			}
		}

		/**
		 * Delete payment
		 *
		 * @param int $order_id Order id.
		 *
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function delete_payment( $order_id ) {
			$order          = wc_get_order( $order_id );
			$payment_method = $order->get_payment_method();
			if ( yith_paypal_ec::$gateway_id === $payment_method ) {
				$transaction_id = $order->get_transaction_id();

				if ( ! empty( $transaction_id ) ) {
					$response_details = $this->api->call_get_transaction_details( $transaction_id );

					if ( ! $response_details->has_error() && 'Pending' === $response_details->get_payment_status() && 'authorization' === $response_details->get_pending_reason() ) {

						$response_capture = $this->api->call_do_void( $transaction_id );

						if ( $response_capture->has_error() ) {
							// translators: 1. order id 2. error code 3. error message.
							$this->helper->log_add_message( sprintf( esc_html__( 'Unable to delete payment for order %1$d, %2$s - %3$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id, $response_capture->get_error_code(), $response_capture->get_error_message() ) );
							$order->add_order_note( esc_html__( 'Unable to delete payment', 'yith-paypal-express-checkout-for-woocommerce' ) . ' ' . $response_capture->get_error_message() );
						} else {
							// translators: 1. order id .
							$this->helper->log_add_message( sprintf( esc_html__( 'YITH PayPal EC - payment deleted for order %d', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );
							// translators: 1. transaction id .
							$order->add_order_note( sprintf( esc_html__( 'YITH PayPal EC - payment deleted (ID: %s)', 'yith-paypal-express-checkout-for-woocommerce' ), $transaction_id ) );
						}
					}
				}
			}
		}

		/**
		 * Process the refund.
		 *
		 * @param int    $order_id Order id.
		 * @param null   $amount Amount to refund.
		 * @param string $reason Reason of refund.
		 *
		 * @return bool|WP_Error
		 * @throws YITH_PayPal_EC_Exception Throws an Exception.
		 */
		public function process_refund( $order_id, $amount = null, $reason = '' ) {

			$order = wc_get_order( $order_id );

			if ( ! ( $order && $order->get_transaction_id() ) ) {
				// translators: Placeholder is the order id.
				$this->helper->log_add_message( sprintf( esc_html__( 'Refund failed for order %d', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );

				return new WP_Error( 'error', esc_html__( 'Refund failed.', 'yith-paypal-express-checkout-for-woocommerce' ) );
			}

			$refund_result = $this->api->call_refund_transaction( $order, $amount, $reason );

			if ( ! $refund_result->has_error() ) {
				$result = true;
				$amount = wc_price( $refund_result->get_response_parameter( 'GROSSREFUNDAMT' ), array( 'currency' => $refund_result->get_response_parameter( 'CURRENCYCODE' ) ) );
				// translators: Placeholder 1. amount of refund 2. transaction id 3. order id.
				$this->helper->log_add_message( sprintf( esc_html__( 'Refunded %1$s - Refund ID: %2$s for order %3$d', 'yith-paypal-express-checkout-for-woocommerce' ), $amount, $refund_result->get_response_parameter( 'REFUNDTRANSACTIONID' ), $order_id ) );
				// translators: Placeholder 1. amount of refund 2. transaction id.
				$order->add_order_note( sprintf( esc_html__( 'Refunded %1$s - Refund ID: %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $amount, $refund_result->get_response_parameter( 'REFUNDTRANSACTIONID' ) ) );
			} else {
				$result = false;
				// translators: Placeholder 1. order id 2. error message  3. error code.
				$this->helper->log_add_message( sprintf( esc_html__( 'An error occurred while refunding order %1$d: %2$s - Code: %3$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id, $refund_result->get_error_message(), $refund_result->get_error_code() ) );
				// translators: Placeholder 1.error message.
				$order->add_order_note( sprintf( esc_html__( 'An error occurred during the refund: %s', 'yith-paypal-express-checkout-for-woocommerce' ), $refund_result->get_error_message() ) );
			}

			return $result;
		}

		/**
		 * Saves the billing agreement id inside the order
		 * and in all subscriptions linked to this order
		 *
		 * @param WC_Order $order Order.
		 * @param string   $billing_agreement_id Billing Agreement.
		 *
		 * @return void
		 */
		private function save_billing_agreement_id( $order, $billing_agreement_id ) {
			$order->update_meta_data( 'billing_agreement_id', $billing_agreement_id );
			$order->set_payment_method( $this->id );
			$order->set_payment_method_title( $this->get_method_title() );

			$subscriptions = $order->get_meta( 'subscriptions' );

			if ( $subscriptions ) {
				foreach ( $subscriptions as $subscription_id ) {
					update_post_meta( $subscription_id, 'billing_agreement_id', $billing_agreement_id );
				}
			}
			$order->save();

		}

		/**
		 * Save payer information like PayPal Standard in the parent order.
		 *
		 * @param WC_Order                $order Order.
		 * @param YITH_PayPal_EC_Response $response YITH_PayPal_EC_Response.
		 * @param bool                    $subscription .
		 *
		 * @since 1.0.0
		 */
		private function save_payer_info( $order, $response, $subscription = false ) {
			if ( $subscription ) {
				$args = array(
					'Payer PayPal address'  => $response->get_response_parameter( 'EMAIL' ),
					'Subscriber first name' => $response->get_response_parameter( 'FIRSTNAME' ),
					'Subscriber last name'  => $response->get_response_parameter( 'LASTNAME' ),
					'Subscriber ID'         => $response->get_response_parameter( 'PAYERID' ),
				);
			} else {
				$args = array(
					'Payer PayPal address' => $response->get_response_parameter( 'EMAIL' ),
					'Payer ID'             => $response->get_response_parameter( 'PAYERID' ),
				);
			}

			foreach ( $args as $key => $value ) {
				$order->update_meta_data( $key, $value );
			}

			$order->save();
		}

		/**
		 * Renew a subscription.
		 *
		 * @param WC_Order $order Order to renew.
		 * @param bool     $manually If the renew is manual or not.
		 *
		 * @return bool|void
		 */
		public function renew_subscriptions( $order, $manually = false ) {

			if ( ! $order ) {
				return false;
			}

			if ( $order->get_total() == 0 ) { //phpcs:ignore
				$order->payment_complete();
				return;
			}

			$is_a_renew     = $order->get_meta( 'is_a_renew' );
			$has_billing_id = $order->get_meta( 'billing_agreement_id' );

			if ( 'yes' !== $is_a_renew || empty( $has_billing_id ) ) {
				return;
			}

			$order_id = $order->get_id();
			$this->helper->log_add_message( esc_html__( 'New payment request for order: #', 'yith-paypal-express-checkout-for-woocommerce' ) . $order_id );

			try {
				$payment_response = $this->api->call_do_reference_transaction( $order );
				do_action( 'yith_paypal_ec_process_order_payment_with_billing_agreement', $order, $payment_response, $manually );
			} catch ( YITH_PayPal_EC_Exception $e ) {
				// translators: placeholder error message.
				$order->add_order_note( sprintf( esc_html__( 'An error occurred during the payment: %s', 'yith-paypal-express-checkout-for-woocommerce' ), $e->getMessage() ) );
				// translators: placeholder error message.
				$this->helper->log_add_message( sprintf( esc_html__( 'An error occurred %s', 'yith-paypal-express-checkout-for-woocommerce' ), $e->getMessage() ) );
			}

		}

		/**
		 * Check if there's a subscription in a valid PayPal IPN request.
		 */
		public static function ipn_handler() {

			include_once YITH_PAYPAL_EC_INC . 'abstract/class.yith-paypal-ec-response-handler.php';
			include_once YITH_PAYPAL_EC_INC . 'class.yith-paypal-ec-ipn-handler.php';

			do_action( 'ywsbs_ipn_handler_before_call_handler' );
			$the_class = apply_filters( 'ywsbs_ipn_handler_class_to_call', 'YITH_PayPal_EC_IPN_Handler' );

			new $the_class();
		}

		/**
		 * Set user details
		 */
		public function set_checkout_user_details() {
			$paypal_args = WC()->session->yith_paypal_session;

			if ( ! is_checkout() || ! $paypal_args || empty( $paypal_args['shipping_info'] ) ) {
				return;
			}

			foreach ( $paypal_args['shipping_info'] as $field => $value ) {
				if ( $value ) {

					if ( 'shiptoname' !== $field ) {
						$_POST[ 'billing_' . $field ]  = $value;
						$_POST[ 'shipping_' . $field ] = $value;
					} else {
						if ( ! isset( $_POST['shipping_last_name'] ) || ! isset( $_POST['shipping_first_name'] ) ) { //phpcs:ignore
							$names                        = explode( ' ', $value );
							$_POST['shipping_first_name'] = $names[0];
							if ( count( $names ) > 1 ) {
								$_POST['shipping_last_name'] = '';
								for ( $i = 1; $i < count( $names ); $i++ ) { //phpcs:ignore
									$_POST['shipping_last_name'] .= $names[ $i ] . ' ';
								}
								$_POST['shipping_last_name'] = trim( $_POST['shipping_last_name'] );  //phpcs:ignore
							}
						}
					}
				}
			}
		}

		/**
		 * Return the label for place order button.
		 *
		 * @return mixed|void
		 */
		public function get_order_button_text() {
			$text = esc_html__( 'Proceed order', 'yith-paypal-express-checkout-for-woocommerce' );

			if ( $this->get_option( 'checkout_button' ) !== 'yes' ) {
				$text = esc_html__( 'Proceed to PayPal', 'yith-paypal-express-checkout-for-woocommerce' );
			}
			if ( isset( WC()->session->yith_paypal_session ) ) {
				$text = esc_html__( 'Complete Order', 'yith-paypal-express-checkout-for-woocommerce' );
			}

			return apply_filters( 'yith_paypal_ec_order_button_text', $text );
		}

		/**
		 * PayPal button
		 */
		public function form() {
			if ( $this->get_option( 'checkout_button' ) === 'yes' ) {
				wc_get_template( 'paypal-ec-button.php', null, '', YITH_PAYPAL_EC_TEMPLATE_PATH . '/' );
			} else {
				echo wp_kses_post( $this->description );
			}

		}

		/**
		 * Displays the PayPal fee
		 *
		 * @since 4.1.0
		 *
		 * @param int $order_id The ID of the order.
		 */
		public function display_order_fee( $order_id ) {
			if ( apply_filters( 'yith_ppec_hide_display_order_fee', false, $order_id ) ) {
				return;
			}

			$order = wc_get_order( $order_id );

			$fee      = $order->get_meta( '_yith_ppec_fee' );
			$currency = $order->get_currency();

			if ( ! $fee || ! $currency ) {
				return;
			}

			?>

			<tr>
				<td class="label yith-ppec-fee">
					<?php esc_html_e( 'PayPal Fee:', 'yith-paypal-express-checkout-for-woocommerce' ); ?>
				</td>
				<td width="1%"></td>
				<td class="total">
					-<?php echo wc_price( $fee, array( 'currency' => $currency ) ); ?>
				</td>
			</tr>

			<?php
		}
	}
}
