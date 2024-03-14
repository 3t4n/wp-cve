<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for extending the WooCommerce Store API
 */
class ShopAsClient_Extend_Store_Endpoint {

	/**
	 * Extension name.
	 *
	 * @var string
	 */
	public static $name = 'ptwoo-shop-as-client';

	/**
	 * The name of the extension.
	 *
	 * @return string
	 */
	public function get_name() {
		return static::$name;
	}

	/**
	 * When called invokes any initialization/setup for the extension.
	 */
	public function initialize() {
		woocommerce_store_api_register_update_callback(
			array(
				'namespace' => $this->get_name(),
				'callback'  => array( $this, 'store_api_update_callback' ),
			)
		);

		add_action( 'woocommerce_store_api_checkout_order_processed', array( $this, 'process_order' ) );
	}

	/**
	 * Add Store API schema data.
	 *
	 * @return array
	 */
	public function store_api_schema_callback() {
		return array();
	}

	/**
	 * Add Store API endpoint data.
	 *
	 * @return array
	 */
	public function store_api_data_callback() {
		return array();
	}

	/**
	 * Update callback to be executed by the Store API.
	 *
	 * @param  array $data Extension data.
	 * @return void
	 */
	public function store_api_update_callback( $data ) {

		if ( ! ( isset( wc()->session ) && wc()->session->has_session() ) ) {
			wc()->session->set_customer_session_cookie( true );
		}

		// Persist "Shop As Client" option.
		$shop_as_client = isset( $data['shopAsClient'] ) ? $data['shopAsClient'] : null;
		wc()->session->set( $this->get_name() . '_shop_as_client', $shop_as_client );

		// Persist "Create User" option.
		$create_user = isset( $data['createUser'] ) ? $data['createUser'] : null;
		wc()->session->set( $this->get_name() . '_create_user', $create_user );

		/**
		 * Persist current customer data.
		 *
		 * This is needed to switch customer data back to its state after the purchase.
		 */
		if ( ! class_exists( 'ShopAsClientPro_Extend_Store_Endpoint' ) ) {
			$customer_data = wc()->session->get( $this->get_name() . '_current_customer_data' );

			if ( $customer_data === null ) {
				$user_id       = get_current_user_id();
				$customer_data = static::get_customer_data_by_user_id( $user_id );
				wc()->session->set( $this->get_name() . '_current_customer_data', $customer_data );
			}
		}
	}

	/**
	 * Process order.
	 *
	 * @param  \WC_Order $order Order object.
	 * @return void
	 */
	public function process_order( $order ) {

		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		if ( ! shop_as_client_can_checkout() ) {
			return;
		}

		$shop_as_client = wc()->session->get( $this->get_name() . '_shop_as_client', false );
		$create_user    = wc()->session->get( $this->get_name() . '_create_user', false );

		if ( ! $shop_as_client ) {
			return;
		}

		$user_id    = 0;
		$user_email = $order->get_billing_email();

		if ( empty( $user_email ) ) {
			$user_email = apply_filters( 'shop_as_client_user_email_if_empty', $user_email, $order );
		}

		if ( empty( $user_email ) ) {
			return;
		}

		$user = get_user_by( 'email', $user_email );

		if ( $user instanceof \WP_User ) {
			$user_id = $user->ID;
		} else {

			$user_query = new \WP_User_Query(
				array(
					'exclude'    => array( $order->get_customer_id() ),
					'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => 'billing_email',
							'value'   => $user_email,
							'compare' => '=',
						),
					),
				)
			);

			$users = $user_query->get_results();

			if ( ! empty( $users ) ) {
				$user    = reset( $users );
				$user_id = $user->ID;
			} elseif ( $create_user ) {
				$user_id = shop_as_client_create_customer(
					$user_email,
					$order->get_billing_first_name(),
					$order->get_billing_last_name()
				);
			}
		}

		if ( is_wp_error( $user_id ) ) {
			static::restore_customer_data();

			return new \WP_Error(
				'shop_as_client_checkout_order_process_error',
				sprintf(
					/* translators: %s error message. */
					__( 'Shop as Client failed to create user: %s', 'shop-as-client' ),
					$user_id->get_error_message()
				)
			);
		}

		$order->update_meta_data( '_billing_shop_as_client', 'yes' );
		$order->update_meta_data( '_billing_shop_as_client_handler_user_id', get_current_user_id() );
		$order->update_meta_data( '_billing_shop_as_client_checkout', 'blocks' );

		$order->set_customer_id( $user_id );
		$order->save();

		if ( apply_filters( 'shop_as_client_update_customer_data', false ) ) {
			$customer      = new \WC_Customer( $user_id );
			$customer_data = static::get_customer_data_by_order_id( $order->get_id() );
			static::switch_customer_data( $customer, $customer_data );
			$customer->save();
		}

		do_action( 'shop_as_client_checkout_order_processed', $order, $user_id );

		static::restore_customer_data();

		// Clear the extension's session data.
		wc()->session->__unset( $this->get_name() . '_shop_as_client' );
		wc()->session->__unset( $this->get_name() . '_create_user' );
		wc()->session->__unset( $this->get_name() . '_current_customer_data' );
	}

	/**
	 * Restore customer data to its state before the purchase.
	 *
	 * @return void
	 */
	public static function restore_customer_data() {
		$user_id  = get_current_user_id();
		$customer = new \WC_Customer( $user_id );

		$customer_data = wc()->session->get( static::$name . '_current_customer_data' );

		static::switch_customer_data( $customer, $customer_data );

		$customer->save();
	}

	/**
	 * Get customer data by user ID.
	 *
	 * @param  int|\WC_Customer $user_id The user ID, or the WC_Customer object.
	 * @return array
	 */
	public static function get_customer_data_by_user_id( $user_id ) {
		$customer = new \WC_Customer( $user_id );

		$customer_data = array(
			'billing_first_name'  => $customer->get_billing_first_name(),
			'billing_last_name'   => $customer->get_billing_last_name(),
			'billing_company'     => $customer->get_billing_company(),
			'billing_address_1'   => $customer->get_billing_address_1(),
			'billing_address_2'   => $customer->get_billing_address_2(),
			'billing_city'        => $customer->get_billing_city(),
			'billing_state'       => $customer->get_billing_state(),
			'billing_postcode'    => $customer->get_billing_postcode(),
			'billing_country'     => $customer->get_billing_country(),
			'billing_email'       => $customer->get_billing_email(),
			'billing_phone'       => $customer->get_billing_phone(),
			'shipping_first_name' => $customer->get_shipping_first_name(),
			'shipping_last_name'  => $customer->get_shipping_last_name(),
			'shipping_company'    => $customer->get_shipping_company(),
			'shipping_address_1'  => $customer->get_shipping_address_1(),
			'shipping_address_2'  => $customer->get_shipping_address_2(),
			'shipping_city'       => $customer->get_shipping_city(),
			'shipping_state'      => $customer->get_shipping_state(),
			'shipping_postcode'   => $customer->get_shipping_postcode(),
			'shipping_country'    => $customer->get_shipping_country(),
			'shipping_phone'      => $customer->get_shipping_phone(),
		);

		return $customer_data;
	}

	/**
	 * Get customer data by order ID.
	 *
	 * @param  int $order_id The order ID, or the WC_Order object.
	 * @return array
	 */
	public static function get_customer_data_by_order_id( $order_id ) {
		$order = new \WC_Order( $order_id );

		$customer_data = array(
			'billing_first_name'  => $order->get_billing_first_name(),
			'billing_last_name'   => $order->get_billing_last_name(),
			'billing_company'     => $order->get_billing_company(),
			'billing_address_1'   => $order->get_billing_address_1(),
			'billing_address_2'   => $order->get_billing_address_2(),
			'billing_city'        => $order->get_billing_city(),
			'billing_state'       => $order->get_billing_state(),
			'billing_postcode'    => $order->get_billing_postcode(),
			'billing_country'     => $order->get_billing_country(),
			'billing_email'       => $order->get_billing_email(),
			'billing_phone'       => $order->get_billing_phone(),
			'shipping_first_name' => $order->get_shipping_first_name(),
			'shipping_last_name'  => $order->get_shipping_last_name(),
			'shipping_company'    => $order->get_shipping_company(),
			'shipping_address_1'  => $order->get_shipping_address_1(),
			'shipping_address_2'  => $order->get_shipping_address_2(),
			'shipping_city'       => $order->get_shipping_city(),
			'shipping_state'      => $order->get_shipping_state(),
			'shipping_postcode'   => $order->get_shipping_postcode(),
			'shipping_country'    => $order->get_shipping_country(),
			'shipping_phone'      => $order->get_shipping_phone(),
		);

		return $customer_data;
	}

	/**
	 * Switch customer data.
	 *
	 * @param  \WC_Customer $customer Customer object.
	 * @param  array        $data     Customer data.
	 * @return void
	 */
	public static function switch_customer_data( $customer, $data ) {

		if ( ! $customer instanceof \WC_Customer ) {
			return;
		}

		foreach ( $data as $key => $value ) {
			if ( is_callable( array( $customer, "set_$key" ) ) ) {
				$customer->{"set_$key"}( $value );
			}
		}
	}
}
