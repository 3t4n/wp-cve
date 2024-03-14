<?php

/**
 * Currency Switcher Exception.
 *
 * @package WPDesk\AbstractShipping\Exception
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService\Exception;

/**
 * Exception thrown when switcher is not accepted.
 *
 * @package WPDesk\AbstractShipping\Exception
 */
class CurrencySwitcherException extends \RuntimeException
{
    /**
     * CurrencySwitcherException constructor.
     */
    public function __construct()
    {
        $link = 'pl_PL' === \get_locale() ? 'https://wpde.sk/ups-pro-currency-pl' : 'https://wpde.sk/ups-pro-currency';
        $message = \sprintf(
            // Translators: link.
            \__('Multi currency is supported by Flexible Shipping UPS Pro version only! %1$sCheck out more: %2$s%3$s', 'flexible-shipping-ups'),
            '<a href="' . $link . '" target="_blank">',
            $link,
            '</a>'
        );
        parent::__construct($message);
    }
}
