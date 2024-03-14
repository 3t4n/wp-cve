<?php

namespace WC_BPost_Shipping\Label;

use WC_Order;

class WC_BPost_Shipping_Label_Post {
	private $order_reference;
	/** @var WC_Order */
	private $order;
	/** @var \WC_BPost_Shipping_Meta_Handler */
	private $meta_handler;

	/**
	 * WC_BPost_Shipping_Label_Post constructor.
	 *
	 * @param \WC_BPost_Shipping_Meta_Handler $meta_handler
	 * @param WC_Order $order
	 */
	public function __construct( \WC_BPost_Shipping_Meta_Handler $meta_handler, WC_Order $order ) {
		$this->meta_handler = $meta_handler;
		$this->order        = $order;
	}

	/**
	 * @return WC_Order
	 */
	public function get_order() {
		return $this->order;
	}

	/**
	 * @return int
	 */
	public function get_post_id() {
		return $this->order->get_id();
	}

	/**
	 * Retrieve the country available for an order ()
	 * @return string [A-Z]{2} aka ISO 3166-1 alpha-2
	 */
	public function get_order_country() {
		$billing_address  = $this->order->get_address();
		$shipping_address = $this->order->get_address( 'shipping' );

		$country = '';

		if ( array_key_exists( 'country', $billing_address ) && ! empty( $billing_address['country'] ) ) {
			$country = $billing_address['country'];
		}
		if ( array_key_exists( 'country', $shipping_address ) && ! empty( $shipping_address['country'] ) ) {
			$country = $shipping_address['country'];
		}

		return $country;
	}

	/**
	 * @return string order reference
	 */
	public function get_order_reference() {
		if ( $this->order_reference ) {
			return $this->order_reference;
		}

		$this->order_reference = $this->meta_handler->get_order_reference();

		return $this->order_reference;
	}


}
