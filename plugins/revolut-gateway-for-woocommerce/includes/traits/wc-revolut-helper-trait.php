<?php
/**
 * Revolut Helper
 *
 * Helper class for required tools.
 *
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Gateway_Revolut_Helper_Trait trait.
 */
trait WC_Gateway_Revolut_Helper_Trait {


	use WC_Revolut_Settings_Trait;
	use WC_Revolut_Logger_Trait;

	/**
	 * Create Revolut Order
	 *
	 * @param WC_Revolut_Order_Descriptor $order_descriptor Revolut Order Descriptor.
	 *
	 * @param bool                        $is_express_checkout indicator.
	 *
	 * @return mixed
	 * @throws Exception Exception.
	 */
	public function create_revolut_order( WC_Revolut_Order_Descriptor $order_descriptor, $is_express_checkout = false ) {
		$capture = 'authorize' === $this->api_settings->get_option( 'payment_action' ) || $is_express_checkout ? 'manual' : 'automatic';

		$body = array(
			'amount'       => $order_descriptor->amount,
			'currency'     => $order_descriptor->currency,
			'capture_mode' => $capture,
		);

		if ( ! empty( $order_descriptor->revolut_customer_id ) ) {
			$body['customer'] = array( 'id' => $order_descriptor->revolut_customer_id );
		}

		if ( $is_express_checkout ) {
			$body['cancel_authorised_after'] = WC_REVOLUT_AUTO_CANCEL_TIMEOUT;
			$location_id                     = $this->api_settings->get_revolut_location();
			if ( $location_id ) {
				$body['location_id'] = $location_id;
			}
		}

		$json = $this->api_client->post( '/orders', $body, false, true );

		if ( isset( $json['token'] ) ) {
			$json['public_id'] = $json['token'];
		}

		if ( empty( $json['id'] ) || empty( $json['public_id'] ) ) {
			throw new Exception( 'Something went wrong: ' . wp_json_encode( $json, JSON_PRETTY_PRINT ) );
		}

		global $wpdb;
		$result = $wpdb->query(
			$wpdb->prepare(
				'INSERT INTO ' . $wpdb->prefix . "wc_revolut_orders (order_id, public_id)
            VALUES (UNHEX(REPLACE(%s, '-', '')), UNHEX(REPLACE(%s, '-', '')))",
				array(
					$json['id'],
					$json['public_id'],
				)
			)
		); // db call ok; no-cache ok.

		if ( 1 !== $result ) {
			throw new Exception( 'Can not save Revolut order record on DB:' . $wpdb->last_error );
		}

		if ( $is_express_checkout ) {
			$this->add_or_update_temp_session( $json['id'] );
		}

		return $json['public_id'];
	}
	/**
	 * Retrieve the revolut customer's id.
	 *
	 * @param  string $billing_phone holds customer email address.
	 * @param  string $billing_email holds customer billing phone.
	 * @throws Exception Exception.
	 */
	public function get_or_create_revolut_customer( $billing_phone = '', $billing_email = '' ) {
		if ( empty( $billing_email ) || empty( $billing_phone ) ) {
			$wc_customer   = WC()->customer;
			$billing_email = $wc_customer->get_billing_email();
			$billing_phone = $wc_customer->get_billing_phone();
		}

		if ( empty( $billing_email ) || empty( $billing_phone ) ) {
			return;
		}

		$revolut_customer_id = $this->get_revolut_customer_id();

		if ( empty( $revolut_customer_id ) ) {
			$revolut_customer_id = $this->create_revolut_customer( $billing_phone, $billing_email );
			return $revolut_customer_id;
		}

		return $revolut_customer_id;
	}

	/**
	 * Update the revolut customer's phone.
	 *
	 * @param string $revolut_customer_id customer_id.
	 * @param string $billing_phone billing phone number.
	 * @throws Exception Exception.
	 * @return void|null
	 */
	public function update_revolut_customer( $revolut_customer_id, $billing_phone ) {
		if ( empty( $revolut_customer_id ) || empty( $billing_phone ) ) {
			return null;
		}

		$this->api_client->patch( "/customers/$revolut_customer_id", array( 'phone' => $billing_phone ) );
	}

	/**
	 * Create revolut customer.
	 *
	 * @return $revolut_customer_id revolut customer id.
	 * @param  string $billing_phone holds customer billing phone.
	 * @param  string $billing_email holds customer email address.
	 * @throws Exception Exception.
	 */
	public function create_revolut_customer( $billing_phone, $billing_email ) {
		try {
			if ( empty( $billing_phone ) || empty( $billing_email ) ) {
				return;
			}

			$revolut_customer_id = null;

			$body = array(
				'phone' => $billing_phone,
				'email' => $billing_email,
			);

			$revolut_customer    = $this->api_client->get( '/customers?term=' . $billing_email );
			$revolut_customer_id = ! empty( $revolut_customer[0]['id'] ) ? $revolut_customer[0]['id'] : '';

			if ( ! $revolut_customer_id ) {
				$revolut_customer    = $this->api_client->post( '/customers', $body );
				$revolut_customer_id = ! empty( $revolut_customer['id'] ) ? $revolut_customer['id'] : '';
			}

			if ( ! $revolut_customer_id ) {
				return $revolut_customer_id;
			}

			$this->insert_revolut_customer_id( $revolut_customer_id );

			$this->update_revolut_customer( $revolut_customer_id, $billing_phone );
			return $revolut_customer_id;
		} catch ( Exception $e ) {
			$this->log_error( 'create_revolut_customer: ' . $e->getMessage() );
		}
	}

	/**
	 * Save Revolut customer id.
	 *
	 * @param string $revolut_customer_id Revolut customer id.
	 *
	 * @throws Exception Exception.
	 */
	protected function insert_revolut_customer_id( $revolut_customer_id ) {
		if ( empty( get_current_user_id() ) ) {
			return;
		}

		global $wpdb;
		$revolut_customer_id = "{$this->api_client->mode}_$revolut_customer_id";

		$wpdb->query(
			$wpdb->prepare(
				"INSERT INTO {$wpdb->prefix}wc_revolut_customer (wc_customer_id, revolut_customer_id) 
				 VALUES (%d, %s) ON DUPLICATE KEY UPDATE wc_customer_id = VALUES(wc_customer_id)",
				array( get_current_user_id(), $revolut_customer_id )
			)
		); // db call ok; no-cache ok.
	}

	/**
	 * Update Revolut Order.
	 *
	 * @param WC_Revolut_Order_Descriptor $order_descriptor Revolut Order Descriptor.
	 * @param String                      $public_id Revolut public id.
	 * @param Bool                        $is_revpay_express_checkout is revpay express checkout.
	 *
	 * @return mixed
	 * @throws Exception Exception.
	 */
	public function update_revolut_order( WC_Revolut_Order_Descriptor $order_descriptor, $public_id, $is_revpay_express_checkout = false ) {
		$order_id = $this->get_revolut_order_by_public_id( $public_id );

		$body = array(
			'amount'      => $order_descriptor->amount,
			'currency'    => $order_descriptor->currency,
			'customer_id' => $order_descriptor->revolut_customer_id,
		);

		if ( empty( $order_id ) ) {
			return $this->create_revolut_order( $order_descriptor, $is_revpay_express_checkout );
		}

		$revolut_order = $this->api_client->get( "/orders/$order_id" );

		if ( ! isset( $revolut_order['public_id'] ) || ! isset( $revolut_order['id'] ) || 'PENDING' !== $revolut_order['state'] ) {
			return $this->create_revolut_order( $order_descriptor, $is_revpay_express_checkout );
		}

		$revolut_order = $this->api_client->patch( "/orders/$order_id", $body );

		if ( ! isset( $revolut_order['public_id'] ) || ! isset( $revolut_order['id'] ) ) {
			return $this->create_revolut_order( $order_descriptor, $is_revpay_express_checkout );
		}

		if ( $is_revpay_express_checkout ) {
			$this->add_or_update_temp_session( $revolut_order['id'] );
		}

		return $revolut_order['public_id'];
	}

	/**
	 * Convert saved customer session into current session.
	 *
	 * @param string $id_revolut_order Revolut order id.
	 *
	 * @return void.
	 */
	public function convert_revolut_order_metadata_into_wc_session( $id_revolut_order ) {
		WC()->initialize_session();
		WC()->initialize_cart();

		global $wpdb;
		$temp_session = $wpdb->get_row( $wpdb->prepare( 'SELECT temp_session FROM ' . $wpdb->prefix . 'wc_revolut_temp_session WHERE order_id=%s', array( $id_revolut_order ) ), ARRAY_A ); // db call ok; no-cache ok.

		$this->log_info( 'start convert_revolut_order_metadata_into_wc_session temp_session:' );
		$this->log_info( $temp_session['temp_session'] );

		$wc_order_metadata = json_decode( $temp_session['temp_session'], true );
		$id_wc_customer    = (int) $wc_order_metadata['id_customer'];

		if ( $id_wc_customer ) {
			wp_set_current_user( $id_wc_customer );
		}

		WC()->session->set( 'cart', $wc_order_metadata['cart'] );
		WC()->session->set( 'cart_totals', $wc_order_metadata['cart_totals'] );
		WC()->session->set( 'applied_coupons', $wc_order_metadata['applied_coupons'] );
		WC()->session->set( 'coupon_discount_totals', $wc_order_metadata['coupon_discount_totals'] );
		WC()->session->set( 'coupon_discount_tax_totals', $wc_order_metadata['coupon_discount_tax_totals'] );
		WC()->session->set( 'get_removed_cart_contents', $wc_order_metadata['get_removed_cart_contents'] );

		$session = new WC_Cart_Session( WC()->cart );
		$session->get_cart_from_session();
	}

	/**
	 * Get order details
	 *
	 * @param array  $address Customer address.
	 * @param bool   $shipping_required is shipping option required for the current order.
	 * @param string $gateway selected payment gateway.
	 * @throws Exception Exception.
	 */
	public function format_wc_order_details( $address, $shipping_required, $gateway ) {
		if ( empty( $address['billingAddress'] ) ) {
			throw new Exception( 'Billing address is missing' );
		}

		if ( $shipping_required && empty( $address['shippingAddress'] ) ) {
			throw new Exception( 'Shipping address is missing' );
		}

		if ( empty( $address['email'] ) ) {
			throw new Exception( 'User Email information is missing' );
		}

		$revolut_billing_address         = $address['billingAddress'];
		$revolut_customer_email          = $address['email'];
		$revolut_customer_full_name      = ! empty( $revolut_billing_address['recipient'] ) ? $revolut_billing_address['recipient'] : '';
		$revolut_customer_billing_phone  = ! empty( $revolut_billing_address['phone'] ) ? $revolut_billing_address['phone'] : '';
		$revolut_customer_shipping_phone = '';
		$wc_shipping_address             = array();

		list($billing_firstname, $billing_lastname) = $this->parse_customer_name( $revolut_customer_full_name );

		if ( isset( $address['shippingAddress'] ) && ! empty( $address['shippingAddress'] ) ) {
			$revolut_shipping_address            = $address['shippingAddress'];
			$revolut_customer_shipping_phone     = ! empty( $revolut_shipping_address['phone'] ) ? $revolut_shipping_address['phone'] : '';
			$revolut_customer_shipping_full_name = ! empty( $revolut_shipping_address['recipient'] ) ? $revolut_shipping_address['recipient'] : '';

			$shipping_firstname = $billing_firstname;
			$shipping_lastname  = $billing_lastname;

			if ( ! empty( $revolut_customer_shipping_full_name ) ) {
				list($shipping_firstname, $shipping_lastname) = $this->parse_customer_name( $revolut_customer_shipping_full_name );
			}

			if ( empty( $revolut_customer_shipping_phone ) && ! empty( $revolut_customer_billing_phone ) ) {
				$revolut_customer_shipping_phone = $revolut_customer_billing_phone;
			}

			$wc_shipping_address = $this->get_wc_shipping_address( $revolut_shipping_address, $revolut_customer_email, $revolut_customer_shipping_phone, $shipping_firstname, $shipping_lastname );
		}

		if ( empty( $revolut_customer_billing_phone ) && ! empty( $revolut_customer_shipping_phone ) ) {
			$revolut_customer_billing_phone = $revolut_customer_shipping_phone;
		}

		$wc_billing_address = $this->get_wc_billing_address( $revolut_billing_address, $revolut_customer_email, $revolut_customer_billing_phone, $billing_firstname, $billing_lastname );

		if ( $shipping_required ) {
			$wc_order_data = array_merge( $wc_billing_address, $wc_shipping_address );
		} else {
			$wc_order_data = $wc_billing_address;
		}

		$wc_order_data['ship_to_different_address']    = $shipping_required;
		$wc_order_data['revolut_pay_express_checkout'] = 'revolut_pay' === $gateway;
		$wc_order_data['terms']                        = 1;
		$wc_order_data['order_comments']               = '';

		return $wc_order_data;
	}

	/**
	 * Get first and lastname from customer full name string.
	 *
	 * @param string $full_name Customer full name.
	 */
	public function parse_customer_name( $full_name ) {
		$full_name_list = explode( ' ', $full_name );
		if ( count( $full_name_list ) > 1 ) {
			$lastname  = array_pop( $full_name_list );
			$firstname = implode( ' ', $full_name_list );
			return array( $firstname, $lastname );
		}

		$firstname = $full_name;
		$lastname  = 'undefined';

		return array( $firstname, $lastname );
	}

	/**
	 * Create billing address for order.
	 *
	 * @param array  $shipping_address Shipping address.
	 * @param string $revolut_customer_email Email.
	 * @param string $revolut_customer_phone Phone.
	 * @param string $firstname Firstname.
	 * @param string $lastname Lastname.
	 */
	public function get_wc_shipping_address( $shipping_address, $revolut_customer_email, $revolut_customer_phone, $firstname, $lastname ) {
		if ( isset( $shipping_address['country'] ) ) {
			$shipping_address['country'] = strtoupper( $shipping_address['country'] );
		}
		$address['shipping_first_name'] = $firstname;
		$address['shipping_last_name']  = $lastname;
		$address['shipping_email']      = $revolut_customer_email;
		$address['shipping_phone']      = $revolut_customer_phone;
		$address['shipping_country']    = ! empty( $shipping_address['country'] ) ? $shipping_address['country'] : '';
		$address['shipping_address_1']  = ! empty( $shipping_address['addressLine'][0] ) ? $shipping_address['addressLine'][0] : '';
		$address['shipping_address_2']  = ! empty( $shipping_address['addressLine'][1] ) ? $shipping_address['addressLine'][1] : '';
		$address['shipping_city']       = ! empty( $shipping_address['city'] ) ? $shipping_address['city'] : '';
		$address['shipping_state']      = ! empty( $shipping_address['region'] ) ? $this->convert_state_name_to_id( $shipping_address['country'], $shipping_address['region'] ) : '';
		$address['shipping_postcode']   = ! empty( $shipping_address['postalCode'] ) ? $shipping_address['postalCode'] : '';
		$address['shipping_company']    = '';

		return $address;
	}

	/**
	 * Create billing address for order.
	 *
	 * @param array  $billing_address Billing address.
	 * @param string $revolut_customer_email Email.
	 * @param string $revolut_customer_phone Phone.
	 * @param string $firstname Firstname.
	 * @param string $lastname Lastname.
	 */
	public function get_wc_billing_address( $billing_address, $revolut_customer_email, $revolut_customer_phone, $firstname, $lastname ) {
		if ( isset( $billing_address['country'] ) ) {
			$billing_address['country'] = strtoupper( $billing_address['country'] );
		}
		$address                       = array();
		$address['billing_first_name'] = $firstname;
		$address['billing_last_name']  = $lastname;

		$address['billing_email']     = $revolut_customer_email;
		$address['billing_phone']     = $revolut_customer_phone;
		$address['billing_country']   = ! empty( $billing_address['country'] ) ? $billing_address['country'] : '';
		$address['billing_address_1'] = ! empty( $billing_address['addressLine'][0] ) ? $billing_address['addressLine'][0] : '';
		$address['billing_address_2'] = ! empty( $billing_address['addressLine'][1] ) ? $billing_address['addressLine'][1] : '';
		$address['billing_city']      = ! empty( $billing_address['city'] ) ? $billing_address['city'] : '';
		$address['billing_state']     = ! empty( $billing_address['region'] ) ? $this->convert_state_name_to_id( $billing_address['country'], $billing_address['region'] ) : '';
		$address['billing_postcode']  = ! empty( $billing_address['postalCode'] ) ? $billing_address['postalCode'] : '';
		$address['billing_company']   = '';

		return $address;
	}

	/**
	 * Check if payment is pending.
	 *
	 * @param string $revolut_order_id Revolut order id.
	 */
	protected function is_pending_payment( $revolut_order_id ) {
		$revolut_order = $this->api_client->get( '/orders/' . $revolut_order_id );
		return ! isset( $revolut_order['state'] ) || ( isset( $revolut_order['state'] ) && 'PENDING' === $revolut_order['state'] );
	}

	/**
	 * Save or Update customer session temporarily.
	 *
	 * @param string $revolut_order_id Revolut order id.
	 *
	 * @throws Exception Exception.
	 */
	public function add_or_update_temp_session( $revolut_order_id ) {
		$order_metadata['id_customer']                = get_current_user_id();
		$order_metadata['cart']                       = WC()->cart->get_cart_for_session();
		$order_metadata['cart_totals']                = WC()->cart->get_totals();
		$order_metadata['applied_coupons']            = WC()->cart->get_applied_coupons();
		$order_metadata['coupon_discount_totals']     = WC()->cart->get_coupon_discount_totals();
		$order_metadata['coupon_discount_tax_totals'] = WC()->cart->get_coupon_discount_tax_totals();
		$order_metadata['get_removed_cart_contents']  = WC()->cart->get_removed_cart_contents();

		$temp_session = wp_json_encode( $order_metadata );

		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				'INSERT INTO ' . $wpdb->prefix . 'wc_revolut_temp_session (order_id, temp_session)
            VALUES (%s, %s) ON DUPLICATE KEY UPDATE temp_session =  VALUES(temp_session)',
				array(
					$revolut_order_id,
					$temp_session,
				)
			)
		); // db call ok; no-cache ok.
	}

	/**
	 * Get Revolut customer id.
	 *
	 * @param int $wc_customer_id WooCommerce customer id.
	 */
	public function get_revolut_customer_id( $wc_customer_id = false ) {
		if ( ! $wc_customer_id ) {
			$wc_customer_id = get_current_user_id();
		}

		if ( empty( $wc_customer_id ) ) {
			return null;
		}

		global $wpdb;
		$revolut_customer_id = $wpdb->get_col( $wpdb->prepare( 'SELECT revolut_customer_id FROM ' . $wpdb->prefix . 'wc_revolut_customer WHERE wc_customer_id=%s', array( $wc_customer_id ) ) ); // db call ok; no-cache ok.
		$revolut_customer_id = reset( $revolut_customer_id );

		if ( empty( $revolut_customer_id ) ) {
			$revolut_customer_id = null;
		}

		$revolut_customer_id_with_mode = explode( '_', $revolut_customer_id );

		if ( count( $revolut_customer_id_with_mode ) > 1 ) {
			list( $api_mode, $revolut_customer_id ) = $revolut_customer_id_with_mode;

			if ( $api_mode !== $this->api_client->mode ) {
				$this->delete_customer_record( $wc_customer_id );
				return null;
			}
		}

		// verify customer id through api.
		$revolut_customer = $this->api_client->get( '/customers/' . $revolut_customer_id );

		if ( empty( $revolut_customer['id'] ) ) {
			$this->delete_customer_record( $wc_customer_id );
			return null;
		}

		return $revolut_customer_id;
	}

	/**
	 * Remove customer db record
	 *
	 * @param string $wc_customer_id customer id.
	 */
	public function delete_customer_record( $wc_customer_id ) {
		global $wpdb;
		$wpdb->delete( // phpcs:ignore
			$wpdb->prefix . 'wc_revolut_customer',
			array(
				'wc_customer_id' => $wc_customer_id,
			)
		);
	}

	/**
	 * Update Revolut Order Total
	 *
	 * @param float  $order_total Order total.
	 * @param string $currency Order currency.
	 * @param string $public_id Order public id.
	 *
	 * @return bool
	 * @throws Exception Exception.
	 */
	public function update_revolut_order_total( $order_total, $currency, $public_id ) {
		$order_id = $this->get_revolut_order_by_public_id( $public_id );

		$order_total = round( $order_total, 2 );

		$revolut_order_total = $this->get_revolut_order_total( $order_total, $currency );

		$body = array(
			'amount'   => $revolut_order_total,
			'currency' => $currency,
		);

		if ( empty( $order_id ) ) {
			return false;
		}

		$revolut_order = $this->api_client->get( "/orders/$order_id" );

		if ( ! isset( $revolut_order['public_id'] ) || ! isset( $revolut_order['id'] ) || 'PENDING' !== $revolut_order['state'] ) {
			return false;
		}

		$revolut_order = $this->api_client->patch( "/orders/$order_id", $body );

		if ( ! isset( $revolut_order['public_id'] ) || ! isset( $revolut_order['id'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Fetch Revolut order by public id
	 *
	 * @param String $public_id Revolut public id.
	 *
	 * @return string|null
	 */
	public function get_revolut_order_by_public_id( $public_id ) {
		global $wpdb;
		// resolve into order_id.
		return $this->uuid_dashes(
			$wpdb->get_col( // phpcs:ignore
				$wpdb->prepare(
					'SELECT HEX(order_id) FROM ' . $wpdb->prefix . 'wc_revolut_orders
                WHERE public_id=UNHEX(REPLACE(%s, "-", ""))',
					array( $public_id )
				)
			)
		);
	}

	/**
	 * Load Merchant Public Key from API.
	 *
	 * @return string
	 */
	public function get_merchant_public_api_key() {
		try {
			$merchant_public_key = $this->get_revolut_merchant_public_key();

			if ( ! empty( $merchant_public_key ) ) {
				return $merchant_public_key;
			}

			$merchant_public_key = $this->api_client->get( WC_GATEWAY_PUBLIC_KEY_ENDPOINT, false, true );
			$merchant_public_key = isset( $merchant_public_key['public_key'] ) ? $merchant_public_key['public_key'] : '';

			if ( empty( $merchant_public_key ) ) {
				return '';
			}

			$this->set_revolut_merchant_public_key( $merchant_public_key );
			return $merchant_public_key;
		} catch ( Exception $e ) {
			$this->log_error( 'get_merchant_public_api_key: ' . $e->getMessage() );
			return '';
		}
	}

	/**
	 * Check Merchant Account features.
	 *
	 * @return bool
	 */
	public function check_feature_support() {
		try {
			$this->api_client->set_public_key( $this->get_merchant_public_api_key() );
			$merchant_features = $this->api_client->get( '/merchant', true );

			return isset( $merchant_features['features'] ) && is_array( $merchant_features['features'] ) && in_array(
				WC_GATEWAY_REVPAY_INDEX,
				$merchant_features['features'],
				true
			);
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * Checks if page is pay for order and change subs payment page.
	 *
	 * @return bool
	 */
	public function is_subs_change_payment() {
		return get_query_var( 'pay_for_order' ) && get_query_var( 'change_payment_method' );
	}

	/**
	 * Unset Revolut public_id
	 */
	protected function unset_revolut_public_id() {
		WC()->session->__unset( "{$this->api_client->mode}_revolut_public_id" );
	}

	/**
	 * Unset Revolut public_id
	 */
	protected function unset_revolut_express_checkout_public_id() {
		WC()->session->__unset( "{$this->api_client->mode}_revolut_express_checkout_public_id" );
	}

	/**
	 * Set Revolut public_id
	 *
	 * @param string $value Revolut public id.
	 */
	protected function set_revolut_public_id( $value ) {
		WC()->session->set( "{$this->api_client->mode}_revolut_public_id", $value );
	}

	/**
	 * Set Revolut public_id
	 *
	 * @param string $value Revolut public id.
	 */
	public function set_revolut_express_checkout_public_id( $value ) {
		WC()->session->set( "{$this->api_client->mode}_revolut_express_checkout_public_id", $value );
	}

	/**
	 * Get Revolut public_id
	 *
	 * @return array|string|null
	 */
	protected function get_revolut_public_id() {
		$public_id = WC()->session->get( "{$this->api_client->mode}_revolut_public_id" );

		if ( empty( $public_id ) ) {
			return null;
		}

		$order_id = $this->get_revolut_order_by_public_id( $public_id );

		if ( empty( $order_id ) ) {
			return null;
		}

		return $public_id;
	}

	/**
	 * Get Revolut public_id
	 *
	 * @return array|string|null
	 */
	protected function get_revolut_express_checkout_public_id() {
		return WC()->session->get( "{$this->api_client->mode}_revolut_express_checkout_public_id" );
	}

	/**
	 * Get Revolut Merchant Public Key
	 *
	 * @return array|string|null
	 */
	protected function get_revolut_merchant_public_key() {
		return WC()->session->get( "{$this->api_client->mode}_revolut_merchant_public_key" );
	}

	/**
	 * Set  Revolut Merchant Public Key
	 *
	 * @param string $value Revolut Merchant public Key.
	 */
	protected function set_revolut_merchant_public_key( $value ) {
		WC()->session->set( "{$this->api_client->mode}_revolut_merchant_public_key", $value );
	}

	/**
	 * Replace dashes
	 *
	 * @param mixed $uuid uuid.
	 *
	 * @return string|string[]|null
	 */
	protected function uuid_dashes( $uuid ) {
		if ( is_array( $uuid ) ) {
			if ( isset( $uuid[0] ) ) {
				$uuid = $uuid[0];
			}
		}

		$result = preg_replace( '/(\w{8})(\w{4})(\w{4})(\w{4})(\w{12})/i', '$1-$2-$3-$4-$5', $uuid );

		return $result;
	}

	/**
	 * Check if is not minor currency
	 *
	 * @param string $currency currency.
	 *
	 * @return bool
	 */
	public function is_zero_decimal( $currency ) {
		return 'jpy' === strtolower( $currency );
	}

	/**
	 * Get order total for Api.
	 *
	 * @param float  $order_total order total amount.
	 * @param string $currency currency.
	 */
	public function get_revolut_order_total( $order_total, $currency ) {
		$order_total = round( (float) $order_total, 2 );

		if ( ! $this->is_zero_decimal( $currency ) ) {
			$order_total = round( $order_total * 100 );
		}

		return (int) $order_total;
	}

	/**
	 * Get order total for WC order.
	 *
	 * @param float  $revolut_order_total order total amount.
	 * @param string $currency currency.
	 */
	public function get_wc_order_total( $revolut_order_total, $currency ) {
		$order_total = $revolut_order_total;

		if ( ! $this->is_zero_decimal( $currency ) ) {
			$order_total = round( $order_total / 100, 2 );
		}

		return $order_total;
	}

	/**
	 * Get total amount value from Revolut order.
	 *
	 * @param array $revolut_order Revolut order.
	 */
	public function get_revolut_order_amount( $revolut_order ) {
		return isset( $revolut_order['order_amount'] ) && isset( $revolut_order['order_amount']['value'] ) ? (int) $revolut_order['order_amount']['value'] : 0;
	}

	/**
	 * Get shipping amount value from Revolut order.
	 *
	 * @param array $revolut_order Revolut order.
	 */
	public function get_revolut_order_total_shipping( $revolut_order ) {
		$shipping_total = isset( $revolut_order['delivery_method'] ) && isset( $revolut_order['delivery_method']['amount'] ) ? (int) $revolut_order['delivery_method']['amount'] : 0;
		$currency       = $this->get_revolut_order_currency( $revolut_order );

		if ( $shipping_total ) {
			return $this->get_wc_order_total( $shipping_total, $currency );
		}

		return 0;
	}

	/**
	 * Get currency from Revolut order.
	 *
	 * @param array $revolut_order Revolut order.
	 */
	public function get_revolut_order_currency( $revolut_order ) {
		return isset( $revolut_order['order_amount'] ) && isset( $revolut_order['order_amount']['currency'] ) ? $revolut_order['order_amount']['currency'] : '';
	}

	/**
	 * Get total shipping price.
	 */
	public function get_cart_total_shipping() {
		$cart_totals    = WC()->session->get( 'cart_totals' );
		$shipping_total = 0;
		if ( ! empty( $cart_totals ) && is_array( $cart_totals ) && in_array( 'shipping_total', array_keys( $cart_totals ), true ) ) {
			$shipping_total = $cart_totals['shipping_total'];
		}

		return $this->get_revolut_order_total( $shipping_total, get_woocommerce_currency() );
	}

	/**
	 * Get two-digit language iso code.
	 */
	public function get_lang_iso_code() {
		return substr( get_locale(), 0, 2 );
	}

	/**
	 * Check order status
	 *
	 * @param String $order_status data for checking.
	 */
	public function check_is_order_has_capture_status( $order_status ) {
		if ( 'authorize' !== $this->api_settings->get_option( 'payment_action' ) ) {
			return false;
		}

		if ( 'yes' !== $this->api_settings->get_option( 'accept_capture' ) ) {
			return false;
		}

		$order_status                 = ( 0 !== strpos( $order_status, 'wc-' ) ) ? 'wc-' . $order_status : $order_status;
		$selected_capture_status_list = $this->api_settings->get_option( 'selected_capture_status_list' );
		$customize_capture_status     = $this->api_settings->get_option( 'customise_capture_status' );

		if ( empty( $selected_capture_status_list ) || 'no' === $customize_capture_status ) {
			$selected_capture_status_list = array( 'wc-processing', 'wc-completed' );
		}

		return in_array( $order_status, $selected_capture_status_list, true );
	}

	/**
	 * Check order status
	 *
	 * @param string $amount order amount.
	 * @param string $currency order currency.
	 */
	public function get_available_card_brands( $amount, $currency ) {
		try {
			$this->api_client->set_public_key( $this->get_merchant_public_api_key() );
			$order_details = $this->api_client->get( "/available-payment-methods?amount=$amount&currency=$currency", true );
			if ( ! isset( $order_details['available_card_brands'] ) || empty( $order_details['available_card_brands'] ) ) {
				return array();
			}

			return array_map( 'strtolower', $order_details['available_card_brands'] );
		} catch ( Exception $e ) {
			$this->log_error( 'get_available_card_brands: ' . $e->getMessage() );
			return array();
		}
	}

	/**
	 * Check the current page
	 */
	public function is_order_payment_page() {
		try {
			global $wp;
			return is_checkout() && ! empty( $wp->query_vars['order-pay'] );
		} catch ( Exception $e ) {
			return false;
		}
	}
}
