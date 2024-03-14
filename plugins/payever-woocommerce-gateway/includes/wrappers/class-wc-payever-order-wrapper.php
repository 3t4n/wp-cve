<?php


class WC_Payever_Order_Wrapper {

	/**
	 * @param $order_id
	 *
	 * @return WC_Order
	 */
	public function get_wc_order( $order_id ) {
		return new WC_Order( $order_id );
	}

	/**
	 * Get Payment ID.
	 *
	 * @param $order_id
	 *
	 * @return false|mixed
	 */
	public function get_payment_id( $order_id ) {
		$payment_id = get_post_meta( $order_id, WC_Payever_Gateway::PAYEVER_PAYMENT_ID, true );
		if ( empty( $payment_id ) ) {
			return false;
		}

		return $payment_id;
	}

	/**
	 * Set Payment ID.
	 *
	 * @param $order_id
	 * @param $payment_id
	 * @return int|bool Meta ID if the key didn't exist, true on successful update,
	 *                  false on failure or if the value passed to the function
	 *                  is the same as the one that is already in the database.
	 */
	public function set_payment_id( $order_id, $payment_id ) {
		return update_post_meta( $order_id, WC_Payever_Gateway::PAYEVER_PAYMENT_ID, $payment_id );
	}

	/**
	 * @param $order
	 * @param $note
	 * @param int $is_customer_note
	 * @param bool $added_by_user
	 *
	 * @return mixed
	 */
	public function add_order_note( $order, $note, $is_customer_note = 0, $added_by_user = null ) {
		return $order->add_order_note( $note, $is_customer_note, $added_by_user );
	}

	/**
	 * @param $order
	 * @param string $context
	 *
	 * @return mixed
	 */
	public function get_customer_id( $order, $context = 'view' ) {
		return $order->get_customer_id( $context );
	}

	/**
	 * @param $refund_id
	 *
	 * @return WC_Order_Refund
	 */
	public function get_order_refunded( $refund_id ) {
		return new WC_Order_Refund( $refund_id );
	}

	/**
	 * @return WC_Order_Refund[]
	 */
	public function get_refunds() {
		return array();
	}
}
