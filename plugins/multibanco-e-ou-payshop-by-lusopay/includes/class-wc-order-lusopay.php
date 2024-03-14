<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Order Class.
 *
 * These are our Orders, which extend the regular WooCommerce Orders, in order to abstract properties access after the 3.0 changes
 */
class WC_Order_Lusopay extends WC_Order {

	/**
	 * Returns the unique ID for this order.
	 *
	 * @return int
	 */
	public function lp_order_get_id() {
		return version_compare( WC_VERSION, '3.0', '>=' ) ? $this->get_id() : $this->id;
	}

	/**
	 * Returns the order payment method
	 *
	 * @return string
	 */
	public function lp_order_get_payment_method() {
		return version_compare( WC_VERSION, '3.0', '>=' ) ? $this->get_payment_method() : $this->payment_method;
	}

	/**
	 * Returns the order total
	 *
	 * @return float
	 */
	public function lp_order_get_total() {
		return version_compare( WC_VERSION, '3.0', '>=' ) ? $this->get_total() : $this->order_total;
	}

	/**
	 * Returns the order status
	 *
	 * @return string
	 */
	public function lp_order_get_status() {
		return version_compare( WC_VERSION, '3.0', '>=' ) ? $this->get_status() : $this->status;
	}

	/**
	 * Reduce order stock
	 */
	public function lp_order_reduce_order_stock() {
		if ( version_compare( WC_VERSION, '3.0', '>=' ) ) {
			wc_reduce_stock_levels( $this->lp_order_get_id() );
		} else {
			$this->reduce_order_stock();
		}
	}

	/**
	 * Return billing phone of the customer
	 * 
	 * @return string
	 */
	public function lp_order_get_billing_phone() {
		return version_compare( WC_VERSION, '3.0', '>=') ? $this->get_billing_phone() : $this->billing_phone;
	}

	/**
	 * Return title of the payment method
	 * 
	 * @return string
	 */
	public function lp_get_payment_method_title() {
		return version_compare( WC_VERSION, '3.0', '>=') ? $this->get_payment_method_title() : $this->payment_method_title;
	}
}
