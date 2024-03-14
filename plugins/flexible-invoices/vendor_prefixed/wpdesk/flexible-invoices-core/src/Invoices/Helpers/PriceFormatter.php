<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

class PriceFormatter
{
    /**
     * @param float|string $price
     *
     * @return float
     */
    public static function string_to_float($price) : float
    {
        if (\is_string($price)) {
            return (float) \str_replace(',', '.', $price);
        }
        return (float) $price;
    }
    /**
     * @param float   $price
     * @param int     $decimals
     * @param ?string $decimal_separator
     * @param ?string $thousand_separator
     *
     * @return float
     */
    public static function number_format(float $price, int $decimals = 2, $decimal_separator = '.', $thousand_separator = ' ') : float
    {
        return (float) \number_format($price, $decimals, $decimal_separator, $thousand_separator);
    }
}
