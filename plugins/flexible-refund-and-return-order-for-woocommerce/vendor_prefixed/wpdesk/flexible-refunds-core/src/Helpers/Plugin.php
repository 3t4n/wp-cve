<?php

namespace FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Helpers;

use FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Integration;
class Plugin
{
    /**
     * Get URL to product page of Pro version.
     *
     * @return string
     */
    public static function get_url_to_pro() : string
    {
        return \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/elastyczne-zwroty-i-reklamacje-woocommerce/' : 'https://wpdesk.net/products/flexible-refund-and-return-order-for-woocommerce/';
    }
    /**
     * Get URL to the docs page.
     *
     * @return string
     */
    public static function get_url_to_docs() : string
    {
        return \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/elastyczne-zwroty-i-reklamacje-woocommerce/' : 'https://wpdesk.net/docs/flexible-refund-and-cancel-order-for-woocommerce/';
    }
    /**
     * Get URL to product page of Pro version.
     *
     * @return string
     */
    public static function add_row_class() : string
    {
        return \FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Integration::is_super() ? 'add_row' : 'add-row-free';
    }
}
