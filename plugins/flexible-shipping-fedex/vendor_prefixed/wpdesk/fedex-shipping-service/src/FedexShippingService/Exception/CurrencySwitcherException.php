<?php

namespace FedExVendor\WPDesk\FedexShippingService\Exception;

use FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings;
/**
 * Exception thrown when switcher is not accepted.
 *
 * @package WPDesk\FedexShippingService\Exception
 */
class CurrencySwitcherException extends \RuntimeException
{
    /**
     * @param ShopSettings $shop_settings .
     */
    public function __construct(\FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings $shop_settings)
    {
        $locale = $shop_settings->get_locale();
        $is_pl = 'pl_PL' === $locale;
        $pro_link = $is_pl ? 'https://octol.io/fedex-pro-cart-currency-pl' : 'https://octol.io/fedex-pro-cart-currency';
        $message = \sprintf(\__('Multicurrency is supported by %1$sFlexible Shipping FedEx PRO →%2$s', 'flexible-shipping-fedex'), '<a href="' . \esc_url($pro_link) . '" target="_blank">', '</a>');
        parent::__construct($message);
    }
}
