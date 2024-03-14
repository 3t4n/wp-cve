<?php

class WC_FreePay_Order_Utils {
	/**
	 * @param WC_Order $order
	 */
	public static function get_authorization_step($order) {
		return $order->get_meta( 'FREEPAY_AUTH_STEP' );
	}

	/**
	 * @param WC_Order $order
	 * @param int $step_idx
	 */
	public static function set_authorization_step( $order, $step_idx ) {
		$order->update_meta_data( 'FREEPAY_AUTH_STEP', $step_idx );
		$order->save_meta_data();
	}

	/**
	 * @param WC_Order $order
	 */
    public static function logDeclinePaymentData($order) {
		$order = woo_freepay_get_order($order);
		if($order) {
			if(!empty($_GET['acquirer']) && !empty($_GET['gatewayreason'])) {
				// The text for the note
				$note = __("Order payment declined from: " . $_GET['acquirer'] . '. Reason code: ' . $_GET['gatewayreason']);

				// Add the note
				$order->add_order_note( $note );
			}
		}
	}

	/**
	 * get_transaction_params function.
	 * 
	 * @param WC_Order $order
	 *
	 * Returns the necessary basic params to send to FreePay when creating a payment
	 *
	 * @access public
	 * @return array
	 */
	public static function get_transaction_params($order) {
		$params = [
			'ShippingAddress' 	=> self::get_transaction_shipping_address_params($order),
			'BillingAddress'  	=> self::get_transaction_invoice_address_params($order),
		];

		return $params;
	}

	/**
	 * contains_wcs_subscription function
	 * 
	 * @param WC_Order $order
	 *
	 * Checks if an order contains a subscription product
	 *
	 * @access public
	 * @return boolean
	 */
	public static function contains_wcs_subscription($order) {
		$has_subscription = false;

		if ( WC_FreePay_Subscription_Utils::wcs_plugin_is_active() ) {
			$has_subscription = wcs_order_contains_subscription( $order );
		}

		return $has_subscription;
	}

	/**
	 * @param WC_Order $order
	 */
	public static function contains_wps_sfw_subscription($order) {
		$has_subscription = false;

		if( WC_FreePay_Subscription_Utils::sfw_plugin_is_active() ) {
			$subscriptionId = $order->get_meta( 'wps_subscription_id', true );
			$has_subscription = !empty($subscriptionId);
		}

		return $has_subscription;
	}

	/**
	 * is_request_to_change_payment
	 *
	 * Check if the current request is trying to change the payment gateway
	 *
	 * @return bool
	 */
	public static function is_request_to_change_payment() {
		$is_request_to_change_payment = false;

		if ( WC_FreePay_Subscription_Utils::wcs_plugin_is_active() ) {
			$is_request_to_change_payment = WC_Subscriptions_Change_Payment_Gateway::$is_request_to_change_payment;

			if ( ! $is_request_to_change_payment && ! empty( $_GET['freepay_change_payment_method'] ) ) {
				$is_request_to_change_payment = true;
			}
		}

		return apply_filters( 'woo_freepay_is_request_to_change_payment', $is_request_to_change_payment );
	}

	/**
	 * get_order_number_for_api function.
	 *
	 * Prefix the order number if necessary. This is done
	 * because FreePay requires the order number to contain at least
	 * 4 chars.
	 *
	 * @access public
	 *
	 * @param WC_Order $order
	 * @param bool $recurring
	 *
	 * @return string
	 */
	public static function get_order_number_for_api( $order, $recurring = false, $is_update_recurring = false ) {
		$minimum_length = 4;

		// On initial subscription authorizations
		if( WC_FreePay_Subscription_Utils::is_wcs_subscription( $order ) && ! self::order_contains_switch($order) && $is_update_recurring && ! $recurring) {
			$order_number = $order->get_id();

			// If an initial payment on a subscription failed (recurring payment), create a new subscription with appended ID.
			if ( WC_FreePay_Payment_Utils::get_payment_method_change_count($order) > 0 ) {
				$order_number .= sprintf( '-%d', WC_FreePay_Payment_Utils::get_payment_method_change_count($order) );
			}
		}
		// When changing payment method on subscriptions
		else if ( WC_FreePay_Subscription_Utils::is_wcs_subscription( $order ) ) {
			$order_number = $order->get_id();
		} 
		else if ( ! self::order_contains_switch($order) && self::contains_wcs_subscription($order) && ! $recurring ) {
			// Find all subscriptions
			$subscriptions = WC_FreePay_Subscription_Utils::get_subscriptions_for_order( $order );
			// Get the last one and base the transaction on it.
			$subscription = end( $subscriptions );
			// Fetch the ID of the subscription, not the parent order.
			$order_number = $subscription->get_id();

			// If an initial payment on a subscription failed (recurring payment), create a new subscription with appended ID.
			if ( WC_FreePay_Payment_Utils::get_failed_freepay_payment_count($order) > 0 ) {
				$order_number .= sprintf( '-%d', WC_FreePay_Payment_Utils::get_failed_freepay_payment_count($order) );
			}
		} // On recurring / payment attempts
		else {
			// Normal orders - get the order number
			$order_number = self::get_clean_order_number($order);
		}

		if ( self::is_request_to_change_payment() ) {
			$order_number .= sprintf( '-%d', WC_FreePay_Payment_Utils::get_payment_method_change_count($order) );
		}

		$order_number_length = strlen( $order_number );

		if ( $order_number_length < $minimum_length ) {
			preg_match( '/\d+/', $order_number, $digits );

			if ( ! empty( $digits ) ) {
				$missing_digits = $minimum_length - $order_number_length;
				$order_number   = str_replace( $digits[0], str_pad( $digits[0], strlen( $digits[0] ) + $missing_digits, 0, STR_PAD_LEFT ), $order_number );
			}
		}

		return $order_number;
	}

	public static function get_order_key_for_api($order) {
		$order_number = $order->get_order_key();

		if ( WC_FreePay_Subscription_Utils::is_wcs_subscription( $order ) ) {
			$subscription = woo_freepay_get_subscription( $order->get_id() );
			$order_number = $subscription->get_order_key();
		}
		else if ( ! self::order_contains_switch($order) && self::contains_wcs_subscription($order) ) {
			// Find all subscriptions
			$subscriptions = WC_FreePay_Subscription_Utils::get_subscriptions_for_order( $order->get_id() );
			// Get the last one and base the transaction on it.
			$subscription = end( $subscriptions );
			// Fetch the ID of the subscription, not the parent order.
			$order_number = $subscription->get_order_key();
		}

		return $order_number;
	}

	/**
	 * @param WC_Order|int $order The WC_Order object or ID of a WC_Order order.
	 *
	 * @return bool
	 */
	public static function order_contains_switch($order) {
		if ( function_exists( 'wcs_order_contains_switch' ) ) {
			return wcs_order_contains_switch( $order );
		}

		return false;
	}

	/**
	 * get_clean_order_number function
	 *
	 * @param WC_Order $order
	 * 
	 * Returns the order number without leading #
	 *
	 * @access public
	 * @return integer
	 */
	public static function get_clean_order_number($order) {
		return str_replace( '#', '', $order->get_order_number() );
	}

	/**
	 * @param WC_Order $order
	 */
	public static function get_transaction_shipping_address_params($order) {
		$org_address = $order->get_shipping_address_1();

		if(empty($org_address)) {
			return null;
		}

		$address1 = "";
		$address2 = "";

		if(strlen($org_address) > 50) {
			$address1 = substr($org_address, 0, 50);
			$address2 = substr($org_address, 49, 50);
		}
		else {
			$address1 = $org_address;
		}

		$countryCode = $order->get_shipping_country();
		if(empty($countryCode)) {
			$countryCode = $order->get_billing_country();
		}

		$params = [
			'AddressLine1'		=> $address1,
			'AddressLine2'		=> $address2,
			'City'				=> $order->get_shipping_city(),
			'PostCode'			=> $order->get_shipping_postcode(),
			'Country'			=> WC_FreePay_Countries::getNumFromAlpha2( $countryCode ),
		];

		return apply_filters( 'woo_freepay_transaction_params_shipping', $params, $order );
	}

	/**
	 * @param WC_Order $order
	 */
	public static function get_transaction_invoice_address_params($order) {
		$org_address = $order->get_billing_address_1();
		$address1 = "";
		$address2 = "";

		if(strlen($org_address) > 50) {
			$address1 = substr($org_address, 0, 50);
			$address2 = substr($org_address, 49, 50);
		}
		else {
			$address1 = $org_address;
		}

		$params = [
			'AddressLine1'		=> $address1,
			'AddressLine2'		=> $address2,
			'City'				=> $order->get_billing_city(),
			'PostCode'			=> $order->get_billing_postcode(),
			'Country'			=> WC_FreePay_Countries::getNumFromAlpha2( $order->get_billing_country() ),
		];

		return apply_filters( 'woo_freepay_transaction_params_invoice', $params, $order );
	}

	/**
	 * get_transaction_link_params function.
	 * 
	 * @param WC_Order $order
	 *
	 * Returns the necessary basic params to send to FreePay when creating a payment link
	 *
	 * @access public
	 * @return array
	 */
	public static function get_transaction_link_params($order) {
		global $wp_version;

		//in very special case of renew order of a subscription with payment link for fixing failed subscriptions. Pay for renew and update subscription identifier
		if($order->get_status() === 'failed') {
			$parentSubscription = WC_FreePay_Subscription_Utils::get_subscriptions_for_renewal_order($order, true);
		}

		$is_subscription = self::contains_wcs_subscription($order) ||
							self::contains_wps_sfw_subscription($order) ||
							self::is_request_to_change_payment() ||
							!empty($parentSubscription) ||
							WC_FreePay_Subscription_Utils::is_wcs_subscription( $order );
		$amount          = $order->get_total();

		if(self::is_request_to_change_payment()) {
			$amount = 0;
		}

		$params			 = [
			'OrderNumber'			=> self::get_order_number_for_api(!empty($parentSubscription) ? $parentSubscription : $order, false, !empty($parentSubscription)),
			'CustomerAcceptUrl'		=> self::get_continue_url($order),
			'CustomerDeclineUrl'	=> self::get_cancellation_url($order),
			'Amount'				=> WC_FreePay_Helper::price_multiply( $amount ),
			'SaveCard'				=> $is_subscription,
			'Client'				=> array(
				'CMS'				=> array(
					'Name'			=> "Wordpress",
					'Version'		=> $wp_version
				),
				'Shop'				=> array(
					'Name'			=> "WooCommerce",
					'Version'		=> Automattic\Jetpack\Constants::get_constant( 'WC_VERSION' )
				),
				'Plugin'			=> array(
					'Name'			=> "Freepay",
					'Version'		=> WCFP_VERSION
				),
				'API'   			=> array(
					'Name'			=> "Freepay",
					'Version'		=> '2.0'
				),
			),
		];

		if ( $is_subscription ) {
			$maxExpiration = null;
			$minFrequency = null;

			if( WC_FreePay_Subscription_Utils::wcs_plugin_is_active() && (self::contains_wcs_subscription($order) || !empty($parentSubscription) )) {
				if(self::is_request_to_change_payment()) {
					$subscriptions = array(wcs_get_subscription( $order->get_id() ));
				}
				else if(!empty($parentSubscription)) {
					$subscriptions = array($parentSubscription);
				}
				else {
					$subscriptions = WC_FreePay_Subscription_Utils::get_subscriptions_for_order( $order->get_id() );
				}
	
				foreach($subscriptions as $sub) {
					$tmp_expiration = strtotime($sub->get_date('end'));
	
					$nextDate = $sub->get_date('next_payment');
					$datediff = strtotime($nextDate) - time();
					$tmp_minFrequency = round($datediff / (60 * 60 * 24));
					$tmp_minFrequencyReal = 99999;
	
					switch($sub->get_billing_period()) {
						case 'day':
							$tmp_minFrequencyReal = $sub->get_billing_interval();
							break;
						case 'week':
							$tmp_minFrequencyReal = $sub->get_billing_interval() * 7;
							break;
						case 'month':
							$tmp_minFrequencyReal = $sub->get_billing_interval() * 28;
							break;
						case 'year':
							$tmp_minFrequencyReal = $sub->get_billing_interval() * 365;
							break;
					}
	
					if($tmp_minFrequency <= 0 || $tmp_minFrequency > $tmp_minFrequencyReal) {
						$tmp_minFrequency = $tmp_minFrequencyReal;
					}
	
					if($maxExpiration == null || $maxExpiration < $tmp_expiration) {
						$maxExpiration = $tmp_expiration;
					}
	
					if($minFrequency == null || $minFrequency > $tmp_minFrequency) {
						$minFrequency = $tmp_minFrequency;
					}
				}
			}
			else if( WC_FreePay_Subscription_Utils::sfw_plugin_is_active() && self::contains_wps_sfw_subscription($order) ) {
				$subscription_id = $order->get_meta( 'wps_subscription_id', true );
				$tmp_order = wc_get_order($subscription_id);
				
				$trial_number = $tmp_order->get_meta( 'wps_sfw_subscription_free_trial_number', true );
				$trial_interval = $tmp_order->get_meta( 'wps_sfw_subscription_free_trial_interval', true );

				$sub_number = $tmp_order->get_meta( 'wps_sfw_subscription_number', true );
				$sub_interval = $tmp_order->get_meta( 'wps_sfw_subscription_interval', true );

				$end_number = $tmp_order->get_meta( 'wps_sfw_subscription_expiry_number', true );
				$end_interval = $tmp_order->get_meta( 'wps_sfw_subscription_expiry_interval', true );

				$dateTimeCurrent = new DateTime(date('Y-m-d', current_time( 'timestamp' )));

				if($end_number) {
					$maxExpiration = self::wps_sfw_get_timestamp( current_time( 'timestamp' ), $end_interval, intval( $end_number ) );
				}

				if($trial_number) {
					$trialTimestamp = self::wps_sfw_get_timestamp( current_time( 'timestamp' ), $trial_interval, intval( $trial_number ) );
					$dateTimeLater = new DateTime(date('Y-m-d', $trialTimestamp));
					$minFrequency = $dateTimeLater->diff($dateTimeCurrent)->format("%a");
				}
				else {
					$subTimestamp = self::wps_sfw_get_timestamp( current_time( 'timestamp' ), $sub_interval, intval( $sub_number ) );
					$dateTimeLater = new DateTime(date('Y-m-d', $subTimestamp));
					$minFrequency = $dateTimeLater->diff($dateTimeCurrent)->format("%a");
				}
			}

			if($maxExpiration == null || empty($maxExpiration) || $maxExpiration == 0) {
				$maxExpiration = strtotime('+3 year');
			}

			if($minFrequency == null || $minFrequency <= 0) {
				$minFrequency = 1;
			}

			$params['RecurringPayment'] = array(
				"Expiry" => date('Ymd', $maxExpiration),
            	"PaymentFrequencyDays" => $minFrequency
			);
		}

		/*$params['Options'] = array(
			"RequestSubscriptionWithAuthorization" => true
		);*/

		if(WC_FreePay_Helper::option_is_enabled( WC_FP_MAIN()->s( 'freepay_test_mode' ) ) == 1) {
			$params['Options']["TestMode"] = true;
		}

		return $params;
	}

	public static function wps_sfw_get_timestamp( $wps_curr_time, $interval, $number ) {

		switch ( $interval ) {
			case 'day':
				$wps_curr_time = strtotime( '+' . $number . ' days', $wps_curr_time );
				break;
			case 'week':
				$wps_curr_time = strtotime( '+' . $number * 7 . ' days', $wps_curr_time );
				break;
			case 'month':
				$wps_curr_time = strtotime( '+' . $number . ' month', $wps_curr_time );
				break;
			case 'year':
				$wps_curr_time = strtotime( '+' . $number . ' year', $wps_curr_time );
				break;
			default:
		}

		return $wps_curr_time;
	}

	/**
	 * get_continue_url function
	 * @param WC_Order $order
	 *
	 * Returns the order's continue callback url
	 *
	 * @access public
	 * @return string
	 */
	public static function get_continue_url($order) {
		if ( method_exists( $order, 'get_checkout_order_received_url' ) ) {
			return $order->get_checkout_order_received_url();
		}

		return add_query_arg( 'key', $order->order_key, add_query_arg( 'order', $order->get_id(), get_permalink( get_option( 'woocommerce_thanks_page_id' ) ) ) );
	}

	/**
	 * get_cancellation_url function
	 * 
	 * @param WC_Order $order
	 *
	 * Returns the order's cancellation callback url
	 *
	 * @access public
	 * @return string
	 */
	public static function get_cancellation_url($order) {
		if( !empty( WC_FP_MAIN()->s( 'freepay_decline_url' ) ) ) {
			return add_query_arg(
				array(
					'cancel_order' => 'true',
					'order'        => $order->get_order_key(),
					'order_id'     => $order->get_id(),
					'redirect'     => '',
					'_wpnonce'     => wp_create_nonce( 'woocommerce-cancel_order' ),
				),
				WC_FP_MAIN()->s( 'freepay_decline_url' )
			);
		}

		if ( method_exists( $order, 'get_cancel_order_url' ) ) {
			return str_replace( '&amp;', '&', $order->get_cancel_order_url() );
		}

		return add_query_arg( 'key', $order->get_order_key(), add_query_arg( [
			'order'                => $order->get_id(),
			'payment_cancellation' => 'yes',
		], get_permalink( get_option( 'woocommerce_cart_page_id' ) ) ) );
	}

	/**
	 * has_freepay_payment function
	 *
	 * @param WC_Order $order
	 * 
	 * Checks if the order is paid with the FreePay module.
	 *
	 * @return bool
	 * @since  4.5.0
	 * @access public
	 */
	public static function has_freepay_payment($order) {
		return in_array( $order->get_payment_method(), ['freepay'], true );
	}

	/**
	 * @param WC_Order $order
	 * @param string $context
	 *
	 * @return mixed|string
	 */
	public static function get_transaction_id( $order, $context = 'view' ) {
		$transaction_id = $order->get_meta( '_freepay_transaction_id' );
		if ( empty( $transaction_id ) ) {
			$transaction_id = $order->get_transaction_id( $context );
		}

		return $transaction_id;
	}
}