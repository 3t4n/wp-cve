<?php

namespace FedExVendor\WPDesk\FedexShippingService\FedexApi;

use FedExVendor\WPDesk\AbstractShipping\Shipment\Dimensions;
use FedExVendor\WPDesk\AbstractShipping\Shipment\Weight;
/**
 * Converts some strings for FedEx API
 *
 * @package WPDesk\FedexShippingService\FedexApi
 */
class FedexRequestManipulation
{
    const CURRENCY_MAPPING = [
        'CZK' => 'CZK',
        // Czech Republic Koruny
        'JPY' => 'JYE',
        // Japanese Yen
        'GBP' => 'UKL',
        // UK Pounds Sterling
        'CHF' => 'SFR',
    ];
    /**
     * FedEx wants maximum 2 chars and requires provinces only for Canada and USA. Let's filter them
     * and return empty string for others.
     *
     * @param string $province Province code.
     *
     * @return string
     */
    public static function filter_province_for_fedex($province)
    {
        $canada_provinces = ['AB', 'BC', 'MB', 'NB', 'NL', 'NT', 'NS', 'NU', 'ON', 'PE', 'QC', 'SK', 'YT'];
        $usa_provinces = ['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY', 'AA', 'AE', 'AP'];
        if (empty($province) || !\in_array($province, \array_merge($canada_provinces, $usa_provinces), \true)) {
            return '';
        }
        return $province;
    }
    /**
     * Convert character encoding to UTF-7.
     *
     * @param string $string String to convert.
     *
     * @return string
     */
    public static function convert_to_utf7($string)
    {
        if (empty($string)) {
            return '';
        }
        return \mb_convert_encoding($string, 'UTF-7');
    }
    /**
     * Convert weight unit.
     *
     * If shop has defined grams convert to KG. Fedex require only KG or LB.
     *
     * @param string $weight_unit Weight unit.
     *
     * @return string
     */
    public static function convert_weight_unit($weight_unit)
    {
        $weight_unit = \str_replace('S', '', \strtoupper($weight_unit));
        return $weight_unit;
    }
    /**
     * Convert dimension unit.
     *
     * @param string $dimension_unit Dimension unit.
     *
     * @return string
     */
    public static function convert_dimension_unit($dimension_unit)
    {
        return \strtoupper($dimension_unit);
    }
    /**
     * Convert currency from Unicode CLDR to FedEx.
     *
     * @param string $currency
     *
     * @return string
     *
     * @see https://www.fedex.com/us/developer/WebHelp/ws/2014/dvg/WS_DVG_WebHelp/Appendix_F_Currency_Codes.htm
     * @see https://github.com/woocommerce/woocommerce/blob/master/includes/wc-core-functions.php#L333
     */
    public static function convert_currency_to_fedex($currency)
    {
        $convert = self::CURRENCY_MAPPING;
        return isset($convert[$currency]) ? $convert[$currency] : $currency;
    }
    /**
     * Convert currency from Fedex to WooCommerce.
     *
     * @param string $fedex_currency
     *
     * @return string
     */
    public static function convert_currency_from_fedex($fedex_currency)
    {
        $convert = \array_flip(self::CURRENCY_MAPPING);
        return isset($convert[$fedex_currency]) ? $convert[$fedex_currency] : $fedex_currency;
    }
}
