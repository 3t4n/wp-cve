<?php

/**
 * WC_FreePay_Order class
 */

class WC_FreePay_Order extends WC_Order {

	/** */
	const META_PAYMENT_METHOD_CHANGE_COUNT = '_freepay_payment_method_change_count';
	/** */
	const META_FAILED_PAYMENT_COUNT = '_freepay_failed_payment_count';

	public static function logDeclinePaymentData($orderId) {
		$order = wc_get_order( $orderId );

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
	 * get_payment_id function
	 *
	 * If the order has a payment ID, we will return it. If no ID is set we return false.
	 *
	 * @access public
	 * @return string
	 */
	public function get_payment_id() {
		return get_post_meta( $this->get_id(), 'FREEPAY_PAYMENT_ID', true );
	}

	/**
	 * get_payment_link function
	 *
	 * If the order has a payment link, we will return it. If no link is set we return false.
	 *
	 * @access public
	 * @return string
	 */
	public function get_payment_link() {
		return get_post_meta( $this->get_id(), 'FREEPAY_PAYMENT_LINK', true );
	}

	/**
	 * set_payment_link function
	 *
	 * Set the payment link on an order
	 *
	 * @access public
	 * @return void
	 */
	public function set_payment_link( $payment_link ) {
		update_post_meta( $this->get_id(), 'FREEPAY_PAYMENT_LINK', $payment_link );
	}

	/**
	 * get_payment_identifier function
	 *
	 * Set the payment identifier on an order
	 *
	 * @access public
	 * @return void
	 */
	public function get_payment_identifier() {
		return get_post_meta( $this->get_id(), 'FREEPAY_PAYMENT_IDENTIFIER', true );
	}

	/**
	 * set_payment_identifier function
	 *
	 * Set the payment identifier on an order
	 *
	 * @access public
	 * @return void
	 */
	public function set_payment_identifier( $payment_identifier ) {
		update_post_meta( $this->get_id(), 'FREEPAY_PAYMENT_IDENTIFIER', $payment_identifier );
	}

	public function get_authorization_step() {
		return get_post_meta( $this->get_id(), 'FREEPAY_AUTH_STEP', true );
	}

	public function set_authorization_step( $step_idx ) {
		update_post_meta( $this->get_id(), 'FREEPAY_AUTH_STEP', $step_idx );
	}

	/**
	 * delete_payment_link function
	 *
	 * Delete the payment link on an order
	 *
	 * @access public
	 * @return void
	 */
	public function delete_payment_link() {
		delete_post_meta( $this->get_id(), 'FREEPAY_PAYMENT_LINK' );
	}

	/**
	 * subscription_is_renewal_failure function.
	 *
	 * Checks if the order is currently in a failed renewal
	 *
	 * @access public
	 * @return boolean
	 */
	public function subscription_is_renewal_failure() {
		$renewal_failure = false;

		if ( WC_FreePay_Subscription::plugin_is_active() ) {
			$renewal_failure = ( WC_FreePay_Subscription::is_renewal( $this ) and $this->get_status() === 'failed' );
		}

		return $renewal_failure;
	}

	/**
	 * note function.
	 *
	 * Adds a custom order note
	 *
	 * @access public
	 * @return void
	 */
	public function note( $message ) {
		if ( isset( $message ) ) {
			$this->add_order_note( 'FreePay: ' . $message );
		}
	}

	/**
	 * get_transaction_params function.
	 *
	 * Returns the necessary basic params to send to FreePay when creating a payment
	 *
	 * @access public
	 * @return array
	 */
	public function get_transaction_params() {
		$params = [
			'ShippingAddress' 	=> $this->get_transaction_shipping_address_params(),
			'BillingAddress'  	=> $this->get_transaction_invoice_address_params(),
		];

		return $params;
	}

	/**
	 * contains_wcs_subscription function
	 *
	 * Checks if an order contains a subscription product
	 *
	 * @access public
	 * @return boolean
	 */
	public function contains_wcs_subscription() {
		$has_subscription = false;

		if ( WC_FreePay_Subscription::plugin_is_active() ) {
			$has_subscription = wcs_order_contains_subscription( $this );
		}

		return $has_subscription;
	}

	public function contains_wps_sfw_subscription() {
		$has_subscription = false;

		if( WC_FreePay_Subscription::sfw_plugin_is_active() ) {
			$subscriptionId = get_post_meta( $this->id, 'wps_subscription_id', true );
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
	public function is_request_to_change_payment() {
		$is_request_to_change_payment = false;

		if ( WC_FreePay_Subscription::plugin_is_active() ) {
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
	 * @param bool $recurring
	 *
	 * @return string
	 */
	public function get_order_number_for_api( $recurring = false ) {
		$minimum_length = 4;

		$order_id = $this->get_id();

		// When changing payment method on subscriptions
		if ( WC_FreePay_Subscription::is_wcs_subscription( $order_id ) ) {
			$order_number = $order_id;
		} // On initial subscription authorizations
		else if ( ! $this->order_contains_switch() && $this->contains_wcs_subscription() && ! $recurring ) {
			// Find all subscriptions
			$subscriptions = WC_FreePay_Subscription::get_subscriptions_for_order( $order_id );
			// Get the last one and base the transaction on it.
			$subscription = end( $subscriptions );
			// Fetch the ID of the subscription, not the parent order.
			$order_number = $subscription->get_id();

			// If an initial payment on a subscription failed (recurring payment), create a new subscription with appended ID.
			if ( $this->get_failed_freepay_payment_count() > 0 ) {
				$order_number .= sprintf( '-%d', $this->get_failed_freepay_payment_count() );
			}
		} // On recurring / payment attempts
		else {
			// Normal orders - get the order number
			$order_number = $this->get_clean_order_number();
		}

		if ( $this->is_request_to_change_payment() ) {
			$order_number .= sprintf( '-%d', $this->get_payment_method_change_count() );
		}

		$order_number_length = strlen( $order_number );

		if ( $order_number_length < $minimum_length ) {
			preg_match( '/\d+/', $order_number, $digits );

			if ( ! empty( $digits ) ) {
				$missing_digits = $minimum_length - $order_number_length;
				$order_number   = str_replace( $digits[0], str_pad( $digits[0], strlen( $digits[0] ) + $missing_digits, 0, STR_PAD_LEFT ), $order_number );
			}
		}

		return apply_filters( 'woo_freepay_order_number_for_api', $order_number, $this, $recurring );
	}

	public function get_order_key_for_api() {
		$order_id = $this->get_id();
		$order_number = $this->get_order_key();

		if ( ! $this->order_contains_switch() && $this->contains_wcs_subscription() ) {
			// Find all subscriptions
			$subscriptions = WC_FreePay_Subscription::get_subscriptions_for_order( $order_id );
			// Get the last one and base the transaction on it.
			$subscription = end( $subscriptions );
			// Fetch the ID of the subscription, not the parent order.
			$order_number = $subscription->get_order_key();

			// If an initial payment on a subscription failed (recurring payment), create a new subscription with appended ID.
			if ( $this->get_failed_freepay_payment_count() > 0 ) {
				$order_number .= sprintf( '-%d', $this->get_failed_freepay_payment_count() );
			}
		}

		return $order_number;
	}

	/**
	 * @param WC_Order|int $order The WC_Order object or ID of a WC_Order order.
	 *
	 * @return bool
	 */
	public function order_contains_switch() {
		if ( function_exists( 'wcs_order_contains_switch' ) ) {
			return wcs_order_contains_switch( $this );
		}

		return false;
	}

	/**
	 * Increase the amount of payment attemtps done through FreePay
	 *
	 * @return int
	 */
	public function get_failed_freepay_payment_count() {
		$order_id = $this->get_id();
		$count    = get_post_meta( $order_id, self::META_FAILED_PAYMENT_COUNT, true );
		if ( empty( $count ) ) {
			$count = 0;
		}

		return $count;
	}

	/**
	 * get_clean_order_number function
	 *
	 * Returns the order number without leading #
	 *
	 * @access public
	 * @return integer
	 */
	public function get_clean_order_number() {
		return str_replace( '#', '', $this->get_order_number() );
	}

	/**
	 * Gets the amount of times the customer has updated his card.
	 *
	 * @return int
	 */
	public function get_payment_method_change_count() {
		$order_id = $this->get_id();
		$count    = get_post_meta( $order_id, self::META_PAYMENT_METHOD_CHANGE_COUNT, true );

		if ( ! empty( $count ) ) {
			return $count;
		}

		return 0;
	}

	/**
	 * Creates an array of order items formatted as "FreePay transaction basket" format.
	 *
	 * @return array
	 */
	public function get_transaction_basket_params() {
		// Contains order items in FreePay basket format
		$basket = [];

		foreach ( $this->get_items() as $item_line ) {
			$basket[] = $this->get_transaction_basket_params_line_helper( $item_line );
		}

		return apply_filters( 'woo_freepay_transaction_params_basket', $basket, $this );
	}

	/**
	 * @param $line_item
	 *
	 * @return array
	 */
	private function get_transaction_basket_params_line_helper( $line_item ) {
		// Before WC 3.0
		/**
		 * @var WC_Order_Item_Product $line_item
		 */

		$vat_rate = 0;

		if ( wc_tax_enabled() ) {
			$taxes = WC_Tax::get_rates( $line_item->get_tax_class() );
			//Get rates of the product
			$rates = array_shift( $taxes );
			//Take only the item rate and round it.
			$vat_rate = ! empty( $rates ) && wc_tax_enabled() ? round( array_shift( $rates ) ) : 0;
		}

		$data = [
			'qty'        => $line_item->get_quantity(),
			'item_no'    => $line_item->get_product_id(),
			'item_name'  => $line_item->get_name(),
			'item_price' => (float) ( $line_item->get_total() + $line_item->get_total_tax() ) / $line_item->get_quantity(),
			'vat_rate'   => $vat_rate,
		];

		return [
			'qty'        => $data['qty'],
			'item_no'    => $data['item_no'], //
			'item_name'  => esc_attr( $data['item_name'] ),
			'item_price' => WC_FreePay_Helper::price_multiply( $data['item_price'] ),
			'vat_rate'   => $data['vat_rate'] > 0 ? $data['vat_rate'] / 100 : 0 // Basket item VAT rate (ex. 0.25 for 25%)
		];
	}

	public function get_transaction_shipping_address_params() {
		$org_address = $this->get_shipping_address_1();

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

		$countryCode = $this->get_shipping_country();
		if(empty($countryCode)) {
			$countryCode = $this->get_billing_country();
		}

		$params = [
			'AddressLine1'		=> $address1,
			'AddressLine2'		=> $address2,
			'City'				=> $this->get_shipping_city(),
			'PostCode'			=> $this->get_shipping_postcode(),
			'Country'			=> WC_FreePay_Countries::getNumFromAlpha2( $countryCode ),
		];

		return apply_filters( 'woo_freepay_transaction_params_shipping', $params, $this );
	}

	public function get_transaction_invoice_address_params() {
		$org_address = $this->get_billing_address_1();
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
			'City'				=> $this->get_billing_city(),
			'PostCode'			=> $this->get_billing_postcode(),
			'Country'			=> WC_FreePay_Countries::getNumFromAlpha2( $this->get_billing_country() ),
		];

		return apply_filters( 'woo_freepay_transaction_params_invoice', $params, $this );
	}

	/**
	 * Increase the amount of payment attemtps done through FreePay
	 *
	 * @return int
	 */
	public function increase_failed_freepay_payment_count() {
		$order_id = $this->get_id();
		$count    = $this->get_failed_freepay_payment_count();
		update_post_meta( $order_id, self::META_FAILED_PAYMENT_COUNT, ++ $count );

		return $count;
	}

	/**
	 * Reset the failed payment attempts made through the FreePay gateway
	 */
	public function reset_failed_freepay_payment_count() {
		$order_id = $this->get_id();
		delete_post_meta( $order_id, self::META_FAILED_PAYMENT_COUNT );
	}

	/**
	 * get_transaction_link_params function.
	 *
	 * Returns the necessary basic params to send to FreePay when creating a payment link
	 *
	 * @access public
	 * @return array
	 */
	public function get_transaction_link_params() {
		global $wp_version;

		$is_subscription = $this->contains_wcs_subscription() || $this->contains_wps_sfw_subscription() || $this->is_request_to_change_payment() || WC_FreePay_Subscription::is_wcs_subscription( $this->get_id() );
		$amount          = $this->get_total();

		if($this->is_request_to_change_payment()) {
			$amount = 0;
		}

		$params			 = [
			'OrderNumber'			=> $this->get_order_number_for_api(),
			'CustomerAcceptUrl'		=> $this->get_continue_url(),
			'CustomerDeclineUrl'	=> $this->get_cancellation_url(),
			'Amount'				=> WC_FreePay_Helper::price_multiply( $amount ),
			'SaveCard'				=> $is_subscription,
			'Client'				=> array(
				'CMS'				=> array(
					'Name'			=> "Wordpress",
					'Version'		=> $wp_version
				),
				'Shop'				=> array(
					'Name'			=> "WooCommerce",
					'Version'		=> WC_VERSION
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

			if( WC_FreePay_Subscription::plugin_is_active() && !$this->contains_wps_sfw_subscription() ) {
				if($this->is_request_to_change_payment()) {
					$subscriptions = array(wcs_get_subscription( $this->get_id() ));
				}
				else {
					$subscriptions = wcs_get_subscriptions_for_order( $this->get_id(), [ 'order_type' => 'any' ] );
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
			else if( WC_FreePay_Subscription::sfw_plugin_is_active() && $this->contains_wps_sfw_subscription() ) {
				$subscription_id = get_post_meta( $this->id, 'wps_subscription_id', true );
				
				$trial_number = get_post_meta( $subscription_id, 'wps_sfw_subscription_free_trial_number', true );
				$trial_interval = get_post_meta( $subscription_id, 'wps_sfw_subscription_free_trial_interval', true );

				$sub_number = get_post_meta( $subscription_id, 'wps_sfw_subscription_number', true );
				$sub_interval = get_post_meta( $subscription_id, 'wps_sfw_subscription_interval', true );

				$end_number = get_post_meta( $subscription_id, 'wps_sfw_subscription_expiry_number', true );
				$end_interval = get_post_meta( $subscription_id, 'wps_sfw_subscription_expiry_interval', true );

				$dateTimeCurrent = new DateTime(date('Y-m-d', current_time( 'timestamp' )));

				if($end_number) {
					$maxExpiration = $this->wps_sfw_get_timestamp( current_time( 'timestamp' ), $end_interval, intval( $end_number ) );
				}

				if($trial_number) {
					$trialTimestamp = $this->wps_sfw_get_timestamp( current_time( 'timestamp' ), $trial_interval, intval( $trial_number ) );
					$dateTimeLater = new DateTime(date('Y-m-d', $trialTimestamp));
					$minFrequency = $dateTimeLater->diff($dateTimeCurrent)->format("%a");
				}
				else {
					$subTimestamp = $this->wps_sfw_get_timestamp( current_time( 'timestamp' ), $sub_interval, intval( $sub_number ) );
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

	public function wps_sfw_get_timestamp( $wps_curr_time, $interval, $number ) {

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
	 *
	 * Returns the order's continue callback url
	 *
	 * @access public
	 * @return string
	 */
	public function get_continue_url() {
		if ( method_exists( $this, 'get_checkout_order_received_url' ) ) {
			return $this->get_checkout_order_received_url();
		}

		return add_query_arg( 'key', $this->order_key, add_query_arg( 'order', $this->get_id(), get_permalink( get_option( 'woocommerce_thanks_page_id' ) ) ) );
	}

	/**
	 * get_cancellation_url function
	 *
	 * Returns the order's cancellation callback url
	 *
	 * @access public
	 * @return string
	 */
	public function get_cancellation_url() {
		if( !empty( WC_FP_MAIN()->s( 'freepay_decline_url' ) ) ) {
			return add_query_arg(
				array(
					'cancel_order' => 'true',
					'order'        => $this->get_order_key(),
					'order_id'     => $this->get_id(),
					'redirect'     => '',
					'_wpnonce'     => wp_create_nonce( 'woocommerce-cancel_order' ),
				),
				//get_permalink( get_page_by_path( WC_FP_MAIN()->s( 'freepay_decline_url' ) ) )
				WC_FP_MAIN()->s( 'freepay_decline_url' )
			);
		}

		if ( method_exists( $this, 'get_cancel_order_url' ) ) {
			return str_replace( '&amp;', '&', $this->get_cancel_order_url() );
		}

		return add_query_arg( 'key', $this->get_order_key(), add_query_arg( [
			'order'                => $this->get_id(),
			'payment_cancellation' => 'yes',
		], get_permalink( get_option( 'woocommerce_cart_page_id' ) ) ) );
	}

	/**
	 * has_freepay_payment function
	 *
	 * Checks if the order is paid with the FreePay module.
	 *
	 * @return bool
	 * @since  4.5.0
	 * @access public
	 */
	public function has_freepay_payment() {
		$order_id = $this->get_id();

		return in_array( get_post_meta( $order_id, '_payment_method', true ), [
			'freepay',
		] );
	}

	/**
	 * Increases the amount of times the customer has updated his card.
	 *
	 * @return int
	 */
	public function increase_payment_method_change_count() {
		$count    = $this->get_payment_method_change_count();
		$order_id = $this->get_id();

		update_post_meta( $order_id, self::META_PAYMENT_METHOD_CHANGE_COUNT, ++ $count );

		return $count;
	}

	/**
	 * @param string $context
	 *
	 * @return mixed|string
	 */
	public function get_transaction_id( $context = 'view' ) {
		$order_id = $this->get_id();

		// Search for custom transaction meta added in 4.8 to avoid transaction ID
		// sometimes being empty on subscriptions in WC 3.0.
		$transaction_id = get_post_meta( $order_id, '_freepay_transaction_id', true );
		if ( empty( $transaction_id ) ) {

			$transaction_id = parent::get_transaction_id( $context );

			if ( empty( $transaction_id ) ) {
				// Search for original transaction ID. The transaction might be temporarily removed by
				// subscriptions. Use this one instead (if available).
				$transaction_id = get_post_meta( $order_id, '_transaction_id_original', true );
				if ( empty( $transaction_id ) ) {
					// Check if the old legacy TRANSACTION ID meta value is available.
					$transaction_id = get_post_meta( $order_id, 'TRANSACTION_ID', true );
				}
			}
		}

		return $transaction_id;
	}
}

