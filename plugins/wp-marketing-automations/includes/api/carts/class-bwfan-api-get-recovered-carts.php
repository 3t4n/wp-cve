<?php

class BWFAN_API_Get_Recovered_Carts extends BWFAN_API_Base {
	public static $ins;
	public $total_count = 0;
	public $count_data = [];

	public function __construct() {
		parent::__construct();
		$this->method             = WP_REST_Server::READABLE;
		$this->route              = '/carts/recovered/';
		$this->pagination->offset = 0;
		$this->pagination->limit  = 10;
		$this->request_args       = array(
			'search' => array(
				'description' => __( '', 'wp-marketing-automations' ),
				'type'        => 'string',
			),
			'offset' => array(
				'description' => __( 'Recovered carts list Offset', 'wp-marketing-automations' ),
				'type'        => 'integer',
			),
			'limit'  => array(
				'description' => __( 'Per page limit', 'wp-marketing-automations' ),
				'type'        => 'integer',
			)
		);
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function default_args_values() {
		return [
			'search' => '',
			'offset' => 0,
			'limit'  => 10
		];
	}

	public function process_api_call() {
		$search = $this->get_sanitized_arg( 'search', 'text_field' );
		$offset = ! empty( $this->get_sanitized_arg( 'offset', 'text_field' ) ) ? $this->get_sanitized_arg( 'offset', 'text_field' ) : 0;
		$limit  = ! empty( $this->get_sanitized_arg( 'limit', 'text_field' ) ) ? $this->get_sanitized_arg( 'limit', 'text_field' ) : 25;

		$recovered_carts  = BWFAN_Recoverable_Carts::get_recovered_carts( $search, $offset, $limit );
		$result           = [];
		$this->count_data = BWFAN_Common::get_carts_count();

		if ( ! isset( $recovered_carts['items'] ) ) {
			return $this->success_response( [], __( 'No recovered carts found', 'wp-marketing-automations' ) );
		}
		$orders  = $recovered_carts['items'];
		$nowDate = new DateTime( 'now', new DateTimeZone( "UTC" ) );
		foreach ( $orders as $order ) {
			if ( ! $order instanceof WC_Order ) {
				continue;
			}
			$cartDate = new DateTime( $order->get_date_created()->date( 'Y-m-d H:i:s' ) );
			$diff     = date_diff( $nowDate, $cartDate, true );
			$diff     = BWFAN_Common::get_difference_string( $diff );

			$result[] = [
				'id'            => $order->get_meta( '_bwfan_recovered_ab_id' ),
				'order_id'      => $order->get_id(),
				'email'         => $order->get_billing_email(),
				'phone'         => $order->get_billing_phone(),
				'f_name'        => $order->get_billing_first_name(),
				'l_name'        => $order->get_billing_last_name(),
				'preview'       => $this->get_preview( $order ),
				'diffstring'    => $diff,
				'date'          => $order->get_date_created()->date( 'Y-m-d H:i:s' ),
				'items'         => $this->get_items( $order ),
				'total'         => $order->get_total(),
				'currency'      => $this->get_currency( $order ),
				'buyer_name'    => $this->get_order_name( $order ),
				'user_id'       => ! empty( $order->get_customer_id() ) ? $order->get_customer_id() : 0,
				'checkout_data' => ! is_null( $order->get_meta() ) ? $order->get_meta() : '',
			];
		}

		$result = BWFAN_Recoverable_Carts::populate_contact_info( $result );
		if ( isset( $recovered_carts['total_count'] ) ) {
			$this->total_count = $recovered_carts['total_count'];
			unset( $result['total_record'] );
		}

		return $this->success_response( $result, __( 'Recovered carts found', 'wp-marketing-automations' ) );
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return array
	 */
	public function get_preview( $order ) {
		$data        = array();
		$products    = array();
		$order_items = $order->get_items();
		foreach ( $order_items as $product ) {
			$products[] = array(
				'name'  => $product->get_name(),
				'qty'   => $product->get_quantity(),
				'price' => number_format( $order->get_line_subtotal( $product ), 2, '.', '' ),
			);
		}
		$data['order_id'] = $order->get_id();
		$data['products'] = $products;
		$data['billing']  = $order->get_formatted_billing_address();
		$data['shipping'] = $order->get_formatted_shipping_address();
		$data['discount'] = $order->get_total_discount();
		$data['total']    = $order->get_total();

		return $data;
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return array
	 */
	public function get_items( $order ) {
		$names = [];
		foreach ( $order->get_items() as $value ) {
			if ( ! $value instanceof WC_Order_Item ) {
				continue;
			}

			$product_name = $value->get_name();
			$product_id   = $value->get_product_id();
			if ( $value->is_type( 'variable' ) ) {
				$product_id = $value->get_variation_id();
			}

			$names[ $product_id ] = $product_name;
		}

		return $names;
	}

	/**
	 * @param $contact_id
	 * @param $order_id
	 *
	 * @return string[]
	 */
	public function get_name( $contact_id, $order_id ) {
		$data = array( 'f_name' => '', 'l_name' => '' );
		if ( ! empty( $contact_id ) ) {
			if ( class_exists( 'BWFCRM_Contact' ) ) {
				$contact        = new BWFCRM_Contact( $contact_id );
				$contact_array  = $contact->get_array();
				$data['f_name'] = $contact_array['f_name'];
				$data['l_name'] = $contact_array['l_name'];

				return $data;
			}

			$contact_array  = ( new WooFunnels_Contact( null, null ) )->get_contact_by_contact_id( $contact_id );
			$data['f_name'] = $contact_array->f_name;
			$data['l_name'] = $contact_array->l_name;

			return $data;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return $data;
		}

		$data['f_name'] = $order->get_billing_first_name();
		$data['l_name'] = $order->get_billing_last_name();

		return $data;
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return array
	 */
	public function get_currency( $order ) {
		return [
			'code'              => ! is_null( $order->get_currency() ) ? $order->get_currency() : get_option( 'woocommerce_currency' ),
			'precision'         => wc_get_price_decimals(),
			'symbol'            => html_entity_decode( get_woocommerce_currency_symbol( $order->get_currency() ) ),
			'symbolPosition'    => get_option( 'woocommerce_currency_pos' ),
			'decimalSeparator'  => wc_get_price_decimal_separator(),
			'thousandSeparator' => wc_get_price_thousand_separator(),
			'priceFormat'       => html_entity_decode( get_woocommerce_price_format() ),
		];
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return mixed|string|void
	 */
	function get_order_name( $order ) {
		if ( ! $order instanceof WC_Order ) {
			return '';
		}

		$buyer = '';
		if ( $order->get_billing_first_name() || $order->get_billing_last_name() ) {
			/* translators: 1: first name 2: last name */
			$buyer = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $order->get_billing_first_name(), $order->get_billing_last_name() ) );
		} elseif ( $order->get_billing_company() ) {
			$buyer = trim( $order->get_billing_company() );
		} elseif ( $order->get_customer_id() ) {
			$user  = get_user_by( 'id', $order->get_customer_id() );
			$buyer = ucwords( $user->display_name );
		}

		return apply_filters( 'woocommerce_admin_order_buyer_name', $buyer, $order );
	}

	public function get_result_total_count() {
		return $this->total_count;
	}

	public function get_result_count_data() {
		return $this->count_data;
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return mixed|string|void
	 */
	public function get_full_name( $order ) {
		if ( ! $order instanceof WC_Order ) {
			return '';
		}

		$buyer = '';
		if ( $order->get_billing_first_name() || $order->get_billing_last_name() ) {
			/* translators: 1: first name 2: last name */
			$buyer = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $order->get_billing_first_name(), $order->get_billing_last_name() ) );
		} elseif ( $order->get_billing_company() ) {
			$buyer = trim( $order->get_billing_company() );
		} elseif ( $order->get_customer_id() ) {
			$user  = get_user_by( 'id', $order->get_customer_id() );
			$buyer = ucwords( $user->display_name );
		}

		return apply_filters( 'woocommerce_admin_order_buyer_name', $buyer, $order );
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return mixed
	 */
	public function get_email( $order ) {
		return $order->get_billing_email();
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return string
	 */
	public function get_user_display_name( $order ) {
		if ( empty( $order->get_customer_id() ) ) {
			return '';
		}

		$user = get_user_by( 'id', absint( $order->get_customer_id() ) );

		return $user instanceof WP_User ? $user->display_name : '';
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Recovered_Carts' );
