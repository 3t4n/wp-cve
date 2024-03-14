<?php

namespace Woo_MP\Payment_Gateway;

defined( 'ABSPATH' ) || die;

/**
 * Parent class for payment gateway transaction processors.
 */
class Transaction_Processor {

    /**
     * Convert an amount to a given currency's smallest denomination.
     *
     * For example:
     *
     * ```php
     * $this->to_smallest_unit( 9.99, 'USD' ) === 999;
     * $this->to_smallest_unit( 1000, 'JPY' ) === 1000;
     * ```
     *
     * @param  int|float|string $amount   The amount to convert.
     * @param  string           $currency The currency of the amount.
     * @return int                        The amount in the smallest unit.
     */
    protected function to_smallest_unit( $amount, $currency ) {
        $zero_decimal_currencies = [
            'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA',
            'PYG', 'RWF', 'VND', 'VUV', 'XAF', 'XOF', 'XPF',
        ];

        if ( in_array( $currency, $zero_decimal_currencies, true ) ) {
            return absint( $amount );
        }

        return round( $amount, 2 ) * 100;
    }

}
