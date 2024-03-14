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
 * @since   1.0.0
 * @author  YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Implements YITH_PayPal_EC_IPN_Handler Class
 *
 * @class   YITH_PayPal_EC_IPN_Handler
 * @package YITH PayPal Express Checkout for WooCommerce
 * @since   1.0.0
 */
if ( ! class_exists( 'YITH_PayPal_EC_IPN_Handler' ) ) {

	/**
	 * Class YITH_PayPal_EC_IPN_Handler
	 */
	class YITH_PayPal_EC_IPN_Handler extends YITH_PayPal_EC_Response_Handler {

		/**
		 * Type of transaction
		 *
		 * @var array
		 */
		protected $subscription_transaction_types = array(
			'subscr_signup',
			'subscr_payment',
			'subscr_modify',
			'subscr_failed',
			'subscr_eot',
			'subscr_cancel',
			'recurring_payment_suspended_due_to_max_failed_payment',
			'recurring_payment_skipped',
			'recurring_payment_outstanding_payment',
			'merch_pmt',
		);

		/**
		 * Standard transactions
		 *
		 * @var array
		 */
		protected $standard_transaction_types = array( 'cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money', 'paypal_here' );

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 */
		public function __construct() {
			add_action( 'woocommerce_api_yith_paypal_ec', array( $this, 'check_response' ) );
			add_action( 'yith_paypal_ec_valid_ipn_request', array( $this, 'valid_response' ) );
			$this->sandbox = 'sandbox' === yith_paypal_ec()->ec->env;
		}

		/**
		 * Check for PayPal IPN Response.
		 */
		public function check_response() {

			if ( ! empty( $_POST ) && $this->validate_ipn() ) { //phpcs:ignore
				$posted = wp_unslash( $_POST ); //phpcs:ignore
				do_action( 'yith_paypal_ec_valid_ipn_request', $posted );
				exit;
			}

			return false;
		}

		/**
		 * Validate IPN
		 *
		 * @return bool
		 */
		protected function validate_ipn() {

			yith_paypal_ec()->ec->log_add_message( __( 'Checking IPN response is valid', 'yith-paypal-express-checkout-for-woocommerce' ) );

			yith_paypal_ec()->ec->log_add_message( __( 'POST: ', 'yith-paypal-express-checkout-for-woocommerce' ) . print_r( $_POST, true ) ); //phpcs:ignore

			// Get received values from post data.
			$validate_ipn        = wp_unslash( $_POST ); //phpcs:ignore
			$validate_ipn['cmd'] = '_notify-validate';

			// Send back post vars to paypal.
			$params = array(
				'body'        => $validate_ipn,
				'timeout'     => 60,
				'httpversion' => '1.1',
				'compress'    => false,
				'decompress'  => false,
				'user-agent'  => sprintf( '%s/%s', 'YITH_PayPal_EC', YITH_PAYPAL_EC_VERSION ),
			);

			// Post back to get a response.
			$request_url = $this->sandbox ? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' : 'https://ipnpb.paypal.com/cgi-bin/webscr';

			$response = wp_safe_remote_post( $request_url, $params );

			// Check to see if the request was valid.
			if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr( $response['body'], 'VERIFIED' ) ) {
				yith_paypal_ec()->ec->log_add_message( __( 'Received valid response from PayPal IPN', 'yith-paypal-express-checkout-for-woocommerce' ) );

				return true;
			}

			yith_paypal_ec()->ec->log_add_message( __( 'Received invalid response from PayPal IPN', 'yith-paypal-express-checkout-for-woocommerce' ) );

			if ( is_wp_error( $response ) ) {
				yith_paypal_ec()->ec->log_add_message( __( 'Error response: ', 'yith-paypal-express-checkout-for-woocommerce' ) . $response->get_error_message() );
			}

			return false;
		}

		/**
		 * There was a valid response
		 *
		 * @param   array  $posted  Post data after wp_unslash.
		 *
		 * @throws Exception Throws an exception message.
		 */
		public function valid_response( $posted ) {
			$order = false;

			if ( ! empty( $posted['custom'] ) ) {
				$order = $this->get_paypal_order( $posted['custom'] );
			} elseif ( ! empty( $posted['invoice'] ) ) {
				$order = $this->get_paypal_order_from_invoice( $posted['invoice'] );
			}

			if ( $order ) {
				yith_paypal_ec()->ec->log_add_message( __( 'Found order:', 'yith-paypal-express-checkout-for-woocommerce' ) . $order->get_id() );
				$this->process_paypal_request( $order, $posted );
			} else {
				yith_paypal_ec()->ec->log_add_message( __( 'Order Not Found: #:', 'yith-paypal-express-checkout-for-woocommerce' ) . print_r( $posted, 1 ) ); //phpcs:ignore
			}
		}

		/**
		 * Handle a completed payment
		 *
		 * @param   WC_Order  $order   Order.
		 * @param   array     $posted  Posted arguments.
		 *
		 * @throws Exception Throws an exception message.
		 */
		protected function process_paypal_request( $order, $posted ) {

			if ( isset( $posted['mc_currency'] ) ) {
				$this->validate_currency( $order, $posted['mc_currency'] );
				yith_paypal_ec()->ec->log_add_message( __( 'Validate currency OK', 'yith-paypal-express-checkout-for-woocommerce' ) );
			}

			$transaction_type         = $posted['txn_type'];
			$posted['payment_status'] = strtolower( $posted['payment_status'] );

			yith_paypal_ec()->ec->log_add_message( 'Transaction type ' . $transaction_type );
			// check if the IPN is for process a subscription payment.
			if ( in_array( $transaction_type, $this->subscription_transaction_types, true ) && defined( 'YITH_YWSBS_PREMIUM' ) ) {
				// subscription.
				$this->save_paypal_meta_data( $order, $posted );
				$this->paypal_ipn_request( $order, $posted );
			} else {
				// translators: Placeholder: order id.
				yith_paypal_ec()->ec->log_add_message( sprintf( __( 'Found order #%s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id() ) );
				// translators: Placeholder: payment status.
				yith_paypal_ec()->ec->log_add_message( sprintf( __( 'Payment status: %s', 'yith-paypal-express-checkout-for-woocommerce' ), $posted['payment_status'] ) );

				if ( method_exists( $this, 'payment_status_' . $posted['payment_status'] ) ) {
					call_user_func( array( $this, 'payment_status_' . $posted['payment_status'] ), $order, $posted );
				}
			}
		}

		/**
		 * Handle a completed payment.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_completed( $order, $posted ) {

			if ( $order->has_status( wc_get_is_paid_statuses() ) ) {
				// translators: Placeholder: order id.
				yith_paypal_ec()->ec->log_add_message( sprintf( __( 'Aborting, Order #%d is already complete.', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id() ) );
				exit;
			}

			if ( ! in_array( $posted['txn_type'], $this->standard_transaction_types, true ) ) {
				// translators: Placeholder: transaction type and order id.
				yith_paypal_ec()->ec->log_add_message( sprintf( __( 'Aborting, Transaction type %1$s cannot be processed for order #%2$d.', 'yith-paypal-express-checkout-for-woocommerce' ), $posted['txn_type'], $order->get_id() ) );
				exit;
			}

			$this->validate_amount( $order, $posted['mc_gross'] );

			if ( 'completed' === $posted['payment_status'] ) {
				if ( $order->has_status( 'cancelled' ) ) {
					$this->payment_status_paid_cancelled_order( $order, $posted );
				}

				$this->payment_complete( $order, ( ! empty( $posted['txn_id'] ) ? wc_clean( $posted['txn_id'] ) : '' ), __( 'IPN payment completed', 'yith-paypal-express-checkout-for-woocommerce' ) );

				if ( ! empty( $posted['mc_fee'] ) ) {
					// Log paypal transaction fee.
					$order->update_meta_data( 'PayPal Transaction Fee', wc_clean( $posted['mc_fee'] ) );
				}
			} else {
				if ( 'authorization' === $posted['pending_reason'] ) {
					$this->payment_on_hold( $order, __( 'Payment authorized. Change payment status to processing or complete to capture funds.', 'yith-paypal-express-checkout-for-woocommerce' ) );
				} else {
					/* translators: %s: pending reason. */
					$this->payment_on_hold( $order, sprintf( __( 'Pending payment (%s).', 'yith-paypal-express-checkout-for-woocommerce' ), $posted['pending_reason'] ) );
				}
			}
		}

		/**
		 * Handle a pending payment.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_pending( $order, $posted ) {
			$this->payment_status_completed( $order, $posted );
		}

		/**
		 * Handle a failed payment.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_failed( $order, $posted ) {
			/* translators: %s: payment status. */
			$order->update_status( 'failed', sprintf( __( 'Payment %s via IPN.', 'yith-paypal-express-checkout-for-woocommerce' ), wc_clean( $posted['payment_status'] ) ) );
		}

		/**
		 * Handle a denied payment.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_denied( $order, $posted ) {
			$this->payment_status_failed( $order, $posted );
		}

		/**
		 * Handle an expired payment.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_expired( $order, $posted ) {
			$this->payment_status_failed( $order, $posted );
		}

		/**
		 * Handle a voided payment.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_voided( $order, $posted ) {
			$this->payment_status_failed( $order, $posted );
		}

		/**
		 * When a user cancelled order is marked paid.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_paid_cancelled_order( $order, $posted ) {
			$this->send_ipn_email_notification(
			/* translators: %s: order link. */
				sprintf( __( 'Payment for cancelled order %s received', 'yith-paypal-express-checkout-for-woocommerce' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
				/* translators: %s: order ID. */
				sprintf( __( 'Order #%s has been marked as paid via PayPal IPN, but was previously cancelled. Admin action required.', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_order_number() )
			);
		}

		/**
		 * Handle a refunded order.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_refunded( $order, $posted ) {

			// Only handle full refunds, not partial.
			if ( floatval( $order->get_total() ) == $posted['mc_gross'] * - 1 ) { //phpcs:ignore
				/* translators: %s: payment status. */
				$order->update_status( 'refunded', sprintf( __( 'Payment %s via IPN.', 'yith-paypal-express-checkout-for-woocommerce' ), strtolower( $posted['payment_status'] ) ) );

				$this->send_ipn_email_notification(
				/* translators: %s: order link. */
					sprintf( __( 'Payment for order %s - refunded', 'yith-paypal-express-checkout-for-woocommerce' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
					/* translators: %1$s: order ID, %2$s: reason code. */
					sprintf( __( 'Order #%1$s has been marked as refunded - PayPal reason code: %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_order_number(), $posted['reason_code'] )
				);
			}
		}

		/**
		 * Handle a reversal.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_reversed( $order, $posted ) {
			/* translators: %s: payment status. */
			$order->update_status( 'on-hold', sprintf( __( 'Payment %s via IPN.', 'yith-paypal-express-checkout-for-woocommerce' ), wc_clean( $posted['payment_status'] ) ) );

			$this->send_ipn_email_notification(
			/* translators: %s: order link. */
				sprintf( __( 'Payment for order %s - reversed', 'yith-paypal-express-checkout-for-woocommerce' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
				/* translators: %1$s: order ID, %2$s: reason code. */
				sprintf( __( 'Order #%1$s has been marked as on-hold due to a reversal - PayPal reason code: %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_order_number(), wc_clean( $posted['reason_code'] ) )
			);
		}

		/**
		 * Handle a cancelled reversal.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function payment_status_canceled_reversal( $order, $posted ) {
			$this->send_ipn_email_notification(
			/* translators: %s: order link. */
				sprintf( __( 'Reversal cancelled for order #%s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_order_number() ),
				/* translators: %1$s: order ID, %2$s: order link. */
				sprintf( __( 'Order #%1$s reversal was cancelled. Please check the status of payment and update the order status accordingly here: %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_order_number(), esc_url( $order->get_edit_order_url() ) )
			);
		}

		/**
		 * Send a notification to the user handling orders.
		 *
		 * @param   string  $subject  Email subject.
		 * @param   string  $message  Email message.
		 *
		 * @return bool
		 */
		protected function send_ipn_email_notification( $subject, $message ) {
			$helper = yith_paypal_ec()->ec;

			if ( 'no' === $helper->ipn_notification ) {
				return false;
			}

			$mailer  = WC()->mailer();
			$message = $mailer->wrap_message( $subject, $message );

			$recipient = ! empty( $helper->ipn_notification_email ) && is_email( $helper->ipn_notification_email ) ? $helper->ipn_notification_email : get_option( 'admin_email' );
			$mailer->send( $recipient, wp_strip_all_tags( $subject ), $message );
		}

		/**
		 * Catch the paypal ipn request for subscription
		 *
		 * @param   WC_Order  $order     Order.
		 * @param   array     $ipn_args  IPN content.
		 *
		 * @return array|void
		 * @throws Exception Throws an exception message.
		 */
		protected function paypal_ipn_request( $order, $ipn_args ) {
			$helper = yith_paypal_ec()->ec;
			$helper->log_add_message( __( 'Subscription Response Start', 'yith-paypal-express-checkout-for-woocommerce' ) );

			$order_id = $order->get_id();

			// check if the transaction has been processed.
			$order_transaction_ids = $order->get_meta( '_paypal_transaction_ids' );
			$order_transactions    = $this->is_a_valid_transaction( $order_transaction_ids, $ipn_args );

			if ( $order_transactions ) {
				$order->update_meta_data( '_paypal_transaction_ids', $order_transactions );
				$order->save();
			} else {
				$helper->log_add_message( __( 'Transaction ID already processed', 'yith-paypal-express-checkout-for-woocommerce' ) );

				return;
			}

			// get the subscriptions of the order.
			$subscriptions = $order->get_meta( 'subscriptions' );
			if ( empty( $subscriptions ) ) {
				// translators: Placeholder: order id.
				$helper->log_add_message( sprintf( __( 'IPN subscription payment error - %s -  has no subscriptions ', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );

				return;
			} else {
				$helper->log_add_message( __( 'Subscriptions found:  ', 'yith-paypal-express-checkout-for-woocommerce' ) . print_r( $subscriptions, true ) ); //phpcs:ignore
			}

			$valid_order_statuses = array( 'on-hold', 'pending', 'failed', 'cancelled' );

			switch ( $ipn_args['txn_type'] ) {
				case 'subscr_signup':
					$this->update_order_payment_info( $order_id, $ipn_args );

					$order->add_order_note( __( 'IPN subscription started', 'yith-paypal-express-checkout-for-woocommerce' ) );

					if ( isset( $ipn_args['mc_amount1'] ) && 0 == $ipn_args['mc_amount1'] ) { //phpcs:ignore
						$order->payment_complete( $ipn_args['txn_id'] );
					}

					foreach ( $subscriptions as $subscription_id ) {
						$subscription = new YWSBS_Subscription( $subscription_id );
						$subscription->set( 'paypal_transaction_id', $ipn_args['txn_id'] );
						$subscription->set( 'paypal_subscriber_id', $ipn_args['subscr_id'] );
					}

					break;
				case 'recurring_payment_outstanding_payment': // follow the next.
				case 'merch_pmt': // follow the next.
					if ( ! isset( $ipn_args['payment_type'] ) || isset( $ipn_args['payment_type'] ) && 'echeck' !== strtolower( $ipn_args['payment_type'] ) ) {
						break;
					}
				case 'subscr_payment':
					if ( 'completed' === strtolower( $ipn_args['payment_status'] ) ) {

						foreach ( $subscriptions as $subscription_id ) {
							$subscription = new YWSBS_Subscription( $subscription_id );

							$transaction_ids = get_post_meta( $subscription_id, '_paypal_transaction_ids', true );
							$transactions    = $this->is_a_valid_transaction( $transaction_ids, $ipn_args );
							if ( $transactions ) {
								update_post_meta( $subscription_id, '_paypal_transaction_ids', $transactions );
							} else {
								break;
							}

							// get order to check.
							$pending_order = $subscription->renew_order;
							$last_order    = intval( $pending_order ) ? wc_get_order( $pending_order ) : false;

							// get the subscriber id from ipn_args.
							$sub_id = isset( $ipn_args['subscr_id'] ) ? $ipn_args['subscr_id'] : ( isset( $ipn_args['recurring_payment_id'] ) ? $ipn_args['recurring_payment_id'] : '' );

							if ( 'pending' === $subscription->status || ( ! $last_order && $order->has_status( $valid_order_statuses ) ) ) {
								$this->pay_and_complete_order( $order, $ipn_args, $sub_id );
							} elseif ( $last_order ) {
								$this->pay_and_complete_order( $last_order, $ipn_args, $sub_id );
							} else {
								// if the renew_order is not created try to create it.
								$new_order_id = YWSBS_Subscription_Order()->renew_order( $subscription->id );
								if ( ! $new_order_id ) {
									YITH_WC_Activity()->add_activity( $subscription_id, 'renew-order', 'failed', $order_id, __( 'Renew order creation failed', 'yith-paypal-express-checkout-for-woocommerce' ) );
									$helper->log_add_message( __( 'YITH PayPal EC - Renew order creation failed ', 'yith-paypal-express-checkout-for-woocommerce' ) );

									return;
								}
								$new_order = wc_get_order( $new_order_id );
								$subscription->set( 'renew_order', $new_order_id );
								$this->pay_and_complete_order( $new_order, $ipn_args, $sub_id );
							}

							$subscription->set( 'paypal_transaction_id', $ipn_args['txn_id'] );
							$subscription->set( 'paypal_subscriber_id', $sub_id );
							$subscription->set( 'payment_method', YITH_PayPal_EC::$gateway_id );
							$subscription->set( 'payment_method_title', $helper->title );

						}
					}
					// not for express checkout.
					if ( isset( $ipn_args['subscr_id'] ) && 'pending' === strtolower( $ipn_args['payment_status'] ) && 'echeck' === strtolower( $ipn_args['payment_type'] ) ) {

						foreach ( $subscriptions as $subscription_id ) {
							$subscription = new YWSBS_Subscription( $subscription_id );

							$transaction_ids = get_post_meta( $subscription_id, '_paypal_transaction_ids', true );
							$transactions    = $this->is_a_valid_transaction( $transaction_ids, $ipn_args );
							if ( $transactions ) {
								update_post_meta( $subscription_id, '_paypal_transaction_ids', $transactions );
							} else {
								break;
							}

							$pending_order = $subscription->renew_order;
							$last_order    = intval( $pending_order ) ? wc_get_order( $pending_order ) : false;

							if ( 'pending' === $subscription->status || ( ! $last_order && $order->has_status( $valid_order_statuses ) ) ) {
								// first payment.
								update_post_meta( $subscription_id, 'start_date', current_time( 'timestamp' ) ); //phpcs:ignore
								update_post_meta( $subscription_id, 'payment_type', $ipn_args['payment_type'] );
								// in this case change the status of order in on-hold waiting the paypal payment.
								$order->update_status( 'on-hold', __( 'Paypal echeck payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
								$order->update_meta_data( 'Payment type', $ipn_args['payment_type'] );
								WC()->cart->empty_cart();

							} elseif ( $last_order ) {
								// renew order.
								$last_order->add_order_note( __( 'YITH PayPal EC - IPN Pending payment for echeck payment type', 'yith-paypal-express-checkout-for-woocommerce' ) );
								// if the renewal is payed with echeck and it is in pending, the subscription is suspended.
								$subscription->update_status( 'suspended', YITH_PayPal_EC::$gateway_id );
								YITH_WC_Activity()->add_activity( $subscription_id, 'suspended', 'success', $order_id, __( 'Subscription has been suspended because pending payment via echeck', 'yith-paypal-express-checkout-for-woocommerce' ) );

								$last_order->add_order_note( __( 'YITH PayPal EC - Subscription has been suspended because pending payment via echeck', 'yith-paypal-express-checkout-for-woocommerce' ) );

							} else {
								// if the renew_order is not created try to create it.
								$new_order_id = YWSBS_Subscription_Order()->renew_order( $subscription->id );
								if ( ! $new_order_id ) {
									YITH_WC_Activity()->add_activity( $subscription_id, 'renew-order', 'failed', $order_id, __( 'Renew order creation failed', 'yith-paypal-express-checkout-for-woocommerce' ) );
									$helper->log_add_message( __( 'YITH PayPal EC - Renew order creation failed ', 'yith-paypal-express-checkout-for-woocommerce' ) );

									return;
								}
								$new_order = wc_get_order( $new_order_id );
								$new_order->add_order_note( __( 'YITH PayPal EC - IPN Pending payment for echeck payment type', 'yith-paypal-express-checkout-for-woocommerce' ) );
							}
						}
					}
					// not for express checkout.
					if ( isset( $ipn_args['subscr_id'] ) && 'failed' === strtolower( $ipn_args['payment_status'] ) ) {
						if ( isset( $ipn_args['subscr_id'] ) ) {
							$paypal_sub_id = $ipn_args['subscr_id'];
							$order_sub_id  = $order->get_meta( 'Subscriber ID' );
							if ( $paypal_sub_id !== $order_sub_id ) {
								$helper->log_add_message( __( 'YITH PayPal EC - IPN request ignored - Profile ID does not match Subscription subscriber ID, for order ', 'yith-paypal-express-checkout-for-woocommerce' ) . $order_id );
							} else {
								$subscriptions = $order->get_meta( 'subscriptions' );
								if ( empty( $subscriptions ) ) {
									// translators: Placeholder: order id.
									$helper->log_add_message( sprintf( __( 'IPN subscription cancellation request ignored - order %s has no subscriptions', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );
								}

								// let's remove woocommerce default IPN handling, that would switch parent order to Failed.
								remove_all_actions( 'valid-paypal-standard-ipn-request', 10 );

								foreach ( $subscriptions as $subscription_id ) {

									$subscription = ywsbs_get_subscription( $subscription_id );

									$transaction_ids = get_post_meta( $subscription_id, '_paypal_transaction_ids', true );
									$transactions    = $this->is_a_valid_transaction( $transaction_ids, $ipn_args );
									if ( $transactions ) {
										update_post_meta( $subscription_id, '_paypal_transaction_ids', $transactions );
									} else {
										break;
									}

									$pending_order = $subscription->renew_order;
									$last_order    = intval( $pending_order ) ? wc_get_order( $pending_order ) : false;

									if ( 'pending' === $subscription->status || ( ! $last_order && $order->has_status( $valid_order_statuses ) ) ) {
										continue;
									} elseif ( $last_order ) {
										$last_order->add_order_note( __( 'YITH PayPal EC - IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
										$helper->log_add_message( __( 'IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
									} else {
										// if the renew_order is not created try to create it.
										$new_order_id = YWSBS_Subscription_Order()->renew_order( $subscription->id );
										if ( ! $new_order_id ) {
											YITH_WC_Activity()->add_activity( $subscription_id, 'renew-order', 'failed', $order_id, __( 'Renew order creation failed', 'yith-paypal-express-checkout-for-woocommerce' ) );
											$helper->log_add_message( __( 'Renew order creation failed', 'yith-paypal-express-checkout-for-woocommerce' ) );

											return;
										}
										$new_order = wc_get_order( $new_order_id );
										$new_order->add_order_note( __( 'YITH PayPal EC - IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
										$helper->log_add_message( __( 'IPN Failed payment for order ', 'yith-paypal-express-checkout-for-woocommerce' ) . $new_order_id );
									}

									// update the number of failed attempt.
									$subscription->register_failed_attempt();

									if ( isset( $ipn_args['retry_at'] ) ) {
										$order->update_meta_data( 'next_payment_attempt', strtotime( $ipn_args['retry_at'] ) );
									}

									$suspend_subscription = apply_filters( 'ywsbs_suspend_for_failed_recurring_payment', get_option( 'ywsbs_suspend_for_failed_recurring_payment', 'no' ) );
									if ( 'yes' === $suspend_subscription ) {
										$subscription->update_status( 'suspended', YITH_PayPal_EC::$gateway_id );
										YITH_WC_Activity()->add_activity( $subscription_id, 'suspended', 'success', $order_id, __( 'Subscription has been suspended for failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
									}

									$order->add_order_note( __( 'YITH PayPal EC - IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
									$helper->log_add_message( __( 'IPN Failed payment for order ', 'yith-paypal-express-checkout-for-woocommerce' ) . $order_id );

								}
							}
						}
					}
					break;
				case 'subscr_modify':
					break;
				case 'subscr_failed':
					if ( isset( $ipn_args['subscr_id'] ) ) {
						$paypal_sub_id = $ipn_args['subscr_id'];
						$order_sub_id  = $order->get_meta( 'Subscriber ID' );

						if ( $paypal_sub_id !== $order_sub_id ) {
							$helper->log_add_message( __( ' IPN request ignored - Profile ID does not match Subscription subscriber ID, for order ', 'yith-paypal-express-checkout-for-woocommerce' ) . $order_id );
						} else {
							$subscriptions = $order->get_meta( 'subscriptions' );

							if ( empty( $subscriptions ) ) {
								// translators: Placeholder: order id.
								$helper->log_add_message( sprintf( __( 'IPN subscription failed payment request ignored - order %s has no subscriptions', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );
							}

							foreach ( $subscriptions as $subscription_id ) {
								$subscription = ywsbs_get_subscription( $subscription_id );

								$transaction_ids = get_post_meta( $subscription_id, '_paypal_transaction_ids', true );
								$transactions    = $this->is_a_valid_transaction( $transaction_ids, $ipn_args );
								if ( $transactions ) {
									update_post_meta( $subscription_id, '_paypal_transaction_ids', $transactions );
								} elseif ( isset( $ipn_args['retry_at'] ) ) {
									$retry_at_meta = get_post_meta( $subscription_id, '_retry_at', true );
									if ( $retry_at_meta === $ipn_args['retry_at'] ) {
										break;
									} else {
										update_post_meta( $subscription_id, '_retry_at', $ipn_args['retry_at'] );
									}
								} else {
									break;
								}

								$last_order = intval( $subscription->renew_order ) ? wc_get_order( $subscription->renew_order ) : false;

								if ( 'pending' === $subscription->status || ( ! $last_order && $order->has_status( $valid_order_statuses ) ) ) {
									continue;
								} elseif ( $last_order ) {
									$last_order->add_order_note( __( 'YITH PayPal EC - IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
								} else {
									// if the renew_order is not created try to create it.
									$new_order_id = YWSBS_Subscription_Order()->renew_order( $subscription->id );
									if ( ! $new_order_id ) {
										YITH_WC_Activity()->add_activity( $subscription_id, 'renew-order', 'failed', $order_id, __( 'Renew order creation failed', 'yith-paypal-express-checkout-for-woocommerce' ) );

										return;
									}
									$new_order = wc_get_order( $new_order_id );
									$new_order->add_order_note( __( 'YITH PayPal EC - IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
								}

								// update the number of failed attempt.
								$subscription->register_failed_attempt();
								if ( isset( $ipn_args['retry_at'] ) ) {
									$order->update_meta_data( 'next_payment_attempt', strtotime( $ipn_args['retry_at'] ) );
								}

								$suspend_subscription = apply_filters( 'ywsbs_suspend_for_failed_recurring_payment', get_option( 'ywsbs_suspend_for_failed_recurring_payment', 'no' ) );
								if ( 'yes' === $suspend_subscription ) {
									$subscription->update_status( 'suspended', YITH_PayPal_EC::$gateway_id );
									YITH_WC_Activity()->add_activity( $subscription_id, 'suspended', 'success', $order_id, __( 'Subscription has been suspended for failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
								}

								$order->add_order_note( __( 'YITH PayPal EC - IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );

								// Subscription Cancellation Completed.
								// translators: Placeholder: order id.
								$helper->log_add_message( sprintf( __( 'IPN Failed payment for order %s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );
							}
						}
					}
					break;
				case 'recurring_payment_skipped':
					if ( isset( $ipn_args['recurring_payment_id'] ) ) {

						$paypal_sub_id = $ipn_args['recurring_payment_id'];
						$order_sub_id  = $order->get_meta( 'Subscriber ID' );
						if ( $paypal_sub_id !== $order_sub_id ) {
							// translators: Placeholder: order id.
							$helper->log_add_message( sprintf( __( 'IPN subscription failed payment - new PayPal Profile ID linked to this subscription, for order %s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );
						} else {
							$subscriptions = $order->get_meta( 'subscriptions' );

							if ( empty( $subscriptions ) ) {
								// translators: Placeholder: order id.
								$helper->log_add_message( sprintf( __( 'IPN subscription failed payment request ignored - order %s there are not subscriptions', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );
							}

							foreach ( $subscriptions as $subscription_id ) {

								$subscription = ywsbs_get_subscription( $subscription_id );

								$transaction_ids = get_post_meta( $subscription_id, '_paypal_transaction_ids', true );
								$transactions    = $this->is_a_valid_transaction( $transaction_ids, $ipn_args );
								if ( $transactions ) {
									update_post_meta( $subscription_id, '_paypal_transaction_ids', $transactions );
								} else {
									break;
								}

								$last_order = intval( $subscription->renew_order ) ? wc_get_order( $subscription->renew_order ) : false;

								if ( 'pending' === $subscription->status || ( ! $last_order && $order->has_status( $valid_order_statuses ) ) ) {
									continue;
								} elseif ( $last_order ) {
									$last_order->add_order_note( __( 'YITH PayPal EC - IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
								} else {
									// if the renew_order is not created try to create it.
									$new_order_id = YWSBS_Subscription_Order()->renew_order( $subscription->id );
									if ( ! $new_order_id ) {
										YITH_WC_Activity()->add_activity( $subscription_id, 'renew-order', 'failed', $order_id, __( 'Renew order creation failed', 'yith-paypal-express-checkout-for-woocommerce' ) );

										return;
									}
									$new_order = wc_get_order( $new_order_id );
									$new_order->add_order_note( __( 'YITH PayPal EC - IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );

								}

								// update the number of failed attemp.
								$subscription->register_failed_attempt();
								if ( isset( $ipn_args['retry_at'] ) ) {
									$order->update_meta_data( 'next_payment_attempt', strtotime( $ipn_args['retry_at'] ) );
								}

								$suspend_subscription = apply_filters( 'ywsbs_suspend_for_failed_recurring_payment', get_option( 'ywsbs_suspend_for_failed_recurring_payment', 'no' ) );
								if ( 'yes' === $suspend_subscription ) {

									$subscription->update_status( 'suspended', YITH_PayPal_EC::$gateway_id );

									YITH_WC_Activity()->add_activity( $subscription_id, 'suspended', 'success', $order_id, __( 'Subscription has been suspended for failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );

								}
								$order->add_order_note( __( 'YITH PayPal EC - IPN Failed payment', 'yith-paypal-express-checkout-for-woocommerce' ) );

								// Subscription Cancellation Completed.
								// translators: Placeholder: order id.
								$helper->log_add_message( sprintf( __( 'IPN Failed payment for order %s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );

							}
						}
					}
					break;
				case 'subscr_eot':
					/*subscription expired*/
					break;
				case 'recurring_payment_suspended_due_to_max_failed_payment':
					if ( isset( $ipn_args['recurring_payment_id'] ) ) {
						$paypal_sub_id = $ipn_args['recurring_payment_id'];
						$order_sub_id  = $order->get_meta( 'Subscriber ID' );

						if ( $paypal_sub_id !== $order_sub_id ) {
							// translators: Placeholder: order id.
							$helper->log_add_message( sprintf( __( 'IPN request ignored - Profile ID does not match Subscription subscriber ID, for order %s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );

						} else {
							$subscriptions = $order->get_meta( 'subscriptions' );

							if ( empty( $subscriptions ) ) {
								// translators: Placeholder: order id.
								$helper->log_add_message( sprintf( __( 'YITH PayPal EC - IPN subscription cancellation for failed request ignored. There are no subscriptions in order %s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );
							}

							foreach ( $subscriptions as $subscription_id ) {

								$transaction_ids = get_post_meta( $subscription_id, '_paypal_transaction_ids', true );
								$transactions    = $this->is_a_valid_transaction( $transaction_ids, $ipn_args );
								if ( $transactions ) {
									update_post_meta( $subscription_id, '_paypal_transaction_ids', $transactions );
								} else {
									break;
								}

								$subscription = ywsbs_get_subscription( $subscription_id );

								// check if the subscription has max num of attemps.
								$failed_attemp      = $order->get_meta( 'failed_attemps' );
								$max_failed_attemps = ywsbs_get_max_failed_attemps_list();

								if ( $failed_attemp >= $max_failed_attemps[ YITH_PayPal_EC::$gateway_id ] - 1 ) {
									$subscription->cancel( false );
									YITH_WC_Activity()->add_activity( $subscription->id, 'cancelled', 'success', $order_id, __( 'Subscription cancelled for max failed attempts: recurring_payment_suspended_due_to_max_failed_payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
									$order->add_order_note( __( 'YITH PayPal EC - Subscription cancelled for max failed attempts: recurring_payment_suspended_due_to_max_failed_payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
									// Subscription Cancellation Completed.
									// translators: Placeholder: order id.
									$helper->log_add_message( sprintf( __( 'Subscription cancelled for max failed attempts: recurring_payment_suspended_due_to_max_failed_payment for order %s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );
								} else {
									$subscription->update_status( 'suspended', YITH_PayPal_EC::$gateway_id );
									YITH_WC_Activity()->add_activity( $subscription_id, 'suspended', 'success', $order_id, __( 'Subscription has been suspended. PayPal IPN message received: recurring_payment_suspended_due_to_max_failed_payment', 'yith-paypal-express-checkout-for-woocommerce' ) );

									$last_order = intval( $subscription->renew_order ) ? wc_get_order( $subscription->renew_order ) : false;

									if ( $last_order ) {
										$last_order->add_order_note( __( 'YITH PayPal EC - IPN message: recurring_payment_suspended_due_to_max_failed_payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
									} else {
										$order->add_order_note( __( 'YITH PayPal EC - IPN message: recurring_payment_suspended_due_to_max_failed_payment', 'yith-paypal-express-checkout-for-woocommerce' ) );
									}
								}
							}
						}
					}
					break;
				case 'subscr_cancel':
					/*subscription cancelled*/
					$paypal_sub_id = $ipn_args['subscr_id'];
					$order_sub_id  = $order->get_meta( 'Subscriber ID' );

					if ( $paypal_sub_id !== $order_sub_id ) {
						// translators: Placeholder: order id.
						$helper->log_add_message( sprintf( __( 'IPN subscription cancellation request ignored - new PayPal Profile ID linked to this subscription, for order %s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );
					} else {
						$subscriptions = $order->get_meta( 'subscriptions' );

						if ( empty( $subscriptions ) ) {
							// translators: Placeholder: order id.
							$helper->log_add_message( sprintf( __( 'IPN subscription cancellation request ignored - order %s has no subscriptions', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );

						}

						foreach ( $subscriptions as $subscription_id ) {

							$transaction_ids = get_post_meta( $subscription_id, '_paypal_transaction_ids', true );
							$transactions    = $this->is_a_valid_transaction( $transaction_ids, $ipn_args );
							if ( $transactions ) {
								update_post_meta( $subscription_id, '_paypal_transaction_ids', $transactions );
							} else {
								break;
							}

							$subscription = new YWSBS_Subscription( $subscription_id );
							$subscription->cancel( false );
							YITH_WC_Activity()->add_activity( $subscription->id, 'cancelled', 'success', $order_id, __( 'Subscription cancelled by gateway', 'yith-paypal-express-checkout-for-woocommerce' ) );

							$order->add_order_note( __( 'YITH PayPal EC - IPN subscription cancelled for the order.', 'yith-paypal-express-checkout-for-woocommerce' ) );

							// Subscription Cancellation Completed.
							// translators: Placeholder: order id.
							$helper->log_add_message( sprintf( __( 'IPN subscription cancelled order %s', 'yith-paypal-express-checkout-for-woocommerce' ), $order_id ) );

						}
					}

					break;
				default:
			}

		}

		/**
		 * Update order payment info
		 *
		 * @param   int    $order_id  Order id.
		 * @param   array  $ipn_args  IPN content.
		 */
		protected function update_order_payment_info( $order_id, $ipn_args ) {
			$order = wc_get_order( $order_id );
			$order->update_meta_data( 'Subscriber ID', $ipn_args['subscr_id'] );
			$order->update_meta_data( 'Subscriber first name', $ipn_args['first_name'] );
			$order->update_meta_data( 'Subscriber last name', $ipn_args['last_name'] );
			$order->update_meta_data( 'Subscriber address', $ipn_args['payer_email'] );
			$order->update_meta_data( 'Payment type', wc_clean( $ipn_args['payment_type'] ) );
			$order->update_meta_data( '_payment_method_title', yith_paypal_ec()->ec->title );
			$order->save();
		}

		/**
		 * Pay for complete order
		 *
		 * @param   WC_Order  $order     Order.
		 * @param   array     $ipn_args  IPN content.
		 * @param   int       $sub_id    Subscriber id.
		 */
		protected function pay_and_complete_order( $order, $ipn_args, $sub_id ) {

			isset( $ipn_args['mc_gross'] ) && $this->validate_amount( $order, $ipn_args['mc_gross'] );

			$order_id = $order->get_id();
			$sub_id && $order->update_meta_data( 'Subscriber ID', $sub_id );
			$this->update_order_payment_info( $order_id, $ipn_args );
			isset( $ipn_args['mp_id'] ) && $order->update_meta_data( 'billing_agreement_id', $ipn_args['mp_id'] );
			$order->add_order_note( __( 'IPN subscription payment completed.', 'yith-paypal-express-checkout-for-woocommerce' ) );
			$order->payment_complete( $ipn_args['txn_id'] );
		}

		/**
		 * Check if the transaction is valid
		 *
		 * @param   string  $transaction_ids  Transaction id.
		 * @param   array   $ipn_args         IPN content.
		 *
		 * @return array|bool
		 */
		protected function is_a_valid_transaction( $transaction_ids, $ipn_args ) {
			$transaction_ids = array();
			// check if the ipn request as been processed.
			if ( isset( $ipn_args['txn_id'] ) ) {
				$transaction_id = $ipn_args['txn_id'] . '-' . $ipn_args['txn_type'];

				if ( isset( $ipn_args['payment_status'] ) ) {
					$transaction_id .= '-' . $ipn_args['payment_status'];
				}
				if ( empty( $transaction_ids ) || ! in_array( $transaction_id, $transaction_ids, true ) ) {
					$transaction_ids[] = $transaction_id;
				} else {
					if ( $this->debug ) {
						// translators: 1. track id.
						yith_paypal_ec()->ec->log_add_message( sprintf( __( 'YITH PayPal EC - Subscription IPN Error: IPN %s message has already been correctly handled.', 'yith-paypal-express-checkout-for-woocommerce' ), $transaction_id ) );
					}

					return false;
				}
			} elseif ( isset( $ipn_args['ipn_track_id'] ) ) {
				$track_id = $ipn_args['txn_type'] . '-' . $ipn_args['ipn_track_id'];
				if ( empty( $transaction_ids ) || ! in_array( $track_id, $transaction_ids, true ) ) {
					$transaction_ids[] = $track_id;
				} else {
					if ( $this->debug ) {
						// translators: 1. track id.
						yith_paypal_ec()->ec->log_add_message( sprintf( __( 'YITH PayPal EC - Subscription IPN Error: IPN %s message has already been correctly handled.', 'yith-paypal-express-checkout-for-woocommerce' ), $track_id ) );
					}

					return false;
				}
			}

			return $transaction_ids;

		}

		/**
		 * Check for a valid transaction type
		 *
		 * @param   string  $txn_type  Type of request.
		 *
		 * @return bool
		 */
		protected function validate_transaction_type( $txn_type ) {

			if ( ! in_array( strtolower( $txn_type ), $this->transaction_types, true ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Check payment amount from IPN matches the order.
		 *
		 * @param   WC_Order  $order   Order.
		 * @param   float     $amount  Amount.
		 */
		protected function validate_amount( $order, $amount ) {
			if ( number_format( $order->get_total(), 2, '.', '' ) != number_format( $amount, 2, '.', '' ) ) { //phpcs:ignore
				// translators: 1. amount received.
				yith_paypal_ec()->ec->log_add_message( sprintf( __( 'Payment error: Amounts do not match (gross %s)', 'yith-paypal-express-checkout-for-woocommerce' ), $amount ) );

				// Put this order on-hold for manual checking.
				// translators: 1. amount received.
				$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayPal amounts do not match (gross %s).', 'yith-paypal-express-checkout-for-woocommerce' ), $amount ) );
				exit;
			}
		}

		/**
		 * Check currency from IPN matches the order.
		 *
		 * @param   WC_Order  $order     Order object.
		 * @param   string    $currency  Currency code.
		 */
		protected function validate_currency( $order, $currency ) {
			if ( $order->get_currency() !== $currency ) {
				// translators: 1. currency of order 2. currency received.
				yith_paypal_ec()->ec->log_add_message( sprintf( __( 'Payment error: Currencies do not match (sent %1$s | returned %2$s )', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_currency(), $currency ) );

				/* translators: %s: currency code. */
				$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayPal currencies do not match (code %s).', 'yith-paypal-express-checkout-for-woocommerce' ), $currency ) );
				exit;
			}
		}

		/**
		 * Save important data from the IPN to the order.
		 *
		 * @param   WC_Order  $order   Order object.
		 * @param   array     $posted  Posted data.
		 */
		protected function save_paypal_meta_data( $order, $posted ) {
			if ( ! empty( $posted['payment_type'] ) ) {
				$order->update_meta_data( 'Payment type', wc_clean( $posted['payment_type'] ) );
			}
			if ( ! empty( $posted['txn_id'] ) ) {
				$order->update_meta_data( '_transaction_id', wc_clean( $posted['txn_id'] ) );
			}
			if ( ! empty( $posted['payment_status'] ) ) {
				$order->update_meta_data( '_paypal_status', wc_clean( $posted['payment_status'] ) );
			}
		}

	}

}
