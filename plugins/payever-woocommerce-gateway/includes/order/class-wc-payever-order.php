<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class WC_Payever_Order {
	/** @var WC_Order */
	private $order;

	private $version300_filter;

	private $version304_filter;

	private $version350_filter;

	public function __construct( $order ) {
		$this->order             = $order;
		$this->version300_filter = version_compare( WOOCOMMERCE_VERSION, '3.0', '<' );
		$this->version304_filter = version_compare( WOOCOMMERCE_VERSION, '3.0.4', '>' );
		$this->version350_filter = version_compare( WOOCOMMERCE_VERSION, '3.5', '<' );
	}

	/**
	 * @return mixed
	 */
	public function get_currency() {
		if ( $this->version300_filter ) {
			return $this->order->get_order_currency();
		}

		return $this->order->get_currency();
	}

	/**
	 * @return mixed
	 */
	public function get_shipping_tax() {
		if ( $this->version300_filter ) {
			return $this->order->get_total_shipping() + $this->order->get_shipping_tax();
		}

		return $this->order->get_shipping_total() + $this->order->get_shipping_tax();
	}

	public function get_billing_country() {
		if ( $this->version300_filter ) {
			return $this->order->billing_country;
		}

		return $this->order->get_billing_country();
	}

	public function has_shipping_address() {
		if ( $this->version304_filter ) {
			return $this->order->has_shipping_address();
		}

		return $this->order->shipping_address_1 || $this->order->shipping_address_2;
	}

	public function get_shipping_address_array() {
		if ( $this->version304_filter ) {
			return array(
				'country'    => $this->order->get_shipping_country(),
				'state'      => $this->order->get_shipping_state(),
				'address_1'  => $this->order->get_shipping_address_1(),
				'address_2'  => $this->order->get_shipping_address_2(),
				'first_name' => $this->order->get_shipping_first_name(),
				'last_name'  => $this->order->get_shipping_last_name(),
				'city'       => $this->order->get_shipping_city(),
				'postcode'   => $this->order->get_shipping_postcode(),
			);
		}
		return array(
			'country'    => $this->order->shipping_country,
			'state'      => $this->order->shipping_state,
			'address_1'  => $this->order->shipping_address_1,
			'address_2'  => $this->order->shipping_address_2,
			'first_name' => $this->order->shipping_first_name,
			'last_name'  => $this->order->shipping_last_name,
			'city'       => $this->order->shipping_city,
			'postcode'   => $this->order->shipping_postcode,
		);
	}

	public function get_billing_address_array() {
		if ( $this->version350_filter ) {
			return array(
				'country'    => $this->order->billing_country,
				'company'    => $this->order->billing_company,
				'email'      => $this->order->billing_email,
				'phone'      => $this->order->billing_phone,
				'state'      => $this->order->billing_state,
				'address_1'  => $this->order->billing_address_1,
				'address_2'  => $this->order->billing_address_2,
				'first_name' => $this->order->billing_first_name,
				'last_name'  => $this->order->billing_last_name,
				'city'       => $this->order->billing_city,
				'postcode'   => $this->order->billing_postcode,
			);
		}

		return array(
			'country'    => $this->order->get_billing_country(),
			'company'    => $this->order->get_billing_company(),
			'email'      => $this->order->get_billing_email(),
			'phone'      => $this->order->get_billing_phone(),
			'state'      => $this->order->get_billing_state(),
			'address_1'  => $this->order->get_billing_address_1(),
			'address_2'  => $this->order->get_billing_address_2(),
			'first_name' => $this->order->get_billing_first_name(),
			'last_name'  => $this->order->get_billing_last_name(),
			'city'       => $this->order->get_billing_city(),
			'postcode'   => $this->order->get_billing_postcode(),
		);
	}
}
