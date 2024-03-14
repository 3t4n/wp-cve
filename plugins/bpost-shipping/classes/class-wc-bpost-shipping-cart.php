<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WC_BPost_Shipping_Cart allows to pass some data through classes.
 * TODO: should be removed or split into more coherent structure
 */
class WC_BPost_Shipping_Cart {

	/** @var  WC_Cart */
	private $cart;

	/**
	 * @param WC_Cart $cart
	 */
	public function __construct( WC_Cart $cart ) {
		$this->cart = $cart;
	}

	/**
	 * @return float
	 */
	public function get_weight_in_g() {
		return $this->get_weight_in_kg() * 1000;
	}

	/**
	 * @return float
	 */
	public function get_weight_in_kg() {
		return wc_get_weight( $this->cart->get_cart_contents_weight(), 'kg' );
	}

	/**
	 * @return float
	 */
	public function get_discounted_subtotal() {
		return $this->cart->get_displayed_subtotal() - $this->cart->get_discount_total();
	}

	/**
	 * @return array
	 */
	public function get_used_coupons() {
		return $this->cart->get_applied_coupons();
	}

}
