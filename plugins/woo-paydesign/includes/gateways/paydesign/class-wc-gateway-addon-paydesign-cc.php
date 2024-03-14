<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * metaps PAYMENT Gateway.
 *
 * Provides a metaps PAYMENT Credit Card Payment Gateway for subscriptions..
 *
 * @class 		WC_Addons_Gateway_PAYDESIGN_CC
 * @extends		WC_Gateway_PAYDESIGN_CC
 * @version		1.1.24
 * @package		WooCommerce/Classes/Payment
 * @author		Artisan Workshop
 */
class WC_Gateway_PAYDESIGN_CC_Addons extends WC_Gateway_PAYDESIGN_CC {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		if ( class_exists( 'WC_Subscriptions_Order' ) ) {
			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );
//			add_action( 'woocommerce_subscription_failing_payment_method_updated_' . $this->id, array( $this, 'update_failing_payment_method' ), 10, 2 );

//			add_action( 'wcs_resubscribe_order_created', array( $this, 'delete_resubscribe_meta' ), 10 );

			// Allow store managers to manually set metaps PAYMENT as the payment method on a subscription
//			add_filter( 'woocommerce_subscription_payment_meta', array( $this, 'add_subscription_payment_meta' ), 10, 2 );
//			add_filter( 'woocommerce_subscription_validate_payment_meta', array( $this, 'validate_subscription_payment_meta' ), 10, 2 );
		}

//		if ( class_exists( 'WC_Pre_Orders_Order' ) ) {
//			add_action( 'wc_pre_orders_process_pre_order_completion_payment_' . $this->id, array( $this, 'process_pre_order_release_payment' ) );
//		}

	}
	/**
	 * Check if order contains subscriptions.
	 *
	 * @param  int $order_id
	 * @return bool
	 */
	protected function order_contains_subscription( $order_id ) {
		return function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) );
	}

	/**
	 * Check if order contains pre-orders.
	 *
	 * @param  int $order_id
	 * @return bool
	 */
//	protected function order_contains_pre_order( $order_id ) {
//		return class_exists( 'WC_Pre_Orders_Order' ) && WC_Pre_Orders_Order::order_contains_pre_order( $order_id );
//	}

	/**
	 * Is $order_id a subscription?
	 * @param  int  $order_id
	 * @return boolean
	 */
	protected function is_subscription( $order_id ) {
		return ( function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) ) );
	}

	/**
	 * Process the subscription.
	 *
	 * @param  WC_Order $order
	 * @param  string   $subscription
	 * @return 
	 */
	protected function process_subscription( $order , $subscription = false) {
		$payment_response = $this->process_subscription_payment( $order, $order->get_total() );
		return;
	}

	/**
	 * Process the payment.
	 *
	 * @param  int $order_id
	 * @return array
	 */
	public function process_payment( $order_id , $subscription = false) {
		// Processing subscription
		if ( $this->is_subscription( $order_id ) ) {
			// Regular payment with force customer enabled
			return parent::process_payment( $order_id, true );
		} else {
			return parent::process_payment( $order_id, false );
		}
	}
	/**
	 * process_subscription_payment function.
	 *
	 * @param WC_order $order
	 * @param int $amount (default: 0)
	 * @uses  Paydesign_subscriptions_payment
	 * @return bool|WP_Error
	 */
	public function process_subscription_payment( $order = '', $amount = 0 ) {
		if ( 0 == $amount ) {
			// Payment complete
			$order->payment_complete();

			return true;
		}
		include_once( 'includes/class-wc-gateway-paydesign-request.php' );
		$paydesign_request = new WC_Gateway_PAYDESIGN_Request();

		$prefix_order = get_option( 'wc_paydesign_prefix_order' );
		$paydesign = new WC_Gateway_PAYDESIGN_CC();

        $order_id = $order->get_id();

		//Setting $send_data
		$setting_data = array();
		$setting_data['ip_user_id'] = $prefix_order.$order->get_user_id();
		$setting_data['ip'] = $paydesign->ip_code;
		$setting_data['pass'] = $paydesign->pass_code;
		$setting_data['lang'] = '0';// Use Language 0 = Japanese, 1 = English
		$setting_data['sid'] = $prefix_order.$order_id;
		$setting_data['paymode'] = 10;
		if($paydesign->paymentaction == 'sale'){
			$setting_data['kakutei'] = '1';//capture = 1			
		}else{
			$setting_data['kakutei'] = '0';//auth = 0
		}
		$connect_url = PAYDESIGN_CC_SALES_USER_URL;
		$response = $paydesign_request->paydesign_post_request( $order, $connect_url, $setting_data );
		if( isset( $response[0] ) and substr( $response[0], 0, 2 ) == 'OK' ){
            // Payment complete
            if( $order->get_status() != 'pending') {
                $order->payment_complete();
            }
		}else{
			$order->add_order_note(__('Payment error:', 'woo-paydesign') . mb_convert_encoding($response[2], "UTF-8", "sjis"));
			$order->update_status( 'cancelled', __( 'This order is cancelled, because of Payment error.'.mb_convert_encoding($response[2], "UTF-8", "sjis"), 'woo-paydesign' ) );
		}
		return true;
	}
	/**
	 * scheduled_subscription_payment function.
	 *
	 * @param float $amount_to_charge The amount to charge.
	 * @param WC_Order $renewal_order A WC_Order object created to record the renewal payment.
	 */
	public function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {
		$result = $this->process_subscription_payment( $renewal_order, $amount_to_charge );

		if ( is_wp_error( $result ) ) {
			$renewal_order->update_status( 'failed', sprintf( __( 'metaps Payment Transaction Failed (%s)', 'woocommerce' ), $result->get_error_message() ) );
		}
	}
}
