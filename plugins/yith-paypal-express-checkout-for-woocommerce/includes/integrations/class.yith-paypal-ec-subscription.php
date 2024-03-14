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

if ( ! class_exists( 'YITH_PayPal_EC_Subscription' ) ) {
	/**
	 * Class YITH_PayPal_EC_Subscription
	 */
	class YITH_PayPal_EC_Subscription {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_PayPal_EC_Subscription
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_PayPal_EC_Subscription
		 * @since 1.0.0
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
		 * @since  1.0.0
		 */
		public function __construct() {

			if ( yith_paypal_ec()->ec_is_enabled() && 'yes' === yith_paypal_ec()->ec->reference_transaction ) {
				remove_filter( 'woocommerce_add_to_cart_validation', array( YITH_WC_Subscription(), 'cart_item_validate' ), 10 );
			}

			add_filter( 'yith_paypal_ec_setting_options', array( $this, 'add_options' ) );

			add_filter( 'yith_paypal_ec_needs_billing_agreements', array( $this, 'needs_billing_agreement' ), 10, 2 );
			add_action( 'yith_paypal_ec_process_order_payment_with_billing_agreement', array( $this, 'process_order' ), 10, 3 );

			add_filter( 'ywsbs_max_failed_attemps_list', array( $this, 'add_failed_attempts' ) );
			add_filter( 'ywsbs_get_num_of_days_between_attemps', array( $this, 'add_num_of_days_between_attempts' ) );
			add_filter( 'ywsbs_from_list', array( $this, 'add_from_list' ) );

			if ( version_compare( YITH_YWSBS_VERSION, '1.4.6', '<' ) ) {
				add_action( 'wp_loaded', array( $this, 'set_cron' ), 30 );
				add_action( 'yith_paypal_ec_payment_renew_orders', array( $this, 'ec_payment_renew_orders' ) );
			}

			add_action( 'ywsbs_renew_subscription', array( $this, 'add_meta_paypal_ec_to_renew_order' ), 10, 2 );
		}

		/**
		 * Add this gateway in the list of maximum number of attempts to do.
		 *
		 * @param array $list List of failed attempts.
		 *
		 * @return mixed
		 */
		public function add_failed_attempts( $list ) {
			$list[ YITH_PayPal_EC::$gateway_id ] = 3;

			return $list;
		}

		/**
		 * Add this gateway in the list of maximum number of attempts to do.
		 *
		 * @param array $list List of days between attempts.
		 *
		 * @return mixed
		 */
		public function add_num_of_days_between_attempts( $list ) {
			$list[ YITH_PayPal_EC::$gateway_id ] = 5;

			return $list;
		}

		/**
		 * Check if there are subscription products on cart.
		 *
		 * @param bool     $result Result.
		 * @param WC_Order $order Oder.
		 * @return bool|string
		 * @since  1.0.0
		 */
		public function needs_billing_agreement( $result, $order ) {
			if ( is_null( $order ) ) {
				return is_callable( 'YWSBS_Subscription_Cart::cart_has_subscriptions' ) ? YWSBS_Subscription_Cart::cart_has_subscriptions() : YITH_WC_Subscription()->cart_has_subscriptions();
			} else {
				return ywsbs_is_an_order_with_subscription( $order );
			}
		}


		/**
		 * Process the order payment
		 *
		 * @param WC_Order                        $order Order.
		 * @param YITH_PayPal_EC_Response_Payment $payment_response Response.
		 * @param bool                            $manually Manually payment or not.
		 */
		public function process_order( $order, $payment_response, $manually = false ) {

			$helper = yith_paypal_ec()->ec;
			$helper->clear_session();
			$redirect_url   = '';
			$payment_status = $payment_response->get_response_payment_parameter( 'PAYMENTSTATUS' );
			$transaction_id = $payment_response->get_response_payment_parameter( 'TRANSACTIONID' );
			$payment_type   = $payment_response->get_response_payment_parameter( 'PAYMENTTYPE' );
			$payment_fee    = $payment_response->get_response_payment_parameter( 'FEEAMT' );
			$is_a_renew     = $order->get_meta( 'is_a_renew' );
			$order_id       = $order->get_id();
			$subscriptions  = $order->get_meta( 'subscriptions' );

			if ( ! empty( $payment_fee ) ) {
				$order->update_meta_data( '_yith_ppec_fee', $payment_fee );
			}

			if ( empty( $subscriptions ) ) {
				$helper->log_add_message( __( 'Payment ignored, this order doesn\'t have any subscription product: ', 'yith-paypal-express-checkout-for-woocommerce' ) . $order_id );
			}

			$order->update_meta_data( 'Payment type', $payment_type );
			$order->set_transaction_id( $transaction_id );


			if ( 'pending' === strtolower( $payment_status ) ) {
				$message = $payment_response->get_response_payment_parameter( 'PENDINGREASON' );
				// translators: placeholder message from PayPal.
				$order_note   = sprintf( __( 'YITH PayPal EC: %s', 'yith-paypal-express-checkout-for-woocommerce' ), $message );
				$order_status = apply_filters( 'ywsbs_paypal_ec_pending_payment_order_status', 'on-hold', $order, $payment_response );

				if ( 'authorization' === $message ) {
					// translators: placeholder order id.
					$helper->log_add_message( sprintf( __( 'Payment authorized for order %d', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id() ) );
					$order_note = __( 'YITH PayPal EC - Payment authorized. Change the order status to processing or complete to capture funds.', 'yith-paypal-express-checkout-for-woocommerce' );
				} else {
					// translators: placeholder order id and message.
					$helper->log_add_message( sprintf( __( 'Pending payment for order %1$d - %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id(), $message ) );
					// translators: placeholder message from PayPal.
					$order_note = sprintf( __( 'YITH PayPal EC: %s', 'yith-paypal-express-checkout-for-woocommerce' ), $message );
				}

				// mark order as held.
				if ( ! $order->has_status( $order_status ) ) {
					$order->update_status( $order_status, $order_note );
				} else {
					$order->add_order_note( $order_note );
				}

				if ( 'echeck' === $payment_type ) {
					// translators: placeholder 1. Date of payment. 2. order id.
					$helper->log_add_message( sprintf( __( 'Echeck expected on %1$s for order %2$d', 'yith-paypal-express-checkout-for-woocommerce' ), date_i18n( wc_date_format(), ywsbs_date_to_time( $payment_response->get_response_payment_parameter( 'EXPECTEDECHECKCLEARDATE' ) ) ), $order->get_id() ) );
					// translators: placeholder 1. Date of payment.
					$status_note = sprintf( __( 'Echeck expected on %s', 'yith-paypal-express-checkout-for-woocommerce' ), date_i18n( wc_date_format(), ywsbs_date_to_time( $payment_response->get_response_payment_parameter( 'EXPECTEDECHECKCLEARDATE' ) ) ) );
				}

				$redirect_url = add_query_arg( 'utm_nooverride', '1', $order->get_checkout_order_received_url() );

			} elseif ( ! $payment_response->transaction_approved() ) {

				$status_note = '';
				$new_status  = '';

				$proceed = ! $manually;
				$error   = $payment_response->get_errors( true );

				if ( is_array( $error ) ) {
					$status_note = $error['message'];

					switch ( $error['code'] ) {
						case '10201':
							$new_status = 'cancelled';
							$proceed    = false;

							foreach ( $subscriptions as $subscription_id ) {
								$subscription = ywsbs_get_subscription( $subscription_id );
								$subscription->update_status( 'cancelled', 'gateway' );
							}
							break;
						case '10412':
							$proceed = false;
							break;
						default:
					}
				}

				// translators: placeholder 1. order id. 2. status note.
				$message = sprintf( __( 'Payment failed for order %1$d: %2$s', 'yith-paypal-express-checkout-for-woocommerce' ), $order->get_id(), $status_note );
				$helper->log_add_message( $message );

				! empty( $new_status ) && $order->update_status( $new_status, $message );
				$manually && wc_add_notice( $message, 'error' );
				if ( 'yes' === $is_a_renew && $proceed ) {
					function_exists( 'ywsbs_register_failed_payment' ) && ywsbs_register_failed_payment( $order, '' );
				}
			} elseif ( $payment_response->transaction_approved() ) {
				// translators: placeholder 1. transaction id.
				$order->add_order_note( sprintf( __( 'YITH PayPal EC payment (ID: %s)', 'yith-paypal-express-checkout-for-woocommerce' ), $transaction_id ) );

				foreach ( $subscriptions as $subscription_id ) {
					// translators: placeholder 1. subscription id. 2. order id.
					$notice = sprintf( __( 'YITH PayPal EC payment done with success. Subscription %1$s. Order %2$s. (Transaction ID: %3$s)', 'yith-paypal-express-checkout-for-woocommerce' ), $subscription_id, $order_id, $transaction_id );
					$helper->log_add_message( $notice );
					$manually && wc_add_notice( $notice, 'success' );
				}

				do_action( 'ywsbs_process_order_payment_before_complete', $order, $payment_response );

				$order->payment_complete( $transaction_id );

				$redirect_url = add_query_arg( 'utm_nooverride', '1', $order->get_checkout_order_received_url() );

			} else {
				$redirect_url = wc_get_cart_url();
			}

			$order->save();

			if ( 'yes' !== $is_a_renew && ! empty( $redirect_url ) ) {
				wp_safe_redirect( esc_url_raw( $redirect_url ) );
				die;
			}

		}

		/**
		 * Add to renew order post meta to initialize some fields to the query
		 * These fields will be added only if the subscription is payed with Express Checkout
		 *
		 * @param int $order_id Order id.
		 * @param int $subscription_id Subscription id.
		 */
		public function add_meta_paypal_ec_to_renew_order( $order_id, $subscription_id ) {

			$billing_agreement_id = get_post_meta( $subscription_id, 'billing_agreement_id' );

			if ( ! empty( $billing_agreement_id ) ) {
				$order = wc_get_order( $order_id );
				$order->update_meta_data( 'billing_agreement_id', $billing_agreement_id );
				$order->save();
			}
		}

		/**
		 * Function called by cron
		 */
		public function ec_payment_renew_orders() {

			global $wpdb;

			$status                  = 'wc-' . YWSBS_Subscription_Order()->get_renew_order_status();
			$current_time            = current_time( 'timestamp' ); //phpcs:ignore
			$from                    = $current_time - DAY_IN_SECONDS;
			$max_failed_attempt_list = ywsbs_get_max_failed_attemps_list();

			$query = $wpdb->prepare( //phpcs:ignore
				"SELECT ywsbs_p.ID FROM {$wpdb->prefix}posts as ywsbs_p
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm ON ( ywsbs_p.ID = ywsbs_pm.post_id )
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm2 ON ( ywsbs_p.ID = ywsbs_pm2.post_id )
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm3 ON ( ywsbs_p.ID = ywsbs_pm3.post_id )
                 WHERE ( ywsbs_pm.meta_key='billing_agreement_id' AND  ywsbs_pm.meta_value != '' )
                 AND ( ywsbs_pm2.meta_key='is_a_renew' AND  ywsbs_pm2.meta_value = 'yes' )
                 AND ywsbs_p.post_type = %s
                 AND ywsbs_p.post_status = %s
                 AND ywsbs_p.post_date_gmt < FROM_UNIXTIME($from)
                 AND ( ywsbs_pm3.meta_key='failed_attemps' AND ywsbs_pm3.meta_value = 0 ) 
                 GROUP BY ywsbs_p.ID ORDER BY ywsbs_p.ID DESC
                ",
				'shop_order',
				$status
			);

			$renew_orders_for_first_time = $wpdb->get_results( $query ); //phpcs:ignore

			$query = $wpdb->prepare( //phpcs:ignore
				"SELECT ywsbs_p.ID FROM {$wpdb->prefix}posts as ywsbs_p
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm ON ( ywsbs_p.ID = ywsbs_pm.post_id )
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm2 ON ( ywsbs_p.ID = ywsbs_pm2.post_id )
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm3 ON ( ywsbs_p.ID = ywsbs_pm3.post_id )
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm4 ON ( ywsbs_p.ID = ywsbs_pm4.post_id )
                 WHERE ( ywsbs_pm.meta_key='billing_agreement_id' AND  ywsbs_pm.meta_value != '' )
                 AND ( ywsbs_pm2.meta_key='is_a_renew' AND  ywsbs_pm2.meta_value = 'yes' )
                 AND ywsbs_p.post_type = %s
                 AND ywsbs_p.post_status = %s
                 AND ( ywsbs_pm3.meta_key='failed_attemps' AND ywsbs_pm3.meta_value > 0 AND ywsbs_pm3.meta_value < %d )
                 AND ( ywsbs_pm4.meta_key='next_payment_attempt' AND ywsbs_pm4.meta_value <= %d )
                 GROUP BY ywsbs_p.ID ORDER BY ywsbs_p.ID DESC
                ",
				'shop_order',
				$status,
				$max_failed_attempt_list[ YITH_PayPal_EC::$gateway_id ],
				$current_time
			);

			$renew_failed_orders = $wpdb->get_results( $query ); //phpcs:ignore

			$renew_orders = array_merge( $renew_orders_for_first_time, $renew_failed_orders );

			if ( $renew_orders ) {
				WC_Payment_Gateways::instance();
				foreach ( $renew_orders as $renew_order ) {
					$current_order = wc_get_order( $renew_order->ID );
					do_action( 'yith_paypal_ec_request_a_payment', $current_order );
				}
			}
		}

		/**
		 * Add this gateway in the list "from" to understand from where the
		 * update status is requested.
		 *
		 * @param array $list Gateways.
		 *
		 * @return mixed
		 */
		public function add_from_list( $list ) {
			$list[ YITH_PayPal_EC::$gateway_id ] = YITH_PayPal_EC()->ec->title;

			return $list;
		}


		/**
		 * Set cron to schedule the renew order.
		 */
		public function set_cron() {
			if ( ! wp_next_scheduled( 'yith_paypal_ec_payment_renew_orders' ) ) {
				wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'yith_paypal_ec_payment_renew_orders' ); //phpcs:ignore
			}
		}

		/**
		 * Add option
		 *
		 * @param array $settings Settings.
		 * @return mixed
		 */
		public function add_options( $settings ) {
			$settings['reference_transaction'] = array(
				'title'       => __( 'Reference transactions', 'yith-paypal-express-checkout-for-woocommerce' ),
				'type'        => 'checkbox',
				'label'       => __( 'Are the reference transactions enabled for your PayPal Account?', 'yith-paypal-express-checkout-for-woocommerce' ),
				'description' => __( 'Disable this option if your PayPal account doesn\'t support the Reference transactions. They are necessary to pay recurring orders of YITH WooCommerce Subscriptions products with Express Checkout. You should enable the standard WooCommerce PayPal Gateway as an alternative.', 'yith-paypal-express-checkout-for-woocommerce' ),
				'default'     => 'yes',
			);
			return $settings;
		}



	}

	/**
	 * Unique access to instance of YITH_PayPal_EC_Subscription class
	 *
	 * @return \YITH_PayPal_EC_Subscription
	 */
	function YITH_PayPal_EC_Subscription() { //phpcs:ignore
		return YITH_PayPal_EC_Subscription::get_instance();
	}
}
