<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Helpers;

defined('ABSPATH') or exit;

/**
 * Helpers for handling money.
 *
 * @since 1.0.0
 */
class MoneyHelper
{
    /**
     * Converts a decimal amount to cents.
     *
     * @see \wc_add_number_precision() wrapper
     *
     * @since 1.0.0
     *
     * @param float $amount a decimal amount
     * @return int
     */
    public static function convertDecimalToCents(float $amount) : int
    {
        return (int) round($amount * 100);
    }

    /**
     * Converts an amount in cents to a decimal number.
     *
     * @see \wc_remove_number_precision() wrapper
     *
     * @since 1.0.0
     *
     * @param int $amountInCents an amount in cents
     * @return float
     */
    public static function convertCentsToDecimal(int $amountInCents) : float
    {
        return (float) ($amountInCents / 100);
    }
}
