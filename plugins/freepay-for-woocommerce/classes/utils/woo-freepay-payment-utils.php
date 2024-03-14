<?php

class WC_FreePay_Payment_Utils {
    /** */
	const META_PAYMENT_METHOD_CHANGE_COUNT = '_freepay_payment_method_change_count';
	/** */
	const META_FAILED_PAYMENT_COUNT = '_freepay_failed_payment_count';

    /**
	 * get_payment_link function
	 *
	 * If the order has a payment link, we will return it. If no link is set we return false.
	 *
	 * @access public
	 * @return string
	 */
	public static function get_payment_link($order) {
		return $order->get_meta( 'FREEPAY_PAYMENT_LINK' );
	}

	/**
	 * set_payment_link function
	 *
	 * Set the payment link on an order
	 *
	 * @access public
	 * @return void
	 */
	public static function set_payment_link( $order, $payment_link ) {
		$order->update_meta_data( 'FREEPAY_PAYMENT_LINK', $payment_link );
		$order->save_meta_data();
	}

	/**
	 * delete_payment_link function
	 *
	 * Delete the payment link on an order
	 *
	 * @access public
	 * @return void
	 */
	public static function delete_payment_link($order) {
		$order->delete_meta_data( 'FREEPAY_PAYMENT_LINK' );
		$order->save_meta_data();
	}

	/**
	 * get_payment_identifier function
	 *
	 * Set the payment identifier on an order
	 *
	 * @access public
	 * @return void
	 */
	public static function get_payment_identifier($order) {
		return $order->get_meta( 'FREEPAY_PAYMENT_IDENTIFIER' );
	}

	/**
	 * set_payment_identifier function
	 *
	 * Set the payment identifier on an order
	 *
	 * @access public
	 * @return void
	 */
	public static function set_payment_identifier( $order, $payment_identifier ) {
		$order->update_meta_data( 'FREEPAY_PAYMENT_IDENTIFIER', $payment_identifier );
		$order->save_meta_data();
	}

    /**
	 * Increase the amount of payment attemtps done through FreePay
	 *
	 * @return int
	 */
	public static function get_failed_freepay_payment_count($order) {
		$count = $order->get_meta( self::META_FAILED_PAYMENT_COUNT );
		if ( empty( $count ) ) {
			$count = 0;
		}

		return $count;
	}

    /**
	 * Increase the amount of payment attemtps done through FreePay
	 *
	 * @return int
	 */
	public static function increase_failed_freepay_payment_count($order) {
		$count = self::get_failed_freepay_payment_count($order);

		$order->update_meta_data( self::META_FAILED_PAYMENT_COUNT, ++ $count );
		$order->save_meta_data();

		return $count;
	}

    /**
	 * Reset the failed payment attempts made through the FreePay gateway
	 */
	public static function reset_failed_freepay_payment_count($order) {
		$order->delete_meta_data( self::META_FAILED_PAYMENT_COUNT );
		$order->save_meta_data();
	}

    /**
	 * Gets the amount of times the customer has updated his card.
	 *
	 * @return int
	 */
	public static function get_payment_method_change_count($order) {
		$count = $order->get_meta( self::META_PAYMENT_METHOD_CHANGE_COUNT );

		if ( ! empty( $count ) ) {
			return $count;
		}

		return 0;
	}

    /**
	 * Increases the amount of times the customer has updated his card.
	 *
	 * @return int
	 */
	public static function increase_payment_method_change_count($order) {
		$count = self::get_payment_method_change_count($order);

		$order->update_meta_data( self::META_PAYMENT_METHOD_CHANGE_COUNT, ++ $count );
		$order->save_meta_data();

		return $count;
	}
}