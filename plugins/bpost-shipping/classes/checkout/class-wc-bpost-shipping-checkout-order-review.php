<?php

namespace WC_BPost_Shipping\Checkout;


use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Factory;
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Builder;
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Solver;

class WC_BPost_Shipping_Checkout_Order_Review {

	/** @var \WC_BPost_Shipping_Limitations */
	private $limitations;
	/** @var \WC_BPost_Shipping_Cart */
	private $cart;
	/** @var WC_BPost_Shipping_Api_Factory */
	private $factory;
	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter_woocommerce;
	/** @var \WC_BPost_Shipping_Posted */
	private $posted;


	/**
	 * WC_BPost_Shipping_Checkout_Order_Review constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter_woocommerce
	 * @param WC_BPost_Shipping_Api_Factory $factory
	 * @param \WC_BPost_Shipping_Limitations $limitations
	 * @param \WC_BPost_Shipping_Cart $cart
	 * @param \WC_BPost_Shipping_Posted $posted
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter_woocommerce,
		WC_BPost_Shipping_Api_Factory $factory,
		\WC_BPost_Shipping_Limitations $limitations,
		\WC_BPost_Shipping_Cart $cart,
		\WC_BPost_Shipping_Posted $posted
	) {
		$this->adapter_woocommerce = $adapter_woocommerce;
		$this->limitations         = $limitations;
		$this->factory             = $factory;

		$this->cart   = $cart;
		$this->posted = $posted;
	}

	/**
	 * @return bool
	 */
	public function review_order() {
		$street_builder = new WC_BPost_Shipping_Street_Builder( new WC_BPost_Shipping_Street_Solver() );

		$limitation_are_ok = $this->limitations->validate_limitations(
			$this->posted->get_payment_method(),
			$street_builder->get_street_items( WC()->customer->get_shipping_address(), WC()->customer->get_shipping_address_2() )->get_street(),
			$this->cart->get_weight_in_kg(),
			$this->factory->get_product_configuration(),
			$this->factory->get_api_connector()
		);

		foreach ( $this->limitations->get_errors() as $error ) {
			$this->adapter_woocommerce->add_notice( $error, 'error' );
		}

		return $limitation_are_ok;
	}
}
