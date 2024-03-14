<?php

namespace Woo_MP;

use WC_Order;

defined( 'ABSPATH' ) || die;

/**
 * Transparently adds plugin-specific functionality to a WooCommerce order
 * object (most often an instance of 'WC_Order').
 *
 * Use it like a regular WooCommerce order object.
 */
class Woo_MP_Order {

    /**
     * The core order object.
     *
     * @var WC_Order|object
     */
    private $order;

    /**
     * Set the core order object.
     *
     * @param WC_Order|object $order The order.
     */
    public function __construct( $order ) {
        $this->order = $order;
    }

    /**
     * Call a method on the core order object.
     *
     * @param  string $name      The method name.
     * @param  array  $arguments The arguments to pass to the method.
     * @return mixed             The return value of the method.
     */
    public function __call( $name, $arguments ) {
        return $this->order->$name( ...$arguments );
    }

    /**
     * Get the core order object.
     *
     * @return WC_Order|object The order.
     */
    public function get_core_order() {
        return $this->order;
    }

    /**
     * Get a list of charge properties and their default values.
     *
     * This is for normalizing charge records across versions.
     *
     * @return array The properties and their defaults.
     */
    private function get_charge_defaults() {
        return [
            'id'              => '',
            'date'            => current_time( 'M d, Y' ),
            'last4'           => '',
            'amount'          => 0,
            'currency'        => '',
            'captured'        => false,
            'held_for_review' => false,
        ];
    }

    /**
     * Get manual payments.
     *
     * @return array All manual payments.
     */
    public function get_woo_mp_payments() {
        $payments = json_decode(
            $this->get_meta( 'woo-mp-' . WOO_MP_PAYMENT_PROCESSOR . '-charges', true ),
            true
        ) ?: [];

        $charge_defaults = $this->get_charge_defaults();

        foreach ( $payments as $key => $payment ) {
            $payments[ $key ] += $charge_defaults;
        }

        return $payments;
    }

    /**
     * Add a manual payment.
     *
     * @param array $payment Associative array of the following format:
     *
     * [
     *     'id'              => '',    // The transaction ID.
     *     'last4'           => '',    // The last four digits of the card that was charged.
     *     'amount'          => 0,     // The payment amount.
     *     'currency'        => '',    // The currency the payment was made in. This should be a 3-digit code.
     *     'captured'        => false, // Whether the charge was captured.
     *     'held_for_review' => false  // Whether the charge was held for review.
     * ]
     *
     * @return void
     */
    public function add_woo_mp_payment( $payment ) {
        $payment += $this->get_charge_defaults();

        $payments = $this->get_woo_mp_payments();

        $payments[] = $payment;

        $this->update_meta_data( 'woo-mp-' . WOO_MP_PAYMENT_PROCESSOR . '-charges', json_encode( $payments ) );
    }

    /**
     * Get the total amount paid.
     *
     * @param  string $currency The currency code of the payments to include in the calculation.
     *                          You can pass `'all_currencies'` to get the total regardless of currency.
     * @return float            The amount.
     */
    public function get_total_amount_paid( $currency = 'order_currency' ) {
        $payments = $this->get_woo_mp_payments();

        if ( $currency !== 'all_currencies' ) {
            $currency = $currency === 'order_currency' ? $this->get_currency() : $currency;

            $payments = wp_list_filter( $payments, [ 'currency' => $currency ] );
        }

        return round( array_sum( array_column( $payments, 'amount' ) ), 2 );
    }

    /**
     * Get the total amount unpaid.
     *
     * If the amount paid is greater than the order total, a negative number will be returned.
     *
     * @return float The amount.
     */
    public function get_total_amount_unpaid() {
        return round( $this->get_total() - $this->get_total_amount_paid(), 2 );
    }

    /**
     * Check whether the order has had multiple payments in different currencies.
     *
     * @return bool Whether this is a multi-currency order.
     */
    public function is_multicurrency() {
        return count( array_unique( array_column( $this->get_woo_mp_payments(), 'currency' ) ) ) > 1;
    }

}
