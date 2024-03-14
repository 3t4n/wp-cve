<?php
/**
 * WC_FreePay_API_Transaction class
 */

class WC_FreePay_API_Transaction extends WC_FreePay_API {

	/**
	 * @var bool
	 */
	protected $loaded_from_cache = false;
	protected $payment_api_url = '';

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Run the parent construct
		parent::__construct();

		// Append the main API url
		$this->payment_api_url = 'https://gw.freepay.dk/api/payment';
	}

	/**
	 * get_current_type function.
	 *
	 * Returns the current payment type
	 *
	 * @access public
	 * @return string
	 * @throws FreePay_API_Exception
	 */
	public function get_current_type() {
		return '';
	}

	/**
	 * is_test function.
	 *
	 * Tests if a payment was made in test mode.
	 *
	 * @access public
	 * @return boolean
	 * @throws FreePay_API_Exception
	 */
	public function is_test() {
		if ( is_object( $this->resource_data ) ) {
			return $this->resource_data->Acquirer == "255";
		}

		return false;
	}

	/**
	 * create_link function.
	 *
	 * Creates or updates a payment link via the API
	 *
	 * @param int $transaction_id
	 * @param WC_Order $order
	 *
	 * @return object
	 * @throws FreePay_API_Exception
	 * @since  4.5.0
	 * @access public
	 *
	 */
	public function create_link( WC_Order $order ) {
		$payment_method = strtolower( $order->get_payment_method() );

		//in very special case of renew order of a subscription with payment link for fixing failed subscriptions. Pay for renew and update subscription identifier
		if($order->get_status() === 'failed') {
			$parentSubscription = WC_FreePay_Subscription_Utils::get_subscriptions_for_renewal_order($order, true);
		}

		$base_params = [
			'EnforceLanguage'				=> WC_FP_MAIN()->get_gateway_language(),
			'Currency'                     	=> WC_FP_MAIN()->get_gateway_currency( $order ),
			'ServerCallbackUrl'				=> WC_FreePay_Helper::get_callback_url() . sprintf('&order_id=%s&order_key=%s',
																						WC_FreePay_Order_Utils::get_order_number_for_api(!empty($parentSubscription) ? $parentSubscription : $order, false, !empty($parentSubscription)),
																						WC_FreePay_Order_Utils::get_order_key_for_api(!empty($parentSubscription) ? $parentSubscription : $order)
																					),
		];

		if(WC_FreePay_Order_Utils::is_request_to_change_payment()) {
			$base_params['ServerCallbackUrl'] = $base_params['ServerCallbackUrl'] . '&c_card=1';
		}
		else if(!empty($parentSubscription)) {
			$base_params['ServerCallbackUrl'] = $base_params['ServerCallbackUrl'] . '&is_renew=1';
		}

		$transaction_params = WC_FreePay_Order_Utils::get_transaction_params($order);

		$order_params = WC_FreePay_Order_Utils::get_transaction_link_params($order);

		$merged_params = array_merge( $base_params, $order_params, $transaction_params );

		$merged_params['Options']["WooCommerceCurrencyMatch"] = WC_FP_MAIN()->get_gateway_currency($order) == $order->get_currency();

		$params = apply_filters( 'woo_freepay_transaction_link_params', $merged_params, $order, $payment_method );

		$payment_link = $this->post( '/', $params, false, $this->payment_api_url );

		return $payment_link->paymentWindowLink;
	}

	/**
	 * get_cardtype function
	 *
	 * Returns the payment type / card type used on the transaction
	 *
	 * @return mixed
	 * @throws FreePay_API_Exception
	 * @since  4.5.0
	 */
	public function get_brand() {
		if ( ! is_object( $this->resource_data ) ) {
			throw new FreePay_API_Exception( 'No API payment resource data available.', 0 );
		}

		$visaCodes = [12,6,7,8,9,10];
		$masterCodes = [14,15,4,5,16];
		$dankortCodes = [13];

		if($this->resource_data->WalletProvider == 1) {
			return "mobilepay";
		}
		else if(in_array($this->resource_data->CardType, $dankortCodes)) {
			return "dankort";
		}
		else if(in_array($this->resource_data->CardType, $masterCodes)) {
			return "mastercard";
		}
		else if(in_array($this->resource_data->CardType, $visaCodes)) {
			return "visa";
		}
		else {
			return "";
		}
	}

	/**
	 * get_formatted_balance function
	 *
	 * Returns a formatted transaction balance
	 *
	 * @return mixed
	 * @throws FreePay_API_Exception
	 * @since  4.5.0
	 */
	public function get_formatted_balance() {
		return WC_FreePay_Helper::price_normalize( $this->get_balance() );
	}

	/**
	 * get_balance function
	 *
	 * Returns the transaction balance
	 *
	 * @return mixed
	 * @throws FreePay_API_Exception
	 * @since  4.5.0
	 */
	public function get_balance() {
		if ( ! is_object( $this->resource_data ) ) {
			throw new FreePay_API_Exception( 'No API payment resource data available.', 0 );
		}

		if($this->resource_data->IsCaptured == 1) {
			if($this->resource_data->TotalAmountCaptured === 0) {
				return $this->resource_data->CaptureAmount;
			}
			else {
				return $this->resource_data->TotalAmountCaptured;
			}
		}

		return 0;
	}

	/**
	 * get_currency function
	 *
	 * Returns a transaction currency
	 *
	 * @return mixed
	 * @throws FreePay_API_Exception
	 * @since  4.5.0
	 */
	public function get_currency() {
		if ( ! is_object( $this->resource_data ) ) {
			throw new FreePay_API_Exception( 'No API payment resource data available.', 0 );
		}

		return $this->resource_data->Currency;
	}

	/**
	 * get_formatted_remaining_balance function
	 *
	 * Returns a formatted transaction balance
	 *
	 * @return mixed
	 * @throws FreePay_API_Exception
	 * @since  4.5.0
	 */
	public function get_formatted_remaining_balance() {
		return WC_FreePay_Helper::price_normalize( $this->get_remaining_balance() );
	}

	/**
	 * get_remaining_balance function
	 *
	 * Returns a remaining balance
	 *
	 * @return mixed
	 * @throws FreePay_API_Exception
	 * @since  4.5.0
	 */
	public function get_remaining_balance() {
		$balance = $this->resource_data->AuthorizationAmount - $this->get_balance();

		return $balance;
	}

	/**
	 * get_state function
	 *
	 * Returns the current transaction state
	 *
	 * @return mixed
	 * @throws FreePay_API_Exception
	 * @since  4.5.0
	 */
	public function get_state() {
		if ( ! is_object( $this->resource_data ) ) {
			throw new FreePay_API_Exception( 'No API payment resource data available.', 0 );
		}

		if($this->resource_data->IsCaptured) {
			if($this->resource_data->TotalAmountCaptured === 0 || $this->resource_data->TotalAmountCaptured == $this->resource_data->AuthorizationAmount) {
				return "capture";
			}
			else {
				return "partcapture";
			}
		}
		else if(!empty($this->resource_data->IsSubscription)) {
			return "subscribe";
		}
		else {
			return "authorize";
		}
	}

	/**
	 * get_state_name function
	 *
	 * Returns the current transaction state friendly name
	 *
	 * @return mixed
	 * @throws FreePay_API_Exception
	 * @since  4.5.0
	 */
	public function get_state_name($state) {
		if($state == "capture") {
			return __( 'Captured', 'freepay-for-woocommerce' );
		}
		if($state == "partcapture") {
			return __( 'Partialy captured', 'freepay-for-woocommerce' );
		}
		else if($state == "subscribe") {
			return __( 'Subscription', 'freepay-for-woocommerce' );
		}
		else {
			return __( 'Authorized', 'freepay-for-woocommerce' );
		}
	}

	/**
	 * Fetches transaction data based on a transaction ID. This method checks if the transaction is cached in a transient before it asks the
	 * FreePay API. Cached data will always be used if available.
	 *
	 * If no data is cached, we will fetch the transaction from the API and cache it.
	 *
	 * @param        $transaction_id
	 *
	 * @return object|stdClass
	 * @throws FreePay_API_Exception
	 * @throws FreePay_Exception
	 */
	public function maybe_load_transaction_from_cache( $transaction_id ) {

		$is_caching_enabled = self::is_transaction_caching_enabled();

		if ( empty( $transaction_id ) ) {
			throw new FreePay_Exception( __( 'Transaction ID cannot be empty', 'freepay-for-woocommerce' ) );
		}

		if ( $is_caching_enabled && false !== ( $transient = get_transient( 'wcfp_transaction_' . $transaction_id ) ) ) {
			$this->loaded_from_cache = true;

			return $this->resource_data = (object) json_decode( $transient );
		}

		$this->get( $transaction_id );

		if ( $is_caching_enabled ) {
			$this->cache_transaction();
		}

		return $this->resource_data;
	}

	/**
	 * @return boolean
	 */
	public static function is_transaction_caching_enabled() {
		$is_enabled = strtolower( WC_FP_MAIN()->s( 'freepay_caching_enabled' ) ) === 'no' ? false : true;

		return apply_filters( 'woo_freepay_transaction_cache_enabled', $is_enabled );
	}

	/**
	 * Updates cache data for a transaction
	 *
	 * @return boolean
	 * @throws FreePay_Exception
	 */
	public function cache_transaction() {
		if ( ! is_object( $this->resource_data ) ) {
			throw new FreePay_Exception( "Cannot cache empty transaction." );
		}

		if ( ! self::is_transaction_caching_enabled() ) {
			return false;
		}

		$expiration = (int) WC_FP_MAIN()->s( 'freepay_caching_expiration' );

		if ( ! $expiration ) {
			$expiration = 7 * DAY_IN_SECONDS;
		}

		// Cache expiration in seconds
		$expiration = apply_filters( 'woo_freepay_transaction_cache_expiration', $expiration );

		return set_transient( 'wcfp_transaction_' . $this->resource_data->AuthorizationIdentifier, json_encode( $this->resource_data ), $expiration );
	}

	/**
	 * @return bool
	 */
	public function is_loaded_from_cached() {
		return $this->loaded_from_cache;
	}

	/**
	 * return stdClass
	 */
	public function get_data() {
		return $this->resource_data;
	}

	public function is_valid($orderId, $amount = null) {
		if(!empty($amount)) {
			return $this->resource_data->OrderID == $orderId && $this->resource_data->AuthorizationAmount == $amount;
		}
		else {
			return $this->resource_data->OrderID == $orderId;
		}
	}

	public function is_zero_subscription() {
		return $this->resource_data->AuthorizationAmount == 0 && $this->resource_data->IsSubscription == true;
	}
}