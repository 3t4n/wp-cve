<?php
/**
 * PeachPay Stripe order util class.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

require_once PEACHPAY_ABSPATH . 'core/abstract/class-peachpay-order-data.php';

/**
 * .
 */
final class PeachPay_Stripe_Order_Data extends PeachPay_Order_Data {

	/**
	 * Adds metadata about a stripe payment intent details to a order.
	 *
	 * @param WC_Order $order A order.
	 * @param array    $payment_intent_details Details about a payment intent.
	 *
	 * @return boolean Indicating if successful.
	 */
	public static function set_payment_intent_details( $order, $payment_intent_details ) {
		// Client secret should NOT be stored!.
		unset( $payment_intent_details['client_secret'] );
		return self::set_order_metadata( $order, '_pp_stripe_payment_intent_details', $payment_intent_details );
	}

	/**
	 * Gets metadata about a stripe payment intent for a order.
	 *
	 * @param WC_Order $order A order.
	 * @param string   $key Metadata key.
	 *
	 * @return mixed|null The details value or null.
	 */
	public static function get_payment_intent( $order, $key ) {
		return self::get_order_metadata( $order, '_pp_stripe_payment_intent_details', $key );
	}



	/**
	 * Adds metadata about a stripe payment method details to a order.
	 *
	 * @param WC_Order $order A order.
	 * @param array    $payment_method_details Details about a payment intent.
	 *
	 * @return boolean Indicating if successful.
	 */
	public static function set_payment_method_details( $order, $payment_method_details ) {
		return self::set_order_metadata( $order, '_pp_stripe_payment_method_details', $payment_method_details );
	}

	/**
	 * Gets metadata about a stripe payment method for a order.
	 *
	 * @param WC_Order $order A order.
	 * @param string   $key Metadata key.
	 *
	 * @return mixed|null The details value or null.
	 */
	public static function get_payment_method( $order, $key ) {
		return self::get_order_metadata( $order, '_pp_stripe_payment_method_details', $key );
	}

	/**
	 * Sets stripe payment method token for a subscription.
	 *
	 * @param WC_Order $order A order.
	 * @param string   $value The new payment token id and customer id.
	 *
	 * @return boolean Indicating if successful.
	 */
	public static function set_payment_method( $order, $value ) {
		return self::set_order_metadata( $order, '_pp_stripe_payment_method_details', $value );
	}

	/**
	 * Adds metadata about a stripe charge details to a order.
	 *
	 * @param WC_Order $order A order.
	 * @param array    $charge_details Details about a payment intent.
	 *
	 * @return boolean Indicating if successful.
	 */
	public static function set_charge_details( $order, $charge_details ) {
		return self::set_order_metadata( $order, '_pp_stripe_charge_details', $charge_details );
	}

	/**
	 * Gets metadata about a stripe charge for a order.
	 *
	 * @param WC_Order $order A order.
	 * @param string   $key Metadata key.
	 *
	 * @return mixed|null The details value or null.
	 */
	public static function get_charge( $order, $key ) {
		return self::get_order_metadata( $order, '_pp_stripe_charge_details', $key );
	}

	/**
	 * Gets the total payout for a PeachPay stripe order.
	 *
	 * @param WC_Order $order A order.
	 */
	public static function total_payout( $order ) {
		$balance_transaction = self::get_charge( $order, 'balance_transaction' );
		if ( null === $balance_transaction ) {
			return null;
		}

		$net = $balance_transaction['net'];

		$refund_total = self::get_charge( $order, 'amount_refunded' );
		if ( null === $refund_total ) {
			return $net;
		}

		$net -= $refund_total;

		return $net;
	}

	/**
	 * Gets the total fees for a PeachPay stripe order.
	 *
	 * @param WC_Order $order A order.
	 */
	public static function total_fees( $order ) {
		$balance_transaction = self::get_charge( $order, 'balance_transaction' );
		if ( null === $balance_transaction ) {
			return null;
		}

		$fees = $balance_transaction['fee'];

		$refunds = self::get_charge( $order, 'refunds' );
		if ( null === $refunds ) {
			return $fees;
		}

		foreach ( $refunds as $refund ) {
			if ( ! isset( $refund['balance_transaction'] ) ) {
				continue;
			}
			$fees += $refund['balance_transaction']['fee'];
		}

		return $fees;
	}

	/**
	 * Gets the total refunds for a PeachPay stripe order.
	 *
	 * @param WC_Order $order A order.
	 */
	public static function total_refunds( $order ) {
		$refund_total = self::get_charge( $order, 'amount_refunded' );

		if ( null === $refund_total ) {

			return 0;
		}

		return $refund_total;
	}

	/**
	 * Saves the previous order status for order disputes.
	 *
	 * @param WC_Order $order A order.
	 * @param array    $status The previous order status.
	 */
	public static function set_prev_status( $order, $status ) {
		return self::set_order_metadata( $order, '_pp_stripe_order_prev_status', $status );
	}

	/**
	 * Get the previous order status if dispute was won.
	 *
	 * @param WC_Order $order An order.
	 * @param string   $key Metadata key.
	 */
	public static function get_prev_status( $order, $key ) {
		return self::get_order_metadata( $order, '_pp_stripe_order_prev_status', $key );
	}

	/**
	 * Set the save to account flag to an order for 3DS cards.
	 *
	 * @param WC_Order $order A order.
	 * @param array    $status The save_to_account flag.
	 */
	public static function save_to_account( $order, $status ) {
		return self::set_order_metadata( $order, '_pp_stripe_order_save_to_account', $status );
	}

	/**
	 * Get the save to account flag fo an order for 3DS cards.
	 *
	 * @param WC_Order $order A order.
	 * @param string   $key Metadata key.
	 */
	public static function is_save_to_account( $order, $key ) {
		return self::get_order_metadata( $order, '_pp_stripe_order_save_to_account', $key );
	}
}
